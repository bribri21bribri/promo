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
  $planType = $_POST['planType'];
  $condi_col="";
  if ($planType=='user_plan') {
    $condi_col = 'user_condi';
  } elseif ($planType == 'price_plan') {
    $condi_col = 'price_condi';
  } elseif ($planType == 'prod_plan') {
    $condi_col = 'prod_condi';
  } elseif ($planType == 'amount_plan') {
    $condi_col = 'amount_condi';
  }




  $name = $_POST['name'];
  $condi = $_POST['condi'];


  $dis_num = $_POST['dis_num'];
  $dis_type = $_POST['dis_type'];
  $start = $_POST['start'];
  $end = $_POST['end'];

  $result['post'] = $_POST;  // 做 echo 檢查

  // check every field isn't empty
  if (empty($planType) or empty($name) or empty($condi) or empty($dis_num) or empty($dis_type) or empty($start) or empty($end)) {
    $result['errorCode'] = 400;
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
  }

  // TODO: 檢查



  $sql = "INSERT INTO `{$_POST['planType']}` (`name`,`{$condi_col}`,`dis_num`,`dis_type`,`start`,`end`) VALUE (?,?,?,?,?,?)";


  try {
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $name,
        $condi,
        $dis_num,
        $dis_type,
        $start,
        $end
    ]);

    if ($stmt->rowCount() == 1) {
      $result['success'] = true;
      $result['errorCode'] = 200;
      $result['errorMsg'] = '';
    } else {
      $result['errorCode'] = 402;
      $result['errorMsg'] = '資料新增錯誤';
    }
  } catch (PDOException $ex) {
    $result['errorCode'] = 403;
    $result['errorMsg'] = "方案已存在";
//    $result['errorMsg'] = $ex->getMessage();
  }
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
