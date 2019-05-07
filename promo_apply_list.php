<?php
include __DIR__ . '/_connectDB.php';

try {
    $member_sql = "SELECT * FROM member_list";
    $member_stmt = $pdo->query($member_sql);
    $member_rows = $member_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}
?>


<?php include __DIR__ . './_header.php';
include __DIR__ . './_navbar.php';
?>

<main class="col-10 bg-white">

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active"><a href="#">優惠活動參加紀錄查詢</a></li>
    </ol>
  </nav>

  <section class="container-fluid" style="height: 100%;">

    <div class="row py-2">
      <div class="col-md-10">
        <div class="alert alert-success" role="alert" style="display: none;" id="info_bar"></div>
      </div>

      <div class="col-md-2">
        <div class="">
          <select class="form-control" id="fetch_option">
            <option class="dropdown-item">列出所有紀錄</option>
            <option class="dropdown-item" data-sql="WHERE `promo_type`='promo_user'">列出會員優惠營地</option>
            <option class="dropdown-item" data-sql="WHERE `promo_type`='promo_campType'">列出營地分類優惠</option>
            <option class="dropdown-item" data-sql="WHERE `promo_type`='promo_price'">列出滿額優惠營地</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 table-responsive">
        <table id="promo_apply_table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col">紀錄編號</th>
              <th scope="col">優惠類別</th>
              <th scope="col">加入日期</th>
              <th scope="col">有效狀態</th>
              <th scope="col">營地編號</th>
              <th scope="col">操作</th>
              <th scope="col"><input type="checkbox" id="checkAll"></th>
            </tr>
          </thead>
          <tbody id="promo_apply_output">

          </tbody>
        </table>
      </div>
    </div>



    <script>
    $(function() {

      function fetch_promo_apply(sql) {
        $('#promo_apply_table').DataTable({
          drawCallback: function() {
            let checkCount = $("tbody .checkbox_manipulation :checkbox").length
            let checkedCount = $("tbody .checkbox_manipulation :checked").length
            $("#checkAll").click(function() {
              let checkAll = $("#checkAll").prop("checked");
              $("tbody :checkbox").prop("checked", checkAll);
            })

            $(".checkbox_manipulation :checkbox").click(function() {
              checkedCount = $("tbody .checkbox_manipulation :checked").length
              if (checkCount == checkedCount) {
                $("#checkAll").prop("checked", true)
              } else {
                $("#checkAll").prop("checked", false)

              }
            })



            // $('.switch').checkToggler({

            //   labelOn: "啟用",
            //   labelOff: "關閉"

            // }).on('change', function() {
            //   let coupon_record_id = $(this).data('coupon_record_id')
            //   let coupon_valid = 0
            //   if ($(this).prop('checked')) {
            //     coupon_valid = 1
            //   } else {
            //     coupon_valid = 2
            //   }
            //   // console.log(coupon_valid)
            //   const formData = new FormData()
            //   formData.append('coupon_record_id', coupon_record_id)
            //   formData.append('coupon_valid', coupon_valid)

            //   fetch('coupon_gain_edit_api.php', {
            //     method: 'POST',
            //     body: formData
            //   }).then(obj => obj.json()).then(result => {
            //     // console.log(result)
            //     $('#coupon_table').DataTable().destroy()
            //     fetch_coupon()
            //   })
            // });
          },
          dom: 'lf<"#pagi-wrap.d-flex"<"mr-auto"B>p>t<"mt-3">',
          buttons: [{
              className: 'btn btn-primary ',
              attr: {
                id: 'promo_apply_insert_btn'
              },
              text: '新增優惠活動參加紀錄',
              action: function() {
                window.location = './promo_apply_insert.php'
              },
            },
            {
              className: 'btn btn-info',
              text: '多筆操作',
              action: function(e, dt, node, config) {

              },
              attr: {
                'data-toggle': 'modal',
                'data-target': '#multi_switch_modal'
              }
            },
          ],
          "processing": true,
          "serverSide": true,
          "order": [],
          "ajax": {
            url: "promo_apply_list_api.php",
            type: "POST",
            data: {
              promo_type_condition: sql
            }
          },
          "columnDefs": [{
              "targets": [6],
              "data": "promo_apply_id",
              "className": "checkbox_manipulation",
              "render": function(data, type, row, meta) {
                return "<input data-promo_apply_id=" + data + " type='checkbox'>";
              }
            },
            {
              "targets": [5],
              "data": "promo_apply_id",
              "render": function(data, type, row, meta) {
                return '<a href="promo_apply_edit.php?promo_apply_id=' + data +
                  '" class="edit_btn mx-1 p-1" data-promo_apply_id=' + data +
                  '><i class="fas fa-edit"></i></a > <a href="#" class="del-btn mx-1 p-1" data-promo_apply_id=' +
                  data + '><i class="fas fa-trash-alt"></i></a>';
              }
            },
          ],
          "columns": [{
              "data": "promo_apply_id"
            },
            {
              "data": "promo_type",
              "render": function(data) {
                let display = ''
                if (data == 'promo_user') {
                  display = '會員優惠';
                } else if (data == 'promo_campType') {
                  display = '營地分類優惠'
                } else if (data == 'promo_price') {
                  display = '滿額優惠'
                }
                return display;
              }
            },
            {
              "data": "apply_date"
            },
            {
              "data": "apply_valid",
              "render": function(data) {
                let display = ''
                if (data == 1) {
                  display = '啟用';
                } else if (data == 2) {
                  display = '關閉'
                }
                return display;
              }
            },
            {
              "data": "camp_id",
              "className": "text-truncate"
            },


          ],
        })
      }
      fetch_promo_apply()
      $('#fetch_option').change(function() {
        let sql = $("#fetch_option option:selected").data('sql')
        $('#promo_apply_table').DataTable().destroy();
        fetch_promo_apply(sql)

      })

      // $('#multi_switch').checkToggler({
      //   labelOn: "啟用",
      //   labelOff: "關閉"
      // })


      const info_bar = $("#info_bar");

      $("#promo_apply_table tbody").on("click", ".del-btn", function() {
        let del_btn = $(this)
        // console.log(del_btn)
        $.confirm.show({
          "message": "確認刪除此筆紀錄",
          "yesText": "確認",
          "noText": "取消",
          "yes": function() {
            let promo_apply_id = del_btn.data('promo_apply_id');
            // console.log(promo_apply_id)
            // console.log(del_btn)
            const form = new FormData();
            form.append("promo_apply_id", promo_apply_id);
            fetch('promo_apply_delete_api.php', {
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

              $('#promo_apply_table').DataTable().destroy();
              fetch_promo_apply()
              $("#select_all").prop('checked', false)
            });
          },
          "no": function() {
            return false
          },
        })
      });
      // function group_del(e, dt, node, config) {
      //   let form = new FormData();
      //   let delete_coupons = [];
      //   $('#coupon_table tbody :checked').each(function() {
      //     delete_coupons.push($(this).data('coupon_id'))
      //   });
      //   if (confirm('確認刪除資料')) {
      //     info_bar.css('display', 'block');
      //     if (delete_coupons.length < 1) {
      //       info_bar.attr('class', 'alert alert-danger');
      //       info_bar.html("未選擇資料");
      //       setTimeout(function() {
      //         info_bar.css('display', 'none')
      //       }, 3000);
      //       return false;
      //     } else {
      //       let delete_coupons_str = JSON.stringify(delete_coupons);
      //       form.append('delete_coupons', delete_coupons_str);
      //       fetch('_group_delete_api.php', {
      //           method: 'POST',
      //           body: form
      //         })
      //         .then(response => response.json())
      //         .then(data => {
      //           console.log(data);
      //           $('#coupon_table').DataTable().destroy();
      //           fetch_coupon(sql);
      //           info_bar.attr('class', 'alert alert-success');
      //           info_bar.html("刪除成功");
      //           setTimeout(function() {
      //             info_bar.css('display', 'none')
      //           }, 3000);
      //         })
      //     }
      //   }
      // }
    });
    </script>
    <?php include __DIR__ . './_footer.php'?>