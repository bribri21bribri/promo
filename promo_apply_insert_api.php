<?php
include __DIR__ . '/_connectDB.php';

header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '資料輸入不完整',
    'post' => [], // 做 echo 檢查

];

if (isset($_POST['promo_type'])) {
    $promo_type = htmlentities($_POST['promo_type']);
    $camp_id = htmlentities($_POST['camp_id']);

    $result['post'] = $_POST;
    if (empty($promo_type) or empty($camp_id)) {
        $result['errorCode'] = 400;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $promo_apply_sql = 'SELECT * FROM promo_apply';
    $stmt = $pdo->query($promo_apply_sql);
    $promo_apply_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($promo_apply_rows as $promo_apply) {
        if ($promo_apply['camp_id'] === $camp_id) {

            $result['errorMsg'] = '該營地目前已有參加其他優惠活動';
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    //TODO: Validation

    //
    //generate coupon code
    //

    $sql = "INSERT INTO `promo_apply` (`promo_type`,`camp_id`) VALUES (?,?) ";

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $promo_type,
            $camp_id,
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
        $result['errorMsg'] = $ex->getMessage();
    }

}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
