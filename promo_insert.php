<?php
require __DIR__ . '/_connectDB.php';

if (isset($_GET['promo_table'])) {
    $promo_table = $_GET['promo_table'];
}

$mem_sql = "SELECT * FROM member_level";
$mem_stmt = $pdo->query($mem_sql);
$mem_rows = $mem_stmt->fetchAll(PDO::FETCH_ASSOC);

$camp_type_sql = "SELECT * FROM campsite_type";
$camp_type_stmt = $pdo->query($camp_type_sql);
$camp_type_rows = $camp_type_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . './_header.php';
include __DIR__ . './_navbar.php';
?>
<main class="col-10 bg-white">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./promo_list.php">優惠方案查詢</a></li>
      <li class="breadcrumb-item active">新增優惠方案</li>
    </ol>
  </nav>
  <section class="" style="height: 100%;">

    <!-- submit result message -->
    <div id="info_bar" style="display: none" class="alert alert-success"></div>
    <div class="container">
      <div class="card-body">

        <div class="row d-flex justify-content-center">
          <div class="col-sm-8">
            <h5 class="card-title text-center">新增優惠方案</h5>
          </div>
        </div>
        <form method="POST" name="promo_form" onsubmit="return sendForm()">

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠方案類型</label>
            <div class="col-6">
              <select class="form-control" name="promo_table" id="promo_table">
                <option value="promo_user" <?php if (isset($promo_table)) {
    echo $promo_table == "promo_user" ? 'selected' : "";
}
?>>使用者促銷</option>
                <option value="promo_campType" <?php if (isset($promo_table)) {
    echo $promo_table == "promo_campType" ? 'selected' : "";
}
?>>營地分類折扣</option>
                <option value="promo_price" <?php if (isset($promo_table)) {
    echo $promo_table == "promo_price" ? 'selected' : "";
}
?>>滿額折扣</option>
              </select>
              <small class="form-text text-muted"></small>
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠方案名稱</label>
            <div class="col-6">
              <input type="text" class="form-control" name="promo_name" placeholder="輸入優惠方案名稱">
              <small class="form-text text-muted"></small>
            </div>
          </div>

          <!-- <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠條件</label>
            <div class="col-6">
              <input type="text" class="form-control" name="requirement" placeholder="優惠條件">
              <small class="form-text text-muted"></small>
            </div>
          </div> -->


          <?php if (isset($promo_table)): ?>
          <?php if ($promo_table == 'promo_user'): ?>


          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>會員等級</label>
            <div class="col-6">
              <select class="form-control" name="requirement">
                <?php foreach ($mem_rows as $mem_row): ?>
                <option value="<?=$mem_row['mem_level']?>"><?=$mem_row['level_title']?></option>
                <?php endforeach;?>
              </select>
              <small id="" class="form-text text-muted"></small>
            </div>
          </div>


          <?php elseif ($promo_table == 'promo_price'): ?>
          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>訂單滿額條件</label>
            <div class="col-6">
              <input class="form-control" type="text" value="" name="requirement" placeholder="請輸入訂單滿額條件">
              <small id="" class="form-text text-muted"></small>
            </div>
          </div>


          <?php elseif ($promo_table == 'promo_campType'): ?>
          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>營地類型</label>
            <div class="col-6">
              <select class="col-6 form-control" name="requirement">
                <?php foreach ($camp_type_rows as $camp_type_row): ?>
                <option value="<?=$camp_type_row['campType_id']?>"><?=$camp_type_row['campType_name']?></option>
                <?php endforeach;?>
              </select>
              <small id="" class="form-text text-muted"></small>
            </div>
          </div>

          <?php endif?>
          <?php endif;?>



          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>折扣類型</label>
            <div class="col-6">
              <select class="form-control" name="discount_type" id="discount_type">
                <option value="percentage">打折</option>
                <option value="currency">扣除金額</option>
              </select>
            </div>
          </div>


          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>折扣數值</label>
            <div class="col-6 input-group">
              <input type="text" class="form-control" name="discount_unit" id="discount_unit"
                placeholder="輸入折扣數值。例:9折、75折">
              <div class="input-group-append">
                <span class="input-group-text" id="discount_unit_suffix">折</span>
              </div>
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>開始時間</label>
            <div class="col-6">
              <input type="date" class="form-control" id="start" name="start" value="" min="2018-01-01" max="">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>結束時間</label>
            <div class="col-6">
              <input type="date" class="form-control" id="end" name="end" value="" min="2018-01-01" max="">
            </div>
          </div>


          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠方案描述</label>
            <div class="col-6">
              <textarea class="form-control" id="discription" name="discription" value=""></textarea>
            </div>
          </div>



          <div class="form-group justify-content-center row  text-center">
            <div class="col-sm-8">
              <button type="submit" class="btn btn-primary" id="submit_btn">新增</button>
            </div>
          </div>

        </form>
      </div>
    </div>
    <script>
    $(function() {
      $('#discount_type').on('click blur', function() {
        let discount_type = $(this).val()
        if (discount_type == 'percentage') {
          $('#discount_unit').attr('placeholder', '輸入折扣數值。例:9折、75折')
          $('#discount_unit_suffix').text('折');
        } else if (discount_type == 'currency') {
          $('#discount_unit').attr('placeholder', '輸入折抵金額。例:100元')
          $('#discount_unit_suffix').text('元');

        }
      })
      $('#discount_unit').on('keyup blur change', function() {
        let discount_unit = $('#discount_unit').val()

        if (isNaN(Number(discount_unit))) {
          info_bar.style.display = 'block';
          info_bar.className = 'alert alert-danger';
          info_bar.innerHTML = '請輸入數值';
          $('#discount_unit').val('')
          setTimeout(function() {
            info_bar.style.display = 'none';
          }, 3000);
        }

      })


      //設定日期欄位最小日期為今日
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1; //January is 0!
      var yyyy = today.getFullYear();
      if (dd < 10) {
        dd = '0' + dd
      }
      if (mm < 10) {
        mm = '0' + mm
      }

      today = yyyy + '-' + mm + '-' + dd;
      $('#avaliable_start').attr('min', today)
      $('#avaliable_end').attr('min', today)
      $('#coupon_expire').attr('min', today)


    });
    const promo_table = document.getElementById('promo_table');
    promo_table.addEventListener('change', sendPromoTable);

    function sendPromoTable() {
      let promoTableInput = new FormData();
      promoTableInput.append('promo_table', promo_table.value);
      fetch('_switchForm_api.php', {
          method: 'POST',
          body: promoTableInput
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          window.location.href = 'promo_insert.php?promo_table=' + data;
        })
    }

    const info_bar = document.getElementById('info_bar')

    function sendForm(e) {
      const form = new FormData(promo_form);

      $('#submit_btn').attr('disabled', true);
      fetch('promo_insert_api.php', {
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


    <?php include __DIR__ . './_footer.php'?>