<?php
require __DIR__. '/_connectDB.php';
header('Content-Type: application/json');

$result = [
    'success' => false,
    'data' => [],
    'errorCode' => 0,
    'errorMsg' => '',
];


if(isset($_POST['planType'])) {
  $planType = $_POST['planType'];
  $id = $_POST['id'];
  $sql = "SELECT * FROM {$planType} WHERE id={$id}";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $rows= $stmt->fetchAll(PDO::FETCH_ASSOC);










// 所有資料一次拿出來
  $result['data'] = $rows;
  $result['success'] = true;
}
// 將陣列轉換成 json 字串
echo json_encode($result, JSON_UNESCAPED_UNICODE);

