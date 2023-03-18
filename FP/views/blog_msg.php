<?php
session_start();
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/functions.php";
$conn = db_check();
$article_sql = "SELECT ID, Title, Category, Content, Username, Img, Reg_date FROM user_article ORDER BY ID DESC;";
?>

<head>
<title>WIN88 | 留言區</title>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php"; ?>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/nav.php"; ?>
  <link href="/final/css/cpwd.css" rel="stylesheet">
</head>

<body>
  <div class="table-responsive container">
    <div class="row ">
      <div class="col-10 offset-1">
        <div class="row ">
        <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/left.php"; ?>
          <div class="col-10" id="main0">
            <!--最下層-->
            <div class="row">
              <div class="col-12" id="main1" style="overflow-y:scroll; height:80vh;">
                <div id="topBtnGroup" class="sticky-top mt-5" align="left" style="width: 10%; height:85%; overflow-y: scroll; overflow-x:hidden; position: absolute;"></div>
                <div class="blog">
                  <div id="blogContent" class="container">
                    <div class="container" align="center">
                    <?php
                    $article_result = mysqli_query($conn, $article_sql);
                    $articles_id = $_GET["id"];
                    if (mysqli_num_rows($article_result) > 0) {
                      while($row = mysqli_fetch_assoc($article_result)) {
                        if($row["ID"]===$articles_id)
                        {
                        echo "<div id=\"". $row["ID"]. "\" class=\"article\" align=\"left\">";
                        echo "  <div class=\"text-center \"><h2>". $row["Title"]. "</h2>";
                        echo "    <div class=\"text-right mt-1 mr-2\" style=\"font-size: 10px\">文章類型: ". $row["Category"]. "</div>";
                        echo "    <div class=\"text-right mt-1 mr-2\" style=\"font-size: 10px\">發布時間: ". $row["Reg_date"]. "</div>";
                        echo "    <div class=\"text-right mt-1 mr-2\" style=\"font-size: 10px\">作者: " . $row["Username"]. "</div>
                                </div><hr>";
                        if ($row["Img"] !== NULL) {  // 如果img欄位有數值則顯示圖片
                          if(strlen($row["Img"]) > 0) {
                            $imgSrc = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://". $_SERVER['HTTP_HOST']. $row["Img"];
                            echo "<div class=\"text-center\" align=\"center\" style=\"width: 100%; margin:0 auto;\">
                                    <img src=\" ".$imgSrc. "\" style=\"display: block; max-width: 100%; max-height: 100%; margin:0 auto;\">
                                  </div>";
                          }
                        }
                        echo "  <div style=\"display: flex;\">";
                        echo "    <div class=\"text-center mt-2 ml-5 mr-5\" style=\"font-size:20px\">" . $row["Content"]. "</div>
                                </div>";
                        echo "  <div style=\"width: 100%\" align=\"center\">";
                        echo "  <button class=\"btn-sm\" onclick=\"message(".$row["ID"]. ",'". $_SESSION['username'] ."')\"> 留言</button>";
                    
                        if ($row["Username"] === $_SESSION['username']) {  // 如果登入狀態與文章的作者相同則新增刪除按鈕
                          echo "  <button class=\"btn-sm\" onclick=\"deleteArticle(".$row["ID"].")\"> 刪除文章</button>";
                        }
                        echo "  </div>";
                        $article_id = $row["ID"]; 
                        $message_sql = "SELECT ID, Article_ID, Username, Content, Reg_date FROM user_message WHERE Article_ID='$article_id' ORDER BY ID DESC;";
                        $message_result = mysqli_query($conn, $message_sql);
                        echo "  <div id=\"article". $row["ID"]. "\">";
                        if (mysqli_num_rows($message_result) > 0) {  // 如果該文章下面有流言則顯示出留言內容
                          while($row = mysqli_fetch_assoc($message_result)) {
                            echo "  <div id=\"message". $row["ID"] ."\">";
                            echo "    <div class=\"text-left mt-5\" style=\"font-size: 16px\">".$row["Username"].": ". $row["Content"];
                            echo "      <div class=\"text-right mt-1 mr-2\" style=\"font-size: 10px\">發布時間: ". $row["Reg_date"]. "</div>";
                            if ($row["Username"] === $_SESSION['username']) {  // 如果登入狀態與留言的作者相同則新增刪除按鈕
                              echo "    <div class=\"text-right mt-1 mr-2\" style=\"font-size: 10px\"><button class=\"btn-sm\" onclick=\"deleteMessage(".$row["ID"].",'". $_SESSION['username'] ."')\"> 刪除留言</button></div>";
                            }
                            echo "    </div>";
                            echo "  </div>";
                          }
                        }
                        echo "  </div>";
                        echo "</div><br>"; 
                        echo "  <button onclick=\"back();\">回主頁</button>";
                        }
                      }
                    }
                    $conn->close();
                    ?>
                    </div>
                  </div>
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
function back()
{
  window.location.href = "/final/index.php";
}


