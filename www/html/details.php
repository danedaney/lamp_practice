<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$order_id = get_post('order_id');

if(is_admin($user) === true){
  $details = get_all_details($db, $order_id);
  } else {
  $details = get_details($db, $user['user_id'], $order_id);
}

//cartsのHTMLエスケープ処理
$details = entity_assoc_array($details);

$total_price = sum_history($details);


include_once VIEW_PATH . 'details_view.php';