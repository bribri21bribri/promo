<?php
require __DIR__ . '/_connectDB.php';

$promo_id = isset($_GET['promo_id']) ? intval($_GET['promo_id']) : 0;
$promo_table = htmlentities($_GET['promo_table']);
$sql = "SELECT * FROM $promo_table WHERE promo_id=$promo_id";

$stmt = $pdo->query($sql);

if ($stmt->rowCount() == 0) {
    header('Location: _list.php');
    exit;
}

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$mem_sql = "SELECT * FROM member_level";
$mem_stmt = $pdo->query($mem_sql);
$mem_rows = $mem_stmt->fetchAll(PDO::FETCH_ASSOC);

$camp_type_sql = "SELECT * FROM campsite_type";
$camp_type_stmt = $pdo->query($camp_type_sql);
$camp_type_rows = $camp_type_stmt->fetchAll(PDO::FETCH_ASSOC);

$dis_type_sql = "SELECT * FROM dis_type";
$dis_type_stmt = $pdo->query($dis_type_sql);
$dis_type_rows = $dis_type_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php include __DIR__ . './_header.php';
include __DIR__ . './_navbar.php';

?>
<style>
.form-group small {
  color: red !important;
}
</style>


<main class="col-10 bg-white">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./promo_list.php">優惠方案查詢</a></li>
      <li class="breadcrumb-item active">編輯優惠方案</li>
    </ol>
  </nav>
  <section class="" style="height: 100%;">

    <!-- submit result message -->
    <div id="info_bar" style="display: none" class="alert alert-success"></div>
    <div class="container">
      <div class="card-body">

        <div class="row d-flex justify-content-center">
          <div class="col-sm-8">
            <h5 class="card-title text-center">編輯優惠方案</h5>
          </div>
        </div>
        <form method="POST" name="promo_form" onsubmit="return sendForm()">

          <input type="hidden" class="form-control" name="promo_table" value="<?=$promo_table?>">
          <input type="hidden" class="form-control" name="promo_id" value="<?=$promo_id?>">

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠方案名稱</label>
            <div class="col-6">
              <input type="text" class="form-control" name="promo_name" placeholder="輸入優惠方案名稱"
                value="<?=$row['promo_name']?>">
              <small class="form-text text-muted"></small>
            </div>
          </div>


          <?php if (isset($promo_table)): ?>
          <?php if ($promo_table == 'promo_user'): ?>


          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>會員等級</label>
            <div class="col-6">
              <select class="form-control" name="requirement">
                <?php foreach ($mem_rows as $mem_row): ?>
                <option <?=$row['requirement'] == $mem_row['mem_level'] ? "selected" : "";?>
                  value="<?=$mem_row['mem_level']?>"><?=$mem_row['level_title']?></option>
                <?php endforeach;?>
              </select>
              <small id="" class="form-text text-muted"></small>
            </div>
          </div>


          <?php elseif ($promo_table == 'promo_price'): ?>
          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>訂單滿額條件</label>
            <div class="col-6">
              <input class="form-control" type="text" value="<?=$row['requirement']?>" name="requirement"
                placeholder="請輸入訂單滿額條件">
              <small id="" class="form-text text-muted"></small>
            </div>
          </div>


          <?php elseif ($promo_table == 'promo_campType'): ?>
          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>營地類型</label>
            <div class="col-6">
              <select class="col-6 form-control" name="requirement">
                <?php foreach ($camp_type_rows as $camp_type_row): ?>
                <option <?=$row['requirement'] == $camp_type_row['campType_id'] ? "selected" : "";?>
                  value="<?=$camp_type_row['campType_id']?>"><?=$camp_type_row['campType_name']?></option>
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
                placeholder="輸入折扣數值。例:9折、75折" value="<?=$row['discount_unit']?>">
              <div class="input-group-append">
                <span class="input-group-text" id="discount_unit_suffix">折</span>
              </div>
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>開始時間</label>
            <div class="col-6">
              <input type="date" class="form-control" id="start" name="start" value="<?=$row['start']?>"
                min="2018-01-01" max="">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>結束時間</label>
            <div class="col-6">
              <input type="date" class="form-control" id="end" name="end" value="<?=$row['end']?>" min="2018-01-01"
                max="">
            </div>
          </div>


          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠方案描述</label>
            <div class="col-6">
              <textarea class="form-control" id="discription" name="discription"><?=$row['discription']?></textarea>
            </div>
          </div>



          <div class="form-group justify-content-center row  text-center">
            <div class="col-sm-8">
              <button type="submit" class="btn btn-primary" id="submit_btn">送出</button>
            </div>
          </div>

        </form>
      </div>
    </div>
    <script>
    const info_bar = document.querySelector('#info_bar');
    const submit_btn = document.querySelector('#submit_btn');

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
    });
    // const fields = [
    //     'name',
    //     'email',
    //     'mobile',
    //     'address'
    // ];
    // const fs = {};
    // for (let v of fields) {
    //     fs[v] = document.form1[v];
    // }
    //
    // function checkForm() {
    //     let fsv = {};
    //     for (let v of fields) {
    //         fsv[v] = fs[v].value;
    //     }
    //     console.log(fsv);
    //
    //     let isPassed = true;
    //
    //
    //     let email_pattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    //     let mobile_pattern = /^09\d{2}\-?\d{3}\-?\d{3}$/;
    //
    //     for (let v of fields) {
    //         fs[v].style.borderColor = '#cccccc';
    //         document.querySelector('#' + v + 'Help').innerHTML = '';
    //     }


    // let name = document.form1.name.value;
    // let  email = document.form1.email.value;
    //  let  mobile = document.form1.mobile.value;

    // if (fsv.name.length < 2) {
    //     fs.name.style.borderColor = 'red';
    //     document.querySelector('#nameHelp').innerHTML = '請填寫正確的姓名!';
    //     isPassed = false;
    // }
    // if (!email_pattern.test(fsv.email)) {
    //     fs.email.style.borderColor = 'red';
    //     document.querySelector('#emailHelp').innerHTML = '請填寫正確的Email!';
    //     isPassed = false;
    // }
    // if (!mobile_pattern.test(fsv.mobile)) {
    //     fs.mobile.style.borderColor = 'red';
    //     document.querySelector('#mobileHelp').innerHTML = '請填寫正確的電話!';
    //     isPassed = false;
    // }
    // if (isPassed) {
    //     let form = new FormData(document.form1);
    //
    //     submit_btn.style.display = "none";
    //
    //         fetch('_insert2_api.php', {
    //             method: 'POST',
    //             body: form
    //         })
    //             .then(response=>response.json())
    //             .then(obj=>{
    //
    //                 console.log(obj);
    //
    //                 info_bar.style.display = 'block';
    //
    //                 if(obj.success){
    //                     info_bar.className = 'alert alert-success';
    //                     info_bar.innerHTML = '資料新增成功';
    //                 } else {
    //                     info_bar.className = 'alert alert-danger';
    //                     info_bar.innerHTML = obj.errorMsg;
    //                 }
    //                 submit_btn.style.display = "block";
    //             });
    //
    //
    //     }
    //     return false;
    // }
    function sendForm() {
      let form = new FormData(document.promo_form);

      fetch('promo_edit_api.php', {
          method: 'POST',
          body: form
        })
        .then(response => response.json())
        .then(obj => {

          console.log(obj);

          info_bar.style.display = 'block';

          if (obj.success) {
            info_bar.className = 'alert alert-success';
            info_bar.innerHTML = '資料修改成功';
          } else {
            info_bar.className = 'alert alert-danger';
            info_bar.innerHTML = obj.errorMsg;
          }
          submit_btn.style.display = "";
          setTimeout(function() {
            info_bar.style.display = 'none'
          }, 3000);
        });
      return false;
    }
    </script>


    <?php include __DIR__ . './_footer.php'?>