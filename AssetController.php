<?php
namespace App\Controller\Asset;

use App\Controller\AppController;
use Cake\View\Helper;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Log\Log;

class AssetController extends AppController
{
  public $components = array('RequestHandler');
  public $layout = 'asset';
  public $helpers = [
    'Paginator' => ['templates' => 'paginator-templates']
  ];

  public function beforeFilter(Event $event)
  {
    $this->loadRanking();
    $this->set('ranking', $this->rankingArray);
    $this->set('sideBanner', $this->loadSideBanner());
    $this->set('loggedIn', $this->request->session()->check('Auth.User'));
  }

  /**
  * Initialization hook method.
  *
  * Use this method to add common initialization code like loading components.
  *
  * @return void
  */
  public function initialize()
  {
    //added request_path for nav
    if (isset($this->request['pass'][0]) && !is_null($this->request['pass'][0])) {
      $this->set('assetRequestParam', $this->request['pass'][0]);
    }else {
      $this->set('assetRequestParam', $this->request->params['controller']);
    }

    parent::initialize();
    $this->loadComponent('Auth', [
      'loginRedirect' => [
        'controller' => 'Asset/Users',
        'action' => 'edit'
      ],
      'loginAction' => [
        'controller' => 'Asset/Asset',
        'action' => 'login'
      ],
      'logoutRedirect' => [
        'controller' => 'Asset/TopPage',
        'action' => 'index',
      ],
      'authenticate' => [
        'Form' => [
          'userModel' => 'Users',
          'fields' => [
            'username' => 'mail',
            'password' => 'password'
          ]
        ]
      ],
    ]);

    // Allow the display action so our pages controller
    // continues to work.
    $this->Auth->allow(['login', 'logout', 'index', 'add', 'view', 'remindPassword', 'newPass']);
  }

