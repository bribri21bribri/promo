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
    if (isset($_POST['promo_table'])) {
        $promo_table = $_POST['promo_table'];
        $promo_id = $_POST['promo_id'];
        $sql = "DELETE FROM {$promo_table} WHERE `promo_id`=$promo_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result['success'] = true;
        $result['errorMsg'] = '';
    } else {
        $result['errorMsg'] = '刪除失敗';
    }
} catch (PDOException $ex) {
    $result['errorMsg'] = $ex->getMessage();
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);