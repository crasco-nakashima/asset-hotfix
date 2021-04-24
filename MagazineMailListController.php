<?php
namespace App\Controller\Asset;

use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
* MagazineMailList Controller
*
* @property \App\Model\Table\MagazineMailListTable $MagazineMailList
*/
class MagazineMailListController extends AssetController
{

  public function initialize()
  {
    parent::initialize();
    $this->Auth->allow(['touroku', 'taikai']);
  }

  /**
  * Index method
  *
  * @return void
  */
  public function index()
  {
    $this->set('magazineMailList', $this->paginate($this->MagazineMailList));
    $this->set('_serialize', ['magazineMailList']);
  }

  public function touroku()
  {
    if (isset($this->request->data['taikai_action'])) {
      $this->taikai();
    }
    else {
      $this->layout = 'asset_kiji';
      $this->set('title', 'メールマガジン' . DS . ASSET_TITLE_SUFFIX);
      $this->set('keywords', DEFAULT_KEYWORDS);
      $this->set('description', DEFAULT_DESCRIPTION);
      $this->set('thumnail', DEFAULT_THUMNAIL);
      parent::registerNewsletter();
      $pref = TableRegistry::get('Pref')->find('list')->order(['id'])->toArray();
      $this->set('pref', $pref);
    }
  }

  public function taikai()
  {
    $this->layout = 'asset_kiji';
    $this->set('title', 'メールマガジン' . DS . ASSET_TITLE_SUFFIX);
    $this->set('keywords', DEFAULT_KEYWORDS);
    $this->set('description', DEFAULT_DESCRIPTION);
    $this->set('thumnail', DEFAULT_THUMNAIL);
    parent::taikaiNewsletter();
  }

  /**
  * View method
  *
  * @param string|null $id Magazine Mail List id.
  * @return void
  * @throws \Cake\Network\Exception\NotFoundException When record not found.
  */
  public function view($id = null)
  {
    $magazineMailList = $this->MagazineMailList->get($id, [
      'contain' => []
    ]);
    $this->set('magazineMailList', $magazineMailList);
    $this->set('_serialize', ['magazineMailList']);
  }

  /**
  * Add method
  *
  * @return void Redirects on successful add, renders view otherwise.
  */
  public function add()
  {
    $magazineMailList = $this->MagazineMailList->newEntity();
    if ($this->request->is('post')) {
      $magazineMailList = $this->MagazineMailList->patchEntity($magazineMailList, $this->request->data);
      if ($this->MagazineMailList->save($magazineMailList)) {
        $this->Flash->success(__('The magazine mail list has been saved.'));
        return $this->redirect(['action' => 'index']);
      } else {
        $this->Flash->error(__('The magazine mail list could not be saved. Please, try again.'));
      }
    }
    $this->set(compact('magazineMailList'));
    $this->set('_serialize', ['magazineMailList']);
  }

  /**
  * Edit method
  *
  * @param string|null $id Magazine Mail List id.
  * @return void Redirects on successful edit, renders view otherwise.
  * @throws \Cake\Network\Exception\NotFoundException When record not found.
  */
  public function edit($id = null)
  {
    $magazineMailList = $this->MagazineMailList->get($id, [
      'contain' => []
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $magazineMailList = $this->MagazineMailList->patchEntity($magazineMailList, $this->request->data);
      if ($this->MagazineMailList->save($magazineMailList)) {
        $this->Flash->success(__('The magazine mail list has been saved.'));
        return $this->redirect(['action' => 'index']);
      } else {
        $this->Flash->error(__('The magazine mail list could not be saved. Please, try again.'));
      }
    }
    $this->set(compact('magazineMailList'));
    $this->set('_serialize', ['magazineMailList']);
  }

  /**
  * Delete method
  *
  * @param string|null $id Magazine Mail List id.
  * @return void Redirects to index.
  * @throws \Cake\Network\Exception\NotFoundException When record not found.
  */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $magazineMailList = $this->MagazineMailList->get($id);
    if ($this->MagazineMailList->delete($magazineMailList)) {
      $this->Flash->success(__('The magazine mail list has been deleted.'));
    } else {
      $this->Flash->error(__('The magazine mail list could not be deleted. Please, try again.'));
    }
    return $this->redirect(['action' => 'index']);
  }
}
