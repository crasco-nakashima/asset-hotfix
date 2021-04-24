<?php
namespace App\Controller\Asset;


/**
* Contact Controller
*
* @property \App\Model\Table\ContactTable $Contact
*/
class ContactController extends AssetController
{

  /**
  * Index method
  *
  * @return void
  */
  public function index()
  {
    $this->set('contact', $this->paginate($this->Contact));
    $this->set('_serialize', ['contact']);
  }

  /**
  * View method
  *
  * @param string|null $id Contact id.
  * @return void
  * @throws \Cake\Network\Exception\NotFoundException When record not found.
  */
  public function view($id = null)
  {
    $contact = $this->Contact->get($id, [
      'contain' => []
    ]);
    $this->set('contact', $contact);
    $this->set('_serialize', ['contact']);
  }

  /**
  * Add method
  *
  * @return void Redirects on successful add, renders view otherwise.
  */
  public function add($subject = null)
  {
    switch ($subject) {
      case '個別相談依頼':
      $this->set('h2', '個別相談依頼');
      break;
      case '資料請求':
      $this->set('h2', '資料請求');
      break;
      case '賃貸経営相談':
      $this->set('h2', '賃貸経営相談');
      break;
      default:
      $this->set('h2', 'お問い合わせ');
      if (!is_null($subject)) {
        $subject = '「' . $subject . '」に関するお問い合わせ';
      }
      break;
    }

    $this->layout = 'asset_kiji';
    $this->set('title', 'お問い合わせ' . DS . ASSET_TITLE_SUFFIX);
    $this->set('keywords', DEFAULT_KEYWORDS);
    $this->set('description', DEFAULT_DESCRIPTION);
    $this->set('thumnail', DEFAULT_THUMNAIL);
    parent::addContact($subject);
  }

  /**
  * Edit method
  *
  * @param string|null $id Contact id.
  * @return void Redirects on successful edit, renders view otherwise.
  * @throws \Cake\Network\Exception\NotFoundException When record not found.
  */
  public function edit($id = null)
  {
    $contact = $this->Contact->get($id, [
      'contain' => []
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $contact = $this->Contact->patchEntity($contact, $this->request->data);
      if ($this->Contact->save($contact)) {
        $this->Flash->success(__('The contact has been saved.'));
        return $this->redirect(['action' => 'index']);
      } else {
        $this->Flash->error(__('The contact could not be saved. Please, try again.'));
      }
    }
    $this->set(compact('contact'));
    $this->set('_serialize', ['contact']);
  }

  /**
  * Delete method
  *
  * @param string|null $id Contact id.
  * @return void Redirects to index.
  * @throws \Cake\Network\Exception\NotFoundException When record not found.
  */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $contact = $this->Contact->get($id);
    if ($this->Contact->delete($contact)) {
      $this->Flash->success(__('The contact has been deleted.'));
    } else {
      $this->Flash->error(__('The contact could not be deleted. Please, try again.'));
    }
    return $this->redirect(['action' => 'index']);
  }
}
