<?php
define('ASSET', 1);
define('AM', 2);
define('PM', 3);
define('SHISANUNYOU_RANKING', 2);
define('AM_RANKING', 1);
define('RANKING_1WEEK', 1);
define('RANKING_1MONTH', 2);
define('RANKING_3MONTHS', 3);
define('NEW_INFO', 1);
define('SEMINAR', 2);
define('COLUMN', 3);
define('ACCESS_COLUMN', 1);
define('ACCESS_KOTEI_PAGE', 2);
define('NEW_INFO_CATEGORY', '新着情報');
define('SEMINAR_CATEGORY', 'セミナー・イベント');

define('ADMIN_KENGEN', 1);
define('CONTENT_KENGEN', 2);
define('NORMAL_KENGEN', 3);

define('EXPORT_1WEEK', 1);
define('EXPORT_1MONTH', 2);
define('EXPORT_3MONTHS', 3);
define('EXPORT_ALL_TIME', 4);

define('AUTO_SEND_MAIL_USER_TOUROKU', 1);
define('AUTO_SEND_MAIL_USER_TAIKAI', 2);
define('AUTO_SEND_MAIL_PASSWORD_REMINDER', 3);
define('AUTO_SEND_MAIL_CONTACT', 4);
define('AUTO_SEND_MAIL_NEWSLETTER_KOUDOKU', 5);
define('AUTO_SEND_MAIL_NEWSLETTER_KAIJO', 6);
define('AUTO_SEND_MAIL_BUKKEN_REQUEST', 7);

define('PAGINATION_LIMIT', 20);
define('PM_PAGINATION_LIMIT', 10);
define('RANKING_LIMIT', 10);
define('AM_RANKING_LIMIT', 5);
define('AM_PAGINATION_LIMIT', 20);
define('AM_FRONTEND_PAGINATION_LIMIT', 20);

define('ASSET_LAYOUT', 'default');
define('ADMIN_NEW_INFO', 1);
define('ADMIN_SEMINAR', 2);
define('ADMIN_COLUMN', 3);
define('ADMIN_COLUMN_MENU', 4);
define('ADMIN_COLUMN_MENU_CATEGORY', 4);
define('ADMIN_KOTEI_PAGE', 5);
define('ADMIN_KOTEI_PAGE_CATEGORY', 6);
define('ADMIN_RANKING_SYUUKEI', 7);
define('ADMIN_CONTACT', 8);
define('ADMIN_MAILMAGAZINE_MAIL', 9);
define('ADMIN_MAIL_TRANSPORT', 10);
define('ADMIN_MAGAZINE_MAIL_LIST', 11);
define('ADMIN_AUTO_SEND_MAIL', 12);
define('ADMIN_BANNER', 13);
define('ADMIN_BUKKEN', 14);
define('ADMIN_STAFF', 15);
define('ADMIN_USERS', 16);
define('ADMIN_C_KANRISHA', 17);
define('ADMIN_U_KANRISHA', 18);

//byteとして
define('UPLOAD_SIZE_LIMIT', 3000000);

//top page display size
define('SMALL', 0);
define('LARGE', 1);

//asset request params
define('ASSET_TOPPAGE_PARAM', 'Asset/TopPage');
define('ASSET_NEW_INFO_PARAM', 'Asset/NewInfo');
define('ASSET_SEMINAR_PARAM', 'Asset/Seminar');
define('ASSET_SHUEKI_PARAM', 'shuekibukken');
define('ASSET_SOUZO_PARAM', 'souzokutaisaku');
define('ASSET_KUSHI_PARAM', 'kushitsutaisaku');
define('ASSET_TOCHI_PARAM', 'tochi-katsuyo');
define('ASSET_ZENKIN_PARAM', 'zeikintaisaku');
define('ASSET_JIREI_PARAM', 'jireishoukai');

//asset member only tag
define('MEMBER_ONLY_TAG', '&lt;%%login%%&gt;');

//am request params
define('AM_TOPPAGE_PARAM', 'Am/TopPage');
define('AM_SEMINAR_PARAM', 'Am/Seminar');
define('AM_STAFF_PARAM', 'Am/Staff');
define('AM_CONTACT_PARAM', 'Am/Contact');
define('AM_BUKKEN_PARAM', 'Am/Bukken');
define('AM_FAQ_PARAM', 'Am/Faq');
define('AM_OUTLINE_PARAM', 'outline');

//asset suffix title
define('ASSET_TITLE_SUFFIX', 'クラスコの資産運用');
//pm suffix title
define('HENSYUUKIJI_TITTLE', '編集記事');
define('PM_TITLE_SUFFIX', 'クラスコの賃貸経営');
//am suffix title
define('AM_TITLE_SUFFIX', 'クラスコの不動産投資');
//meta
define('DEFAULT_KEYWORDS', '資産,運用,賃貸,売買,不動産,マンション');
define('DEFAULT_DESCRIPTION', '株式会社クラスコは、物件の賃貸・売買やリノベーションなどを主体とした不動産事業を展開している、総資産管理会社です。「住まいと暮らしのアイデア創造企業」として、新たな不動産ニーズに応える取り組みを行っています。');
define('DEFAULT_THUMNAIL', 'https://asset.crasco.jp/img/asset/logo.png');

//chintai column category
define('HENSYUUKIJI', 11);

//banner type
define('LINK_BANNER', 0);
define('TOP_BANNER', 1);
define('ASSET_CONTENT_BANNER', 2);
define('ASSET_SIDE_BANNER', 3);

// facebook
define('FB_APP_ID', '223258238144789');
define('FB_APP_SECRET', '66d59e7af91ccee17249e26c6539f71b');
//define('FB_CALLBACK', 'http://asset.crasco.dev/admin/test-fb/fbcallback');
define('FB_CALLBACK', 'https://asset.crasco.jp/admin/test-fb/fbcallback');
//define('FB_PAGES', '319464905067578,183090058421180');
define('FB_PAGES', '630529703653857');

// url for dev
//define('ASSET_URL', 'http://asset.crasco.dev');
//define('AM_URL', 'http://am.crasco.dev');

// url for staging
// define('ASSET_URL', 'http://test.asset.crasco.jp');
// define('AM_URL', 'http://test.am.crasco.jp');

// url for production
define('ASSET_URL', 'https://asset.crasco.jp');
define('AM_URL', 'https://am.crasco.jp'); 

define('GOOGLE_RECAPTHA_SITE_KEY', '6Le-z6saAAAAAGd8AZ1Po-XwxgpZSiz_46H8HRVc'); 
define('GOOGLE_RECAPTHA_SECRET_KEY', '6Le-z6saAAAAAEfhAoWSO_cywNLbBxrcePjsVpAc'); 

define('AM_GOOGLE_RECAPTHA_SITE_KEY', '6LeD2LkaAAAAANMp-eeKWbtUKiea26qA9wr8ZzFA');
define('AM_GOOGLE_RECAPTHA_SECRET_KEY', '6LeD2LkaAAAAAH5gzFyMpM8-x3GrSaGBdvP8qMAt');