<?php
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/functions.php";
$conn = db_check();
?>

<head>
<title>WIN88 | 發起討論</title>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php"; ?>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/nav.php"; ?>
  <link href="/final/css/cpwd.css" rel="stylesheet">
</head>

<body>
  <div class="table-responsive container">
    <div class="row ">
      <div class="col-10 offset-1">
        <div class="row" style="height:90vh;">
          <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/left.php"; ?>
          <div class="col-10" id="main0">
            <!--最下層-->
            <div class="row">
              <div class="col-12" id="main1" style="height:80vh;">
                <!--第2下層-->
                <div>
                  <form id="form" onsubmit="return false" method="get" action="/final/FP/models/article_check.php" $align="center" class="vertical-center">

                    <div class="row g-3 align-items-center d-flex justify-content-center">
                      <h4 id="text1">發布你的貼文</h4>
                      <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">文章標題: </label>
                      </div>
                      <div class="col-auto">
                        <input id="title" name="title" class="input" type="text" maxlength="50" required="" style="padding: 6px 12px 6px 12px; width: 516px;">
                      </div>
                    </div>
                    <div class="row g-3 align-items-center d-flex justify-content-center">
                      <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">文章類型: </label>
                      </div>
                      <div class="col-auto" style="height: 38px;">
                        <div class="input-group mb-3">
                          <select class="form-select" id="category" name="category" class="input" type="text" maxlength="50" required="" style="padding: 6px 12px 6px 12px; width: 516px;">
                            <option value="choose" selected>Choose...</option>
                            <option value="討論">討論</option>
                            <option value="預測">預測</option>
                            <option value="心情">心情</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row g-3 align-items-center d-flex justify-content-center">
                      <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">文章內容: </label>
                      </div>
                      <div class="col-auto">
                        <textarea id="content" name="content" class="input" cols="50" rows="10" type="text" maxlength="500" required="" style="padding: 6px 12px 6px 12px; width: 516px;height: 114px;"></textarea>
                      </div>
                    </div>
                    <div>
                      <div class="row" id="btn1">
                        <div class="col-4 offset-2">
                          <button type="submit" value="submit" class="btn btn-dark">發布貼文</button>
                        </div>
                        <div class="col-4">
                          <button type="submit" class="btn btn-dark" onclick="clearAll()">清空</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<script>
  $("#form").submit(function(e) {
    $("#submit").attr("disabled", true);
    var params = {
      writeArticle: '',
      title: $('#title').val(),
      category: $('#category').val(),
      content: $('#content').val(),
    };
    var query = jQuery.param(params);
    var form = $(this);
    var url = form.attr('action');
    $.ajax({
      type: "POST", //send it through get method
      url: url,
      data: params,
      success: function(data) {
        if (data.includes('文章新增成功')) {
          Swal.fire({
            icon: 'success',
            title: 'OK',
            text: '文章新增成功',
            allowOutsideClick: false,
            showCancelButton: false,
          }).then((result) => {
            if (result.value) {
              window.location = '/final/index.php'
            }
          })
        }
      }
    });
    e.preventDefault(); // 避免將表單直接發送而造成頁面跳轉.
  });

  function clearAll() {
    document.getElementById('title').value = ''
    document.getElementById('content').value = ''
    document.getElementById('category').value = 'choose';
  }
</script>