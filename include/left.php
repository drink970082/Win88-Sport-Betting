<div class="col-2 " style="overflow-y:scroll; height:80vh;">
  <div class="row">
  <div class="dropdowns" style="padding: 0px;">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 0; border-color: black;">
            <div id="clock" style="text-align:center;color:azure;"></div>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="width:183.33px;">
            <li style="display: flex; justify-content: center; align-items: center;"><input id="setnewtime" type="text" style="width: 140px;"></input></li>
            <li style="display: flex; justify-content: center; align-items: center;"><button onclick=setTime() style="width: 140px; margin-top:10px;">更改</button></li>
        </ul>
    </div>
    <h2 style="border-bottom: 1px solid grey !important; margin:0px;"></h2>
    <div>
      <h2 style="text-align: center; margin-top: 12px;font-size:30px; margin-bottom: 12px; color:grey;">討論區</h2>
      <div style="text-align: center; margin: 20px;"><button class="btn btn-dark" onclick="window.location = '/final/FP/views/write_article.php'" style="border:0;"><b>發起討論</b></button></div>
      <h2 style="border-bottom: 1px solid grey !important; margin:0px;"></h2>
      <?php
      $article_sql = "SELECT ID, Title, Category, Content, Username, Img, Reg_date FROM user_article ORDER BY ID DESC;";
      $article_result = mysqli_query($conn, $article_sql);
      if (mysqli_num_rows($article_result) > 0) {
        while ($row = mysqli_fetch_assoc($article_result)) {
          echo "<div style=\"margin:20px 0px 20px 0px;\"><a class= \"hover1\" style=\"text-align: left;\" onclick=\"document.getElementById('" . $row["ID"] . "');showArticle('" . $row["ID"] . "');return false;\">" . $row["Title"] . "</a></div>";
          echo "<h2 style=\"border-bottom: 1px solid grey !important; margin:0px; \"></h2>";
        }
      }
      ?>
      <script>
        function showArticle(id) {
          window.location.href = "/final/FP/views/blog_msg.php?id=" + id;
        };
      </script>


    </div>
  </div>
</div>
<script>
  var initime = '2022-04-01 00:00:00';
  var timetext;
  if (!Cookies.get('time')) {
    timetext = initime;
  } else {
    timetext = Cookies.get('time');
  }
  var time = moment(timetext);
  var clock = document.getElementById('clock');

  function clockRunner() {
    time.add(1, "s");
    clock.innerHTML = time.format('YYYY-MM-DD HH:mm:ss');
    Cookies.set('time', time.format('YYYY-MM-DD HH:mm:ss'));
    setTimeout(clockRunner, 1000);
  }
  window.onload = clockRunner();
  function setTime()
  {
    var newtime=document.getElementById("setnewtime").value;
    Cookies.set('time', newtime);
    location.reload();
  }
</script>
<style>
  .hover1 {
    text-decoration: none;
    color: grey;
  }
  #dropdownMenuButton2::after {
      content: none;
  }
</style>