<?php
include __DIR__ . '/_connectDB.php';

?>


<?php include __DIR__ . './_header.php';
include __DIR__ . './_navbar.php';
?>
<main class="col-10 bg-white">


  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">優惠方案查詢</li>
    </ol>
  </nav>
  <section class="container-fluid" style="height: 100%;">
    <div class="container-fluid">
      <div class="row py-2">
        <div class="col-md-10">
          <div class="alert alert-success" role="alert" style="display: none;" id="info_bar"></div>
        </div>
        <div class="col-md-2">
          <select class="form-control" id="fetch_option_promo">
            <option value="">---請選擇查詢方案類型---</option>
            <option data-promo_table="promo_user">使用者促銷</option>
            <option data-promo_table="promo_product">產品促銷</option>
            <option data-promo_table="promo_price">價格促銷</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <table class="table table-striped table-bordered" id="promo_table">
            <thead>
              <tr>
                <th scope="col">編號</th>
                <th scope="col">方案名稱</th>
                <th scope="col">適用條件</th>
                <th scope="col">折扣數值</th>
                <th scope="col">折扣類型</th>
                <th scope="col">開始時間</th>
                <th scope="col">結束時間</th>
                <th scope="col">方案描述</th>
                <th scope="col">操作</th>
                <th scope="col"><input type="checkbox" id="select_all"></th>
              </tr>
            </thead>
            <tbody id="promo_output">

            </tbody>
          </table>
        </div>
      </div>


    </div>
    <script>
    let ori_data = []; // data
    let ori_obj = {}; // data
    const info_bar = document.getElementById('info_bar')



    const dis_type_arr = {
      1: '打折',
      2: '扣除金額'
    };
    $(function() {

      function fetch_promo(promo_table) {
        $('#promo_table').DataTable({

          dom: 'lf<"#pagi-wrap.d-flex"<"mr-auto"B>p>t<"mt-3">',

          buttons: [{
            className: 'btn btn-primary ',
            attr: {
              id: 'promo_insert_btn'
            },
            text: '新增優惠方案',
            action: function() {
              window.location = './promo_insert.php'
            },

          }, ],
          "processing": true,
          "serverSide": true,
          "order": [],
          "ajax": {
            url: "promo_list_api.php",
            type: "POST",
            data: {
              promo_table: promo_table
            }
          },
          "columnDefs": [{
              "targets": [9],
              "data": "promo_id",
              "render": function(data, type, row, meta) {
                return "<input data-promo_id=" + data + " type='checkbox'>";
              }
            },
            {
              "targets": [8],
              "data": "promo_id",
              "render": function(data, type, row, meta) {
                return '<a href="coupon_genre_edit.php?promo_id=' + data +
                  '" class="edit_btn mx-1 p-1" data-promo_id=' + data +
                  '><i class="fas fa-edit"></i></a > <a href="#" class="del-btn mx-1 p-1" data-promo_id=' +
                  data + '><i class="fas fa-trash-alt"></i></a>';
              }
            },
          ],
          "columns": [{
              "data": "promo_id"
            },
            {
              "data": "promo_name"
            },
            {
              "data": "requirement",
              "render": function(data) {
                let display = ''
                if (data == '1') {
                  display = "露營新手";
                } else if (data == '2') {
                  display = "業餘露營家"
                } else if (data == '3') {
                  display = "露營達人"
                }
                return display;
              }
            },
            {
              "data": "discount_unit"
            },
            {
              "data": "discount_type",
              "render": function(data) {
                let display = ''
                if (data == 'percentage') {
                  display = "折扣";
                } else if (data == 'currency') {
                  display = "扣除金額"
                }
                return display;
              }
            },
            {
              "data": "start",
            },
            {
              "data": "end",
            },
            {
              "data": "discription"
            }
          ],
        })
      }
      fetch_promo()

      $('#fetch_option_promo').change(function() {
        let promo_table = $("#fetch_option_promo option:selected").data('promo_table')
        $('#promo_table').DataTable().destroy();
        fetch_promo(promo_table)

      })
    })
    </script>
    <?php include __DIR__ . './_footer.php'?>