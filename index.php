<?php
session_start();



require_once("models/db_check.php");
require_once("models/functions.php");
$conn = db_check();
$user_data = check_login($conn);
$user_id = $user_data['User_ID'];
$Remaining = $user_data['Remaining'];
?>
<!DOCTYPE html>
<meta name="viewport" content="width=device-width" />
<meta charset="UTF-8">
<html>

<head>
<title>WIN88 | 下注</title>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php"; ?>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/nav.php"; ?>
  <link href="/final/css/cpwd.css" rel="stylesheet">
</head>

<body>
  <div id="ohsnap"></div>
  <!---下注表格--->
  <div class="table-responsive container">
    <div class="row ">
      <div class="col-10 offset-1">
        <div class="row " style="height:90vh;">
          <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/left.php"; ?>
          <div class="col-10" id="main0">
            <div class="row">
              <div class="col-12" id="main1" style="overflow-y:scroll; height:80vh;">
                <table class="table caption-top bet">
                  <thead>
                    <tr>
                      <th scope="col">時間</th>
                      <th scope="col">主客隊伍</th>
                      <th scope="col">讓分</th>
                      <th scope="col"></th>
                      <th scope="col">不讓分</th>
                      <th scope="col">大小分</th>
                      <th scope="col"></th>
                      <th scope="col">單雙</th>
                    </tr>
                  </thead>               
                  <tbody>
                    <?php
                    if (isset($_COOKIE['time'])) {
                      $time = strtotime($_COOKIE['time']);
                      $dateshow = date('Y-m-d', $time);
                      $nowtime = date_create($_COOKIE['time']);
                      $date2 = date_create($dateshow . " 17:00");
                      $diff = date_diff($nowtime, $date2)->format("%R");
                      if ($diff === '-') {
                        $dateshow = date_create($dateshow);
                        date_modify($dateshow, "+1 days");
                        $dateshow = date_format($dateshow, "Y-m-d");
                      }
                    } else $dateshow = "2022-04-01";
                    echo"<caption style='border-top-width:0px;'>"."下注 ".$dateshow."</caption>";
                    $nowtime = date_create("2022-04-01 00:00:00");
                    $sql = "SELECT Game_ID,時間,球隊,讓分,讓分賠率,不讓分賠率,大小分,大小分賠率,單雙賠率 FROM sport WHERE 日期='$dateshow'";
                    $result = mysqli_query($conn, $sql);
                    $flag = 0;
                    if (mysqli_num_rows($result) > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                        if ($flag == 0) {
                          $team = "";
                          $row1 = $row;
                          $teamItem = "";
                        }
                        $team .= $row["球隊"];
                        $teamItem .= $row["球隊"];
                        if ($flag == 1) $team .= "(主)";
                        else {
                          $team .= "<br>";
                          $teamItem .= '@';
                        }
                        if ($flag == 1) {
                          echo "<tr>";
                          echo "<td rowspan=2 scope='col' style='vertical-align : middle'>" . $row1["時間"] . "</td>";
                          echo "<td rowspan=2 scope='col' style='vertical-align : middle'>" . $team . "</td>";
                          echo "<td scope='col' class='bet-noTDBorder'>" . $row1["讓分"] . "</td>";
                          echo "<td scope='col' class='bet-noTDBorder'>
                            <button class='notshow' data-bs-toggle='modal' data-bs-target='#betModal' teamItem={$teamItem} betItem='讓分: {$row1['讓分']}' betOdds={$row1['讓分賠率']} GameID={$row1['Game_ID']}>" . $row1["讓分賠率"] . "</button></td>";
                          if ($row1["不讓分賠率"]) echo "<td scope='col' class='bet-noTDBorder'>
                            <button class='notshow' data-bs-toggle='modal' data-bs-target='#betModal' teamItem={$teamItem} betItem='不讓分:' betOdds={$row1['不讓分賠率']} GameID={$row1['Game_ID']}>" . $row1["不讓分賠率"] . "</button></td>";
                          else echo "<td scope='col' class='bet-noTDBorder'>" . $row1["不讓分賠率"] . "</td>";
                          echo "<td rowspan=2 scope='col' style='vertical-align : middle'>" . $row1["大小分"] . "</td>";
                          echo "<td scope='col' class='bet-noTDBorder'>
                            <button class='notshow' data-bs-toggle='modal' data-bs-target='#betModal' teamItem={$teamItem} betItem='大小分:' betOdds={$row1['大小分賠率']} GameID={$row1['Game_ID']}>" . $row1["大小分賠率"] . "</button></td>";
                          echo "<td scope='col' class='bet-noTDBorder'>
                              <button class='notshow' data-bs-toggle='modal' data-bs-target='#betModal' teamItem={$teamItem} betItem='單雙:' betOdds={$row1['單雙賠率']} GameID={$row1['Game_ID']}>" . $row1["單雙賠率"] . "</button></td>";
                          echo "</tr>";
                          echo "<tr>";
                          echo "<td scope='col' class='bet-noTBorder'>" . $row["讓分"] . "</td>";
                          echo "<td scope='col' class='bet-noTBorder'>
                              <button class='notshow' data-bs-toggle='modal' data-bs-target='#betModal' teamItem={$teamItem} betItem='讓分: {$row['讓分']}' betOdds={$row['讓分賠率']} GameID={$row['Game_ID']}>" . $row["讓分賠率"] . "</button></td>";
                          if ($row["不讓分賠率"]) echo "<td scope='col' class='bet-noTBorder'>
                              <button class='notshow' data-bs-toggle='modal' data-bs-target='#betModal' teamItem={$teamItem} betItem='不讓分:' betOdds={$row['不讓分賠率']} GameID={$row['Game_ID']}>" . $row["不讓分賠率"] . "</button></td>";
                          else echo "<td scope='col' class='bet-noTBorder'>" . $row["不讓分賠率"] . "</td>";
                          echo "<td scope='col' class='bet-noTBorder'>
                              <button class='notshow' data-bs-toggle='modal' data-bs-target='#betModal' teamItem={$teamItem} betItem='大小分:' betOdds={$row['大小分賠率']} GameID={$row['Game_ID']}>" . $row["大小分賠率"] . "</button></td>";
                          echo "<td scope='col' class='bet-noTBorder'>
                              <button class='notshow' data-bs-toggle='modal' data-bs-target='#betModal' teamItem={$teamItem} betItem='單雙:' betOdds={$row['單雙賠率']} GameID={$row['Game_ID']}>" . $row["單雙賠率"] . "</button></td>";
                          echo "</tr>";
                          if(isset($_COOKIE['time'])){
                            $nowtime = date_create($_COOKIE['time']);
                          }
                          else{
                            $nowtime = date_create("2022-04-01 00:00:00");
                          }
                          
                          $tmp = explode(" ", $row['時間']);
                          $time = $tmp[1] . " " . $tmp[0];
                          $newtime = $dateshow . " " . $time;
                          $gametime = date_create($dateshow . " " . $time);
                          $diff = date_diff($gametime, $nowtime)->format("%R");
                          if ($diff === '+') {
                            echo "<script>
                        var elements = document.getElementsByClassName('notshow');
                        for (var i = 0; i < elements.length; i++) {
                            elements[i].disabled = true;
                        }
                        </script>";
                          }
                        }
                        if ($flag == 0) $flag = 1;
                        else $flag = 0;
                      }
                    }
                    else echo"<td scope='col' class='bet-noTBorder'>今日無比賽</tr>";
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="modal" id="betModal" tabindex="-1" aria-labelledby="betModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <p class="modal-title" id="betModalLabel">下注選項: </p>
          <div id="bettext"></div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">

              <div id="remaining"></div>
              <div class='col-12'>
                <label for="bet_amount">下注金額:</label>
                <input id="bet_amount" type="number" min=0 value=100>
                <button type='button' class='btn-bet' onclick='setbetMount(0)'>清空</button>
              </div>

              <div id="win_amount"></div>
              <input type="hidden" id="user_id" value=<?php echo $user_id ?>>
              <input type="hidden" id="Remaining" value=<?php echo $Remaining ?>>

            </div>
            <div>
              <label>快速下注: </label>
              <button type='button' class='btn-bet' onclick='setbetMount(100)'>+100</button>
              <button type='button' class='btn-bet' onclick='setbetMount(200)'>+200</button>
              <button type='button' class='btn-bet' onclick='setbetMount(500)'>+500</button>
              <button type='button' class='btn-bet' onclick='setbetMount(1000)'>+1000</button>
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type='button' class='btn btn-secondary' onclick='set_bet(0)' data-bs-dismiss='modal' id="confirm"> 確定 </button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    var modal = document.getElementById('betModal')
    var teamItem = ""
    var betOdds = ""
    var betItem = ""
    var GameID = ""
    var betMount = ""
    var user_id = ""
    var Remaining = ""
    var NumOdd
    modal.addEventListener('show.bs.modal', function(event) {

      // Button that triggered the modal
      var button = event.relatedTarget
      // Extract info from data-bs-* attributes
      betItem = button.getAttribute('betItem')
      betOdds = button.getAttribute('betOdds')
      teamItem = button.getAttribute('teamItem')
      GameID = button.getAttribute('GameID')
      user_id = document.getElementById('user_id').value
      Remaining = document.getElementById('Remaining').value
      // Update the modal's content.
      var betText = document.getElementById('bettext')
      betText.innerHTML = teamItem + " " + betItem + " " + betOdds
      document.getElementById('bet_amount').value = 100
      calWinMount()
    })
    //丟給php的函式(改網址)
    function set_bet(f) {
      var betMount = document.getElementById('bet_amount').value
      betItem = betItem.split(":")[0]
      location.href = 'models/set_bet.php?bet_mount=' + betMount + '&teamItem=' + teamItem + '&GameID=' + GameID +
        '&betOdds=' + betOdds + '&betItem=' + betItem + '&user_id=' + user_id + '&Remaining=' + Remaining+'&f='+f
    }
    //算可贏得多少並輸出在網頁上的
    function calWinMount() {
      var winText = document.getElementById('win_amount');
      var remainingText = document.getElementById('remaining');
      var nowMount = document.getElementById('bet_amount').value;
      var button = document.getElementById('confirm')

      if (betItem !== "不讓分:") NumOdd = Number(betOdds.substr(1));
      else NumOdd = Number(betOdds);
      winText.innerHTML = "最多可贏得: " + Number(nowMount) * NumOdd + " 元"
      remainingText.innerHTML = "帳戶餘額: " + Number(Remaining) + " 元"

      if (Number(Remaining) < Number(nowMount)) button.disabled = true
      else button.disabled = false
    }
    //按鈕下注
    function setbetMount(mount) {
      document.getElementById('bet_amount').value = Number(document.getElementById('bet_amount').value) + mount
      calWinMount()
    }
    //輸入的監聽事件
    var ele = document.getElementById('bet_amount')
    ele.addEventListener('input', function() {
      calWinMount()
    });
  </script>

</body>

</html>

<style>
  td.bet-noTDBorder {
    border-top: none;
    border-bottom: 0px;
  }

  td.bet-noTBorder {
    border-top: none;
  }

  .btn-bet {
    border: 1px solid;
    border-radius: 20px;
  }

  .bet button {
    border: none;
    background-color: lightgray;
  }

  .bet button:hover {
    border: none;
    background-color: gainsboro;
  }

  #main0 {
    background-color: gray;
  }

  #date {
    background-color: lightgray;
    border: none;
  }
  .alert {
    margin-left: auto;
    margin-right: auto;
    position: absolute;
    top: 5%;
    left: 50%;
    float: right;
    clear: right;
    transform: translate(-50%, 0%);
    background-color: #DA4453;
    color: white;
    z-index: 10;
}
</style>