<?php
include __DIR__ . '/_connectDB.php';

function get_all_plan($pdo, $promo_table)
{
    $sql = "SELECT * FROM $promo_table";

    // return $sql;

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $stmt->rowCount();
}