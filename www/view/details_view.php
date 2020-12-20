<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  
  <div class="container">
    <h1>購入明細</h1>
    <h4>購入履歴</h4>

    <?php if(count($details) > 0){ ?>
    <table class="table table-bordered text-center">
      <thead class="thead-light">
        <tr>
          <th>注文番号</th>
          <th>購入日時</th>
          <th>合計金額</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php print($details[0]['order_id']); ?></td>
          <td><?php print($details[0]['created']); ?></td>
          <td><?php print(number_format($total_price)); ?>円</td>
        </tr>  
      </tbody>
    </table>

    <h4>購入明細</h4>
      <table class="table table-bordered text-center">
      <thead class="thead-light">
        <tr>
        <th>商品名</th>
        <th>商品価格</th>
        <th>購入数</th>
        <th>小計</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($details as $detail){ ?>
          <tr>
          <td><?php print($detail['name']); ?></td>
          <td><?php print(number_format($detail['price'])); ?>円</td>
          <td><?php print($detail['amount']); ?></td>
          <td><?php print(number_format($detail['subtotal'])); ?>円</td>
          </tr>  
          <?php } ?>
      </tbody>
      </table>
    <?php } else { ?>
      <p>購入明細はありません。</p>
    <?php } ?>
  </div>
</body>
</html>