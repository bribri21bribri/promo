<?php
include __DIR__ . '/_connectDB.php';
header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '資料輸入不完整',
    'post' => [], // 做 echo 檢查

];

if (isset($_POST['promo_table'])) {
    $promo_table = htmlentities($_POST['promo_table']);
    $promo_name = htmlentities($_POST['promo_name']);
    $requirement = htmlentities($_POST['requirement']);
    $discount_unit = htmlentities($_POST['discount_unit']);
    $discount_type = htmlentities($_POST['discount_type']);
    $start = htmlentities($_POST['start']);
    $end = htmlentities($_POST['end']);
    $discription = htmlentities($_POST['discription']);

    if (empty($promo_table) or empty($promo_name) or empty($requirement) or empty($discount_unit) or empty($discount_type) or empty($start) or empty($end) or empty($discription)) {
        $result['errorCode'] = 400;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $promo_sql = 'SELECT * FROM ' . $promo_table;
    $stmt = $pdo->query($promo_sql);
    $promo_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($promo_rows as $promo_row) {
        if ($promo_row['requirement'] === $requirement) {

            $result['errorMsg'] = '輸入之優惠條件已存在，請直接對已存在之優惠方案進行修改，或輸入其他優惠條件';
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    //TODO: Validation

    $sql = "INSERT INTO {$promo_table} ( `promo_name`, `requirement`, `discount_unit`, `discount_type`, `start`, `end`, `discription`) VALUES (?,?,?,?,?,?,?) ";

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
        ]);

        if ($stmt->rowCount() === 1) {
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