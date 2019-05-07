<?php
require __DIR__ . '/_connectDB.php';
try {
    $campsite_sql = 'SELECT  * FROM campsite_list';
    $stmt = $pdo->query($campsite_sql);
    $campsite_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}
try {
    $coupon_sql = "SELECT * FROM coupon_genre";
    $coupon_stmt = $pdo->query($coupon_sql);
    $coupon_rows = $coupon_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

try {
    $member_sql = "SELECT * FROM member_list";
    $member_stmt = $pdo->query($member_sql);
    $member_rows = $member_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

$promo_apply_id = isset($_GET['promo_apply_id']) ? intval($_GET['promo_apply_id']) : 0;
$sql = "SELECT * FROM promo_apply WHERE promo_apply_id=$promo_apply_id";

$stmt = $pdo->query($sql);

// if ($stmt->rowCount() == 0) {
//     header('Location: _list.php');
//     exit;
// }

$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . './_header.php';
include __DIR__ . './_navbar.php';
?>
<main class="col-10 bg-white">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./promo_apply_list.php">優惠活動參加紀錄查詢</a></li>
      <li class="breadcrumb-item active">優惠活動參加紀錄編輯</li>
    </ol>
  </nav>
  <section class="" style="height: 100%;">

    <!-- submit result message -->
    <div id="info_bar" style="display: none" class="alert alert-success"></div>
    <div class="container">
      <div class="card-body">

        <div class="row d-flex justify-content-center">
          <div class="col-sm-8">
            <h5 class="card-title text-center">優惠活動參加紀錄編輯</h5>
          </div>
        </div>
        <form method="POST" name="promo_apply_insert_form" onsubmit="return sendForm()">

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right">營地</label>
            <div class="col-6">
              <input type="text" class="form-control" readonly value="<?=$row['camp_id']?>">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠類別</label>
            <div class="col-6">
              <select class="form-control" name="promo_type" id="promo_type">
                <option <?=$row['promo_type'] == "promo_user" ? "selected" : "";?> value="promo_user">會員優惠
                </option>
                <option <?=$row['promo_type'] == "promo_campType" ? "selected" : "";?> value="promo_campType">營地分類優惠
                </option>
                <option <?=$row['promo_type'] == "promo_price" ? "selected" : "";?> value="promo_price">滿額優惠</option>
              </select>
            </div>
          </div>



          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>啟用狀態</label>
            <div class="col-6">
              <select class="form-control" name="apply_valid" id="apply_valid">
                <option value="1">啟用</option>
                <option value="2">關閉</option>
              </select>
            </div>
          </div>


          <div class="form-group justify-content-center row  text-center">
            <div class="col-sm-8">
              <button type="submit" class="btn btn-primary" id="submit_btn">Submit</button>
            </div>
          </div>

        </form>
      </div>
    </div>
    <script>
    const info_bar = document.getElementById('info_bar')

    function sendForm(e) {
      const form = new FormData(promo_apply_insert_form);
      console.log(form)
      $('#submit_btn').attr('disabled', true);
      fetch('promo_apply_insert_api.php', {
          method: 'POST',
          body: form
        })
        .then(response => response.json())
        .then(obj => {

          console.log(obj);

          info_bar.style.display = 'block';

          if (obj.success) {
            info_bar.className = 'alert alert-success';
            info_bar.innerHTML = '資料新增成功';
          } else {
            info_bar.className = 'alert alert-danger';
            info_bar.innerHTML = obj.errorMsg;
          }
          setTimeout(function() {
            info_bar.style.display = 'none';
          }, 3000);

          $('#submit_btn').attr('disabled', false);
        });
      return false;
    }
    </script>


    <?php include __DIR__ . './_footer.php';?>