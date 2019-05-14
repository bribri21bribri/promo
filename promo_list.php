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

            <option value="promo_user" data-promo_table="promo_user">使用者促銷</option>
            <option value="promo_campType" data-promo_table="promo_campType">營地類別促銷</option>
            <option value="promo_price" data-promo_table="promo_price">價格促銷</option>
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
    const info_bar = $("#info_bar");





    $(function() {

      function fetch_promo(promo_table) {
        $('#promo_table').DataTable({
          language: {
            searchPlaceholder: "輸入方案名稱"
          },

          dom: 'lf<"#pagi-wrap.d-flex"<"mr-auto"B>p>t<"mt-3">',

          buttons: [{
            className: 'btn btn-primary ',
            attr: {
              id: 'promo_insert_btn'
            },
            text: '新增優惠方案',
            action: function() {
              window.location = './promo_insert.php?promo_table=promo_user'
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
                return '<a href="promo_edit.php?promo_table=' + $("#fetch_option_promo").val() +
                  '&promo_id=' + data +
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
                let fetch_option_promo = $('#fetch_option_promo').val()
                let display = ''

                switch (fetch_option_promo) {
                  case 'promo_user':
                    if (data == '1') {
                      display = "露營新手";
                    } else if (data == '2') {
                      display = "業餘露營家"
                    } else if (data == '3') {
                      display = "露營達人"
                    }
                    break
                  case 'promo_campType':
                    display = data
                    break
                  case 'promo_price':
                    display = "訂購滿" + data + "元"
                    break
                }




                return display;
              }
            },
            {
              "data": "discount_unit",
              "render": function(data, type, row, meta) {
                let display = ''
                if (row.discount_type == 'percentage') {
                  display = data + '折'
                } else if (row.discount_type == 'currency') {
                  display = data + '元'
                }
                return display
              }
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

      $("#promo_table tbody").on("click", ".del-btn", function() {
        let del_btn = $(this)
        // console.log(del_btn)
        $.confirm.show({
          "message": "確認刪除此筆優惠方案",
          "yesText": "確認",
          "noText": "取消",
          "yes": function() {
            let promo_table = $("#fetch_option_promo").val();
            let promo_id = del_btn.data('promo_id');
            console.log(promo_table)
            console.log(del_btn)
            const form = new FormData();
            form.append('promo_table', promo_table)
            form.append("promo_id", promo_id);
            fetch('promo_delete_api.php', {
              method: "POST",
              body: form
            }).then(response => {
              return response.json()
            }).then(result => {
              console.log(result);

              info_bar.css("display", "block")
              if (result['success']) {
                info_bar.attr('class', 'alert alert-info').text('刪除成功');
              } else {
                info_bar.attr('class', 'alert alert-danger').text(result.errorMsg);
              }
              setTimeout(function() {
                info_bar.css("display", "none")
              }, 3000)

              $('#promo_table').DataTable().destroy();
              fetch_promo()
              $("#select_all").prop('checked', false)
            });
          },
          "no": function() {
            return false
          },
        })
      });
    })
    </script>
    <?php include __DIR__ . './_footer.php'?>