  /*public function beforeRender(Event $event)
  {
  parent::beforeRender($event);
  $this->set('ranking', $this->loadRanking());
}*/

public function pagination($finder, $condition = null, $type = null, $categoryId = null)
{
  $finderOptions = [
    'customOffset' => 0,
  ];
  $page = $this->request->query('page');
  if (!is_null($page)) {
    $finderOptions['customOffset'] = (((int) $page) - 1) * PAGINATION_LIMIT;
  }
  if (!is_null($condition)) {
    $finderOptions['condition'] = $condition;
  }
  if (!is_null($type)) {
    $finderOptions['type'] = $type;
  }
  if (!is_null($categoryId)) {
    $finderOptions['categoryId'] = $categoryId;
  }
  Log::write('debug', 'ac offset: ' . $finderOptions['customOffset'] );
  $this->paginate = [
    'finder' => [
      $finder => $finderOptions
    ],
    'limit' => PAGINATION_LIMIT
  ];
}

public function login()
{
  // if logged in, redirect to mail magazine page
  if ($this->request->session()->check('Auth.User')) {
    return $this->redirect($this->Auth->redirectUrl());
  }
  $this->layout = 'asset_kiji';
  if ($this->request->is('post')) {
    $user = $this->Auth->identify();
    if ($user) {
      $this->Auth->setUser($user);
      return $this->redirect($this->Auth->redirectUrl());
    }
    $this->Flash->error_web('ログインIDかパスワードに誤りがあります。');
  }
  $this->set('title', 'ログイン' . DS .ASSET_TITLE_SUFFIX);
  $this->set('keywords', DEFAULT_KEYWORDS);
  $this->set('description', DEFAULT_DESCRIPTION);
  $this->set('thumnail', DEFAULT_THUMNAIL);
}

public function logout()
{
  return $this->redirect($this->Auth->logout());
}

public static function saveRanking($ip, $postId, $koukaiSaki)
{
  $rankingTable = TableRegistry::get('ranking');
  $rankingData = [
    'ip' => $ip,
    'access_date' => Time::now(),
    'post_id' => $postId,
    'koukai_saki' => $koukaiSaki
  ];
  $ranking = $rankingTable->newEntity($rankingData);
  $rankingTable->save($ranking);
}

public function loadRanking()
{
  //ランキングロード
  $columnMenuTable = TableRegistry::get('ColumnMenu');
  $rankingJikan = $this->loadModel('RankingSyuukei')->get(SHISANUNYOU_RANKING)->jikan;
  switch ($rankingJikan) {
    case RANKING_1WEEK:
    $compareDate = new \DateTime('1 week ago');
    break;
    case RANKING_1MONTH:
    $compareDate = new \DateTime('1 month ago');
    break;
    case RANKING_3MONTHS:
    $compareDate = new \DateTime('3 months ago');
    break;
  }
  $rankingQuery = $this->loadModel('Ranking')->find();
  $rankingQuery->select(['post_id', 'count' => $rankingQuery->func()->count('*')])
  ->where(['access_date >=' => $compareDate, 'koukai_saki LIKE' => '%' . ASSET . '%'])
  ->group(['post_id'])
  ->order(['count' => 'DESC'])
  ->limit(RANKING_LIMIT);
  $this->rankingArray = $rankingQuery->toArray();
  $rankNumber = 0;
  foreach ($this->rankingArray as $key => $value) {
    $this->log('aray key '.$key.' --value: '.$value['count'], 'debug');
    //ランキング順番
    $value['rank_number'] = ++$rankNumber;
    $this->log('aray key '.$key.' --ranking post_id: '.$value['post_id'], 'debug');
    //echo $value['post_id']."<br/>";
    $post = $columnMenuTable->get($value['post_id'], ['contain' => 'ColumnMenuCategory']);
    
    $value['post'] = $post;
    
  }
  return $this->rankingArray;
}

public function loadCategoryData($post, $koteiPost, $categoryId = '')
{
  $condi = '';
  if ($categoryId != '') {
    $condi = 'AND column_menu_category_id = ' . $categoryId;
  }
  //category page fixed position sql sentence
  $ichiOrderSql = sprintf('(SELECT id, pos1
  FROM (
  SELECT @pos2 := @pos2 +1 pos2, pos1
  FROM (
  SELECT @pos1 := @pos1 + 1 pos1
  FROM column_menu
  JOIN (SELECT @pos1 := 0) r
  ) p1
  JOIN (SELECT @pos2 := 0) r
  WHERE p1.pos1 NOT IN (
  SELECT category_ichi
  FROM column_menu
  WHERE category_ichi > 0 %s
  )) p2,
  (
  SELECT @pos3 := @pos3 +1 pos3, id
  FROM (
  SELECT toukou_nichiji, id
  FROM column_menu
  WHERE category_ichi = 0 %s
  ORDER BY toukou_nichiji DESC
  ) p
  JOIN (SELECT @pos3 := 0) r
  ) p3
  WHERE p2.pos2 = p3.pos3)', $condi, $condi);

  $postSelectArray = array('pos1' => 'pos.pos1', 'title', 'id', 'honbun', 'type',
  'column_menu_category_id', 'thumbnail_url', 'koukai_saki', 'koukai_kaishi', 'koukai_kanryou', 'keyword',
  'description', 'canonical', 'size', 'top_ichi', 'category_ichi', 'toukou_nichiji', 'ts');

  $post->select($postSelectArray)
  ->join(['table' => $ichiOrderSql, 'alias' => 'pos',
  'type' => 'INNER', 'conditions' => 'pos.id = ColumnMenu.id']);

  $koteiPostSelectArray = array('pos1' => 'category_ichi', 'title', 'id', 'honbun', 'type',
  'column_menu_category_id', 'thumbnail_url', 'koukai_saki', 'koukai_kaishi', 'koukai_kanryou', 'keyword',
  'description', 'canonical', 'size', 'top_ichi', 'category_ichi', 'toukou_nichiji', 'ts');

  $koteiPost->select($koteiPostSelectArray);

  return $post->union($koteiPost)->epilog('ORDER BY pos1 ASC');

}

public function loadSideBanner()
{
  $bannerTable = TableRegistry::get('Banner');
  $sideBanner = $bannerTable->find('all', [
    'conditions' => [
      'image_pc IS NOT' => NULL,
      'koukai' => 1,
      'koukai_saki LIKE' => '%' . ASSET . '%',
      'banner_type' => ASSET_SIDE_BANNER,
      'koukai_kaishi <=' => new \DateTime('now'),
      'OR' => [['koukai_kanryou IS' => NULL],
      ['koukai_kanryou' => new \DateTime('0000-00-00 00:00:00')],
      ['koukai_kanryou >=' => new \DateTime('now')]]
    ],
    'order' => ['ts' => 'DESC'],
    'limit' => 1])->first();
    return $sideBanner;
  }

  public function loadContentBanner()
  {
    $bannerTable = TableRegistry::get('Banner');
    $contentBanner = $bannerTable->find('all', [
      'conditions' => [
        'image_pc IS NOT' => NULL,
        'koukai' => 1,
        'koukai_saki LIKE' => '%' . ASSET . '%',
        'banner_type' => ASSET_CONTENT_BANNER,
        'koukai_kaishi <=' => new \DateTime('now'),
        'OR' => [['koukai_kanryou IS' => NULL],
        ['koukai_kanryou' => new \DateTime('0000-00-00 00:00:00')],
        ['koukai_kanryou >=' => new \DateTime('now')]]
      ],
      'order' => ['ts' => 'DESC'],
      'limit' => 1])->first();
      return $contentBanner;
    }

  }
