<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//itemテーブルの在庫の変更とcartテーブルの削除と履歴の追加
function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  $db->beginTransaction();
  try{
  foreach($carts as $cart){
    //在庫の変更
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  //historyテーブルに挿入
  insert_history($db, $carts[0]['user_id']);

  $order_id = $db->lastInsertId();

  //detailsテーブルに挿入
  foreach($carts as $cart){
    insert_details($db, $order_id, $cart['item_id'], $cart['amount'], $cart['price']);
  }

  //cartsテーブルの削除
  delete_user_carts($db, $carts[0]['user_id']);
  $db->commit();
  } catch(PDOException $e) {
  $db->rollback();
  throw $e;
  }
}

function insert_history($db, $user_id){
$sql = "
    INSERT INTO
      history(user_id)
    VALUES (?);
  ";

  execute_query($db, $sql, array($user_id));
}

function insert_details($db, $order_id, $item_id, $amount, $price) {
$sql = "
    INSERT INTO
      details(order_id, item_id, amount, price)
    VALUES (?,?,?,?);
  ";

  execute_query($db, $sql, array($order_id, $item_id, $amount, $price));
}

function get_history($db, $user_id){
  $sql = "
    SELECT
      history.order_id,
      history.created,
      SUM(details.price * details.amount)
    FROM
      history
    JOIN
      details
    ON
      history.order_id = details.order_id
    WHERE
      history.user_id = ?
    GROUP BY
      history.order_id          
  ";

  return fetch_query($db, $sql, array($user_id));
}

function get_details($db, $user_id, $order_id){
    $sql = "
      SELECT
        items.name,
        details.price,
        details.amount,
        details.price * details.amount
      FROM
        details
      JOIN
        items
      ON
        details.item_id = items.item_id
      WHERE
        details.order_id =?        
    ";
  
    return fetch_query($db, $sql, array($user_id, $order_id));
  }