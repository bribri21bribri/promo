<?php
include __DIR__ . '/_connectDB.php';

function get_all_promo_apply($pdo, $promo_type_condition = "")
{
    $sql = "SELECT * FROM `promo_apply`" . " " . $promo_type_condition;

    // return $sql;

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $stmt->rowCount();
}