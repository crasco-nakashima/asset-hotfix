<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Email\Email;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Cake\Network\Http\Client;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
  public $components = array('RequestHandler');
  /**
   * Initialization hook method.
   *
   * Use this method to add common initialization code like loading components.
   *
   * @return void
   */
  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('Flash');
  }


  public function getErrMsg($msgArray)
  {
    $Errmsg = '';
    foreach ($msgArray as $key => $value) {
      foreach ($value as $msg) {
        $Errmsg .= $msg . '<br>';
      }
    }
    return $Errmsg;
  }

  public function sprintf2($str = '', $vars = array(), $char = '%')
  {
    if (!$str) return '';
    if (count($vars) > 0) {
      foreach ($vars as $k => $v) {
        $str = str_replace($char . $k, $v, $str);
      }
    }
    return $str;
  }

  public function sendNotifyMail($email, $type, $param = null)
  {
    $autoSendMail = $this->loadModel('AutoSendMail')->get($type, ['contain' => ['MailTransport']]);
    $transport = $autoSendMail->mail_transport;
    $honbun = $this->sprintf2($autoSendMail->honbun, $param);
    try {
      if ($transport->class_name == 'sendmail') {
        $headers = 'From: ' . $transport->email . "\r\n" .
          'Reply-To: ' . $transport->email . "\r\n" .
          'X-Mailer: PHP/' . phpversion();
        $check = mail($email, $autoSendMail->title, $honbun, $headers);
        if ($check) {
          $this->log('enter sendmail true!!!!!!', 'debug');
        } else {
          $this->log('enter sendmail false!!!!!!', 'debug');
        }
      } else {
        Email::configTransport($transport->name, [
          'host' => $transport->host,
          'port' => $transport->port,
          'username' => $transport->email,
          'password' => $transport->password,
          'className' => $transport->class_name
        ]);
        Email::deliver(
          $email,
          $autoSendMail->title,
          $honbun,
          [
            'from' => $transport->email,
            'transport' => $transport->name
          ]
        );
      }
    } catch (\Exception  $e) {
      $this->log(__('エラー・送信できませんでした。'), 'debug');
      $this->Flash->error_web(__('送信できませんでした。'));
    }
    return $transport;
  }

  public function registerNewsletter()
  {
    $magazineMailListTable = TableRegistry::get('MagazineMailList');
    $magazineMailList = $magazineMailListTable->newEntity();

    //check if logged in
    $isLogged = $this->request->session()->check('Auth.User');
    if ($isLogged) {
      $auth = $this->request->session()->read('Auth.User');
      $magazineMailList->email = $auth['mail'];
      if ($magazineMailListTable->exists(['email' => $auth['mail']])) {
        $this->log('logged in. ' . $auth['mail'], 'debug');
        $magazineMailList = $magazineMailListTable->get($auth['mail']);
      }
    }

    if ($this->request->is(['patch', 'post', 'put'])) {
      $data = $this->request->data;
      $json = $this->getRecaptchaJson($data);

      //マガジンメールのarrayをstringにする
      if ($data['magazine_mail'] != null) {
        $data['magazine_mail'] = implode(',', $data['magazine_mail']);
        $this->log('data maga: ' . $data['magazine_mail'], 'debug');
        if (!$isLogged) {
          if ($magazineMailListTable->exists(['email' => $data['email']])) {
            $magazineMailList = $magazineMailListTable->get($data['email']);
          }
        } else {
          $data['email'] = $magazineMailList->email;
        }
      } else {
        $this->Flash->error_web(__('受信先を選択してください。'));
        $this->set('magazineMailList', $magazineMailList);
        return;
      }
      $magazineMailList = $magazineMailListTable->patchEntity($magazineMailList, $data);
      if ($json['success'] && $magazineMailListTable->save($magazineMailList)) {
        $this->Flash->success_web(__('保存しました。'));
        $mailParams = array();
        $mailParams['email'] = $magazineMailList->email;
        $this->sendNotifyMail($magazineMailList->email, AUTO_SEND_MAIL_NEWSLETTER_KOUDOKU, $mailParams);
        $this->sendNotifyMail('asset@crasco.jp', AUTO_SEND_MAIL_NEWSLETTER_KOUDOKU, $mailParams);
        $this->redirect('/newsletter');
      } else {
        $this->Flash->error_web(__('保存できませんでした。'));
      }
    }
    $this->set('magazineMailList', $magazineMailList);
  }

  public function taikaiNewsletter($email = null)
  {
    $magazineMailListTable = TableRegistry::get('MagazineMailList');
    $magazineMailList = $magazineMailListTable->newEntity();
    //check if logged in
    $isLogged = $this->request->session()->check('Auth.User');
    //taikai when logged in
    if ($isLogged) {
      $auth = $this->request->session()->read('Auth.User');
      $magazineMailList->email = $auth['mail'];
      if ($magazineMailListTable->exists(['email' => $auth['mail']])) {
        $magazineMailList = $magazineMailListTable->get($auth['mail']);
        if ($magazineMailListTable->delete($magazineMailList)) {
          $mailParams = array();
          $mailParams['email'] = $magazineMailList->email;
          $this->sendNotifyMail($magazineMailList->email, AUTO_SEND_MAIL_NEWSLETTER_KAIJO, $mailParams);
          $this->sendNotifyMail('asset@crasco.jp', AUTO_SEND_MAIL_NEWSLETTER_KAIJO, $mailParams);
          $this->Flash->success_web(__('メールマガジン購読を解除しました。'));
        } else {
          $this->Flash->error_web(__('メールマガジン購読を解除できませんでした。'));
        }
      } else {
        $this->Flash->error_web(__('メールマガジンはまだ登録していません。'));
      }
      return $this->redirect('/newsletter');
    }
    //taikai when not logging in (takai email input needed)
    $data = $this->request->data;
    $json = $this->getRecaptchaJson($data);
    if ($magazineMailListTable->exists(['email' => $data['email']])) {
      $magazineMailList = $magazineMailListTable->get($data['email']);
      if ($json['success'] && $magazineMailListTable->delete($magazineMailList)) {
        $mailParams = array();
        $mailParams['email'] = $magazineMailList->email;
        $this->sendNotifyMail($magazineMailList->email, AUTO_SEND_MAIL_NEWSLETTER_KAIJO, $mailParams);
        $this->sendNotifyMail('asset@crasco.jp', AUTO_SEND_MAIL_NEWSLETTER_KAIJO, $mailParams);
        $this->Flash->success_web(__('メールマガジン購読を解除しました。'));
      } else {
        $this->Flash->error_web(__('メールマガジンはまだ登録していません。'));
      }
      return $this->redirect('/newsletter');
    } else {
      $this->Flash->error_web('メールマガジンはまだ登録していません。');
    }
    return $this->redirect('/newsletter');
  }

  public function addContact($subject = null)
  {
    $contactTable = TableRegistry::get('Contact');
    $contact = $contactTable->newEntity();
    if (!is_null($subject)) {
      $contact->subject = $subject;
    }
    $this->log('site: ' . $_SERVER['HTTP_HOST'], 'debug');
    switch ($_SERVER['HTTP_HOST']) {
      case 'asset.crasco.jp':
        $contact->site_name = '資産運用';
        break;
      case 'am.crasco.jp':
        $contact->site_name = '不動産';
        break;
      case 'pm.crasco.jp':
        $this->log('site: enter ' . $_SERVER['HTTP_HOST'], 'debug');
        $contact->site_name = '賃貸経営';
        break;
    }

    //check if logged in
    $isLogged = $this->request->session()->check('Auth.User');
    if ($isLogged) {
      $auth = $this->request->session()->read('Auth.User');
      $contact->name = $auth['name'];
      $contact->furigana = $auth['furigana'];
      $contact->email = $auth['mail'];
      $contact->zip1 = $auth['zip1'];
      $contact->zip2 = $auth['zip2'];
      $contact->pref_id = $auth['pref_id'];
      $contact->district = $auth['district'];
      $contact->sub_address = $auth['sub_address'];
      $contact->mansion = $auth['mansion'];
      $contact->phone_number = $auth['phone_number'];
      $contact->fax_number = $auth['fax_number'];
    }
    if ($this->request->is('post')) {
      $data = $this->request->data;
      $json = $this->getRecaptchaJson($data);

      if ($data['agree'] == 1) {
        $contact = $contactTable->patchEntity($contact, $data);
        if ($json['success'] && $contactTable->save($contact)) {
          $prefTable = TableRegistry::get('Pref');
          $prefName = $prefTable->get($contact->pref_id)->name;
          $mailParams = array();
          $mailParams['subject'] = $contact->subject;
          $mailParams['naiyou'] = $contact->naiyou;
          $mailParams['name'] = $contact->name;
          $mailParams['furigana'] = $contact->furigana;
          $mailParams['email'] = $contact->email;
          $mailParams['zip'] = $contact->zip1 . '-' . $contact->zip2;
          $mailParams['pref_id'] = $prefName;
          $mailParams['district'] = $contact->district;
          $mailParams['sub_address'] = $contact->sub_address;
          $mailParams['mansion'] = $contact->mansion;
          $mailParams['phone_number'] = $contact->phone_number;
          $mailParams['fax_number'] = $contact->fax_number;
          $this->sendNotifyMail('asset@crasco.jp', AUTO_SEND_MAIL_CONTACT, $mailParams);
          $this->set('title', 'お問い合わせ' . DS . ASSET_TITLE_SUFFIX);
          $this->set('keywords', DEFAULT_KEYWORDS);
          $this->set('description', DEFAULT_DESCRIPTION);
          $this->set('thumnail', DEFAULT_THUMNAIL);
          $this->render('finish');
        } else {
          $this->Flash->error_web(__('送信できませんでした。'));
        }
      }
    }
    $pref = $contactTable->Pref->find('list')->order(['id'])->toArray();
    $this->set('pref', $pref);
    $this->set(compact('contact'));
    $this->set('_serialize', ['contact']);
  }



  public function saveMagazineMail($email, $data)
  {
    $data['email'] = $email;
    $magazineMailListTable = $this->loadModel('magazineMailList');
    if ($magazineMailListTable->exists(['email' => $email])) {
      $magazineMailList = $magazineMailListTable->get($email);
      unset($data['email']);
      if (isset($data['magazine_mail']) && $data['magazine_mail'] != null) {
        $saki = implode(',', $data['magazine_mail']);
        $data['magazine_mail'] = $saki;
        if ($saki != $magazineMailList->magazine_mail) {
          $mailParams = array();
          $mailParams['email'] = $magazineMailList->email;
          $this->sendNotifyMail($magazineMailList->email, AUTO_SEND_MAIL_NEWSLETTER_KOUDOKU, $mailParams);
          $this->sendNotifyMail('asset@crasco.jp', AUTO_SEND_MAIL_NEWSLETTER_KOUDOKU, $mailParams);
        }
      }
      $magazineMailList = $magazineMailListTable->patchEntity($magazineMailList, $data);
      $magazineMailListTable->save($magazineMailList);
    } else if (isset($data['magazine_mail']) && $data['magazine_mail'] != null) {
      $magazineMailList = $magazineMailListTable->newEntity();
      $saki = implode(',', $data['magazine_mail']);
      $data['magazine_mail'] = $saki;
      $magazineMailList = $magazineMailListTable->patchEntity($magazineMailList, $data);
      $magazineMailListTable->save($magazineMailList);
      $mailParams = array();
      $mailParams['email'] = $magazineMailList->email;
      $this->sendNotifyMail($magazineMailList->email, AUTO_SEND_MAIL_NEWSLETTER_KOUDOKU, $mailParams);
      $this->sendNotifyMail('asset@crasco.jp', AUTO_SEND_MAIL_NEWSLETTER_KOUDOKU, $mailParams);
    }
  }
  /**
   * GoogleRecaptchaの結果を検証して、結果をjsonで返却する
   *
   * @param array $request
   * @return void
   */
  private function getRecaptchaJson($request = [])
  {
    // エラー判定
    if (!isset($request['g-recaptcha-response'])) {
      $request['g-recaptcha-response'] = '';
    }
    // シークレットキー
    if ($_SERVER['HTTP_HOST']=='asset.crasco.jp') {
      $secret_key = GOOGLE_RECAPTHA_SECRET_KEY;
    } elseif ($_SERVER['HTTP_HOST']=='am.crasco.jp') {
      $secret_key = AM_GOOGLE_RECAPTHA_SECRET_KEY;
    } else {
      $secret_key = '';
    }
    
    // エンドポイント
    $endpoint = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $request['g-recaptcha-response'];
    // 判定結果の取得
    $http = new Client();
    $response = $http->get($endpoint);
    $json = $response->json;
    $this->log('recaptcha-------------------------------', 'debug');
    $this->log($request['g-recaptcha-response'], 'debug');
    $this->log($response->json, 'debug');

    return $json;
  }
}
