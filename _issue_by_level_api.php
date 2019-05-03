<?php
include __DIR__.'./_connectDB.php';
header('Content-Type: application/json');
$result = [
    'success' => false,
    'data' => [],
    'errorCode' => 0,
    'errorMsg' => '',
    'post'=>[],
    'issue_member_count'=>0,
    'available_coupon_count'=>0,
    'issue_count'=>0
];

$issue_count = 0;

if(isset($_POST['issue_level'])){
  $issue_level = $_POST['issue_level'];

  $result['post'] = $_POST;

  $c_count_sql = "SELECT count(1) FROM coupon WHERE user_id=0";
  $c_count_stmt = $pdo->query($c_count_sql);
  $c_count_row = $c_count_stmt->fetch(PDO::FETCH_NUM)[0];

  $m_count_sql = "SELECT count(1) FROM member_list WHERE memLevel_id={$issue_level}";
  $m_count_stmt = $pdo->query($m_count_sql);
  $m_count_row = $m_count_stmt->fetch(PDO::FETCH_NUM)[0];

  $result['issue_member_count'] = $m_count_row;
  $result['available_coupon_count'] = $c_count_row;

  if($c_count_row<$m_count_row){
    $result['errorMsg']= '未配發coupon不足';
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
  }else{
    $m_sql="SELECT * FROM member_list";
    $m_stmt = $pdo->query($m_sql);
    $m_rows = $m_stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach($m_rows as $m_row) {
      if($m_row['memLevel_id']==$issue_level) {
        $sql = "UPDATE  coupon set user_id=? where user_id='0' LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          $m_row['mem_id']
        ]);
        if($stmt->rowCount()==1) {
          $issue_count++;
        }
      }
    }
  }
  $result['issue_count'] = $issue_count;
  $result['success'] =true;
}






echo json_encode($result,JSON_UNESCAPED_UNICODE);