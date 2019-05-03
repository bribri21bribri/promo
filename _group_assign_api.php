<?php
require __DIR__ . '/_connectDB.php';

header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '指定失敗',
    'post' => [], // 做 echo 檢查
    'row_assign' => 0
];
try {
  if (isset($_POST['assign_coupons'])) {
    $assign_coupons = json_decode($_POST['assign_coupons']);
    $user_id = $_POST['user_id'];
    $row_count = 0;
    foreach ($assign_coupons as $coupon_to_assign) {
      $sql = "UPDATE `coupon` SET
              `user_id`=? WHERE `coupon_id`=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
          $user_id,
        $coupon_to_assign
      ]);
      if($stmt->rowCount()==1){
        $row_count++;
      }
    }



  }
  if ($row_count == count($assign_coupons)) {
    $result['errorMsg'] = '指派成功';
    $result['success'] = true;
    $result['row_assign'] = $row_count;
  }
} catch (PDOException $ex) {
  $result['errorMsg'] = $ex->getMessage();
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);