<?php
require __DIR__ . '/_connectDB.php';

header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '刪除失敗',
    'post' => [], // 做 echo 檢查

];
try {
  if (isset($_POST['planType'])) {
    $planType = $_POST['planType'];
    $id = $_POST['id'];
    $sql = "DELETE FROM {$planType} WHERE `id`=$id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result['success'] = true;
  } else {
    $result['errorMsg'] = '刪除失敗';
  }
} catch (PDOException $ex) {
  $result['errorMsg'] = $ex->getMessage();
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);