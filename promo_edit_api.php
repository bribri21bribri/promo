<?php
require __DIR__ . '/_connectDB.php';

header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '資料輸入不完整',
    'post' => [], // 做 echo 檢查

];

if (isset($_POST['promo_table'])) {
    $promo_table = htmlentities($_POST['promo_table']);
    $promo_id = htmlentities($_POST['promo_id']);
    $promo_name = htmlentities($_POST['promo_name']);
    $requirement = htmlentities($_POST['requirement']);
    $discount_unit = htmlentities($_POST['discount_unit']);
    $discount_type = htmlentities($_POST['discount_type']);
    $start = htmlentities($_POST['start']);
    $end = htmlentities($_POST['end']);
    $discription = htmlentities($_POST['discription']);

    $result['post'] = $_POST;
    if (empty($promo_table) or empty($promo_name) or empty($requirement) or empty($discount_unit) or empty($discount_type) or empty($start) or empty($end) or empty($discription)) {
        $result['errorCode'] = 400;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $result['post'] = $_POST; // 做 echo 檢查

    // TODO: 檢查

    $sql = "UPDATE `{$promo_table}` SET
              `promo_name`=?,
              `requirement`=?,
              `discount_unit`=?,
              `discount_type`=?,
              `start`=?,
              `end`=?,
              `discription`=?
              WHERE `promo_id`=?";

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $promo_name,
            $requirement,
            $discount_unit,
            $discount_type,
            $start,
            $end,
            $discription,
            $promo_id,
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
