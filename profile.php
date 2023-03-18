<?php
session_start();
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/functions.php";
$conn = db_check();
$user_data = check_login($conn);
$id = $user_data['User_ID'];
$user_name = $user_data['Username'];
$Remaining = $user_data['Remaining'];

if (isset($_POST['update'])) {
  $money = $_POST['store'];
  $Remaining += $money;
  $time=$_COOKIE['time'];
  $sql = "UPDATE users SET Remaining='$Remaining' WHERE User_ID='$id'";
  mysqli_query($conn, $sql);
  $sql = "INSERT INTO deposit (User_ID,Amount,Total,Time) values ('$id','$money','$Remaining','$time')";
  mysqli_query($conn, $sql);
  echo '<script>window.history.go(-1)</script>';
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>WIN88 | 帳戶儲值</title>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php"; ?>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/nav.php"; ?>
  <link href="/final/css/cpwd.css" rel="stylesheet">
  <link href="/final/css/profile.css" rel="stylesheet">
</head>

<body style="overflow-y:hidden;">
  <div class="table-responsive container">
    <div class="row ">
      <div class="col-10 offset-1">
        <div class="row ">
          <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/left.php"; ?>
          <div class="col-10" id="main0">
            <!--最下層-->
            <div class="row">
              <div class="col-12" id="main1">
                <!--第2下層-->
                <h4 id="text1">
                  <?php echo "Hello, {$user_name}. Your Remaining Balance is: {$Remaining}"; ?>
                </h4>
                <form action="" method="POST">
                  <div class="input-group">
                    <input type="number" name="store" min=0 value=100 class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="submit" name="update" value="儲值" id="button-addon2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;儲值&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                  </div>
                </form>

                <div class="border-bottom" id="line">
                  <p id="text2">儲值記錄</p>
                </div>

                <div class="row" id="record_name">
                  <!--項目名稱-->
                  <div class="col-1"></div>
                  <div class="col-5">
                    <p id="text1">時間</p>
                  </div>
                  <div class="col-3">
                    <p id="text1">金額</p>
                  </div>
                  <div class="col-3">
                    <p id="text1">餘額</p>
                  </div>
                </div>

                <div class="row" id="cash_record" style="overflow-y:scroll; height:37vh;">
                  <!--少後端-->
                  <?php
                  $sql = "SELECT Amount ,Time, Total FROM deposit WHERE User_ID='$id' ORDER BY Time desc";
                  $result = mysqli_query($conn, $sql);
                  $cnt = mysqli_num_rows($result);
                  if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo "<div class='col-1'>";
                      echo "<p id='text1'>" . $cnt . "</p>";
                      echo "</div>";
                      echo "<div class='col-5'>";
                      echo "<p id='text1'>" . $row["Time"] . "</p>";
                      echo "</div>";
                      echo "<div class='col-3'>";
                      echo "<p id='text1'>" . $row["Amount"] . "</p>";
                      echo "</div>";
                      echo "<div class='col-3'>";
                      echo "<p id='text1'>" . $row["Total"] . "</p>";
                      echo "</div>";
                      $cnt -= 1;
                    }
                  }
                  ?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>