function deleteArticle(id) {  // 雖然一樣為ajax的方式，但最後有做網頁重整
  Swal.fire({
  icon: 'warning',
  title: 'warning',
  text: '確定要將文章和留言一同刪除嗎?',
  showCancelButton: true,
  }).then((result) => {
    if (result.value) {
      $.ajax({
        type: "POST",
        url: '/final/FP/models/article_check.php',
        data: {
          deleteArticle: id,
        },
        success: function(data) {
          if(data.includes('文章刪除成功')) {
            Swal.fire({
              icon: 'success',
              title: 'OK',
              text: '文章刪除成功',
              allowOutsideClick: false,
              showCancelButton: false,
            }).then((result) => {
              if (result.value) {
                window.location = '/final/index.php'  // 網頁重整
              }
            })
          }
        }
      });
    }
  });
}
function deleteMessage(id, username) {
  Swal.fire({
  icon: 'warning',
  title: 'warning',
  text: '確定要刪除嗎?',
  showCancelButton: true,
  }).then((result) => {
    if (result.value) {
      $.ajax({
        type: "POST",
        url: '/final/FP/models/article_check.php',
        data: {
          deleteMessage: id,
          username: username,
        },
        success: function(data) {
          $('#message' + id ).remove();
          Swal.fire({
          icon: 'success',
          title: 'OK',
          text: '刪除留言成功',
          });
        }
      });
    }
  });
}
async function message(articleId, username) {
  const { value: text } = await Swal.fire({
    title: '留言',
    input: 'textarea',
    inputPlaceholder: '請輸入留言'
  })
  if (text) {
    var messageId = '';
    var params= {
      writeMessage: '',
      articleId: articleId,
      username: username,
      content: text
    };
    $.ajax({
      type: "POST",
      url: '/final/FP/models/article_check.php',
      data: params,
      success: function(data) {
        messageId = data;
        var dt = new Date();
        var time = dt.getFullYear() + '-' + (dt.getMonth()+1) + '-' + dt.getDate() + ' ' + dt.getHours() + ':' + dt.getMinutes() + ':' + dt.getSeconds();
        $('#article' + articleId.toString()).prepend("\
          <div id=\"message"+ messageId + "\">\
            <div class=\"text-left mt-5\" style=\"font-size: 16px\">" +username+": "+ text + "\
              <div class=\"text-right mt-1 mr-2\" style=\"font-size: 10px\">發布時間: " + time + "</div>" + "\
              <div class=\"text-right mt-1 mr-2\" style=\"font-size: 10px\"><button class=\"btn-sm\" onclick=\"deleteMessage('" + messageId +"','" + username + "')\"> 刪除留言</button></div>" + "\
            </div>\
          </div>\
        ");
        Swal.fire({
        icon: 'success',
        title: 'OK',
        text: '留言成功',
        });
      }
      
    });
  } 
}
</script>

<style>
body {
  background-color: #ffffff !important;
  /* overflow: hidden */
}

h2 {
  color: #4C4C4C;
  word-spacing: 5px;
  font-size: 30px;
  font-weight: 700;
  margin-bottom: 30px;
  font-family: 'Raleway', sans-serif;
}

.blog {
  padding: 20px 0px;
}


#topBtnGroup button {
  background-color: rgba(175, 175, 175, 0.2);
}

#blogContent::-webkit-scrollbar, #topBtnGroup::-webkit-scrollbar
{
	width: 12px;
	background-color: rgba(0,0,0,0);
}

#blogContent::-webkit-scrollbar-thumb, #topBtnGroup::-webkit-scrollbar-thumb
{
	border-radius: 10px;
	/*-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.2);*/
	background-color: #ffffff;
}
</style>