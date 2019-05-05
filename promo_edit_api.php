<?php
require __DIR__ . '/_connectDB.php';

header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '資料輸入不完整',
    'post' => [], // 做 echo 檢查

];


if (isset($_POST['planType'])) {
  $planType = htmlentities($_POST['planType']);
  $id = htmlentities($_POST['id']);
  $name = htmlentities($_POST['name']);
  $condi = htmlentities($_POST['condi']);
  $dis_num = htmlentities($_POST['dis_num']);
  $dis_type = htmlentities($_POST['dis_type']);
  $start = htmlentities($_POST['start']);
  $end = htmlentities($_POST['end']);
//TODO: check if any input is empty




  if ($planType=='user_plan') {
    $condi_col = 'user_condi';
  } elseif ($planType == 'price_plan') {
    $condi_col = 'price_condi';
  } elseif ($planType == 'prod_plan') {
    $condi_col = 'prod_condi';
  } elseif ($planType == 'amount_plan') {
    $condi_col = 'amount_condi';
  }



  $result['post'] = $_POST;  // 做 echo 檢查



  // TODO: 檢查



  $sql = "UPDATE `{$planType}` SET
              `name`=?, 
              {$condi_col}=?, 
              `dis_num`=?,  
              `dis_type`=?,
              `start`=?,
              `end`=?
              WHERE `id`=?";


  try {
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $name,
        $condi,
        $dis_num,
        $dis_type,
        $start,
        $end,
        $id
    ]);

    if ($stmt->rowCount() == 1) {
      $result['success'] = true;
      $result['errorCode'] = 200;
      $result['errorMsg'] = '';
    } else {
      $result['errorCode'] = 402;
      $result['errorMsg'] = '資料須為數值';
    }
  } catch (PDOException $ex) {
    $result['errorCode'] = 403;
    $result['errorMsg'] = $ex->getMessage();
  }
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
