<?php
session_start();
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/functions.php";
$conn = db_check();
$user_data = check_login($conn);
$user_id = $user_data['User_ID'];
$Remaining = $user_data['Remaining'];
$time = strtotime($_COOKIE['time']);
$dateshow = date('Y-m-d', $time);
$nowtime = date_create($_COOKIE['time']);
$date2 = date_create($dateshow . " 17:00");
$diff = date_diff($nowtime, $date2)->format("%R");
// 2022/06/12 撈出贏錢最多前三名
$sql = "
  SELECT 
  hs.User_ID,
  us.Username,
  us.Winrate,
  count(IF(Pay>0,true,NULL)) 'game_times',
  count(IF(hs.Total_Cash_Flow>0 AND Pay>0,true,NULL)) 'win',
  count(IF(hs.Total_Cash_Flow<0 AND Pay>0,true,NULL)) 'lose'
  FROM history hs
  LEFT JOIN users us
  ON us.User_ID = hs.User_ID
  GROUP BY hs.User_ID
  ORDER BY win DESC 
  LIMIT 3
";

$data_arr = [];
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
  // 整理資料
  $i = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    // 前三名的資料
    $data_arr[$i] = $row;
    // 下注詳細內容
    $sql2 = "
      SELECT hs.*,sp.日期 'game_date' FROM history hs
      LEFT JOIN sport sp
      ON sp.Game_ID = hs.Game_ID
      WHERE hs.User_ID = '" . $row['User_ID'] . "' 
      ORDER BY History_ID DESC limit 5
    ";
    $result_2 = mysqli_query($conn, $sql2);
    while ($row2 = mysqli_fetch_assoc($result_2)) {
      // 整理到陣列
      $data_arr[$i]['game_detail'][] = $row2;
    }
    $i++;
  }
}

?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width" />
<meta charset="UTF-8">

<head>
  <title>WIN88 | 高手下注</title>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php"; ?>
  <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/nav.php"; ?>
  <link href="/final/css/cpwd.css" rel="stylesheet">
</head>

<body>
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
          <button type='button' class='btn btn-secondary' onclick='set_bet()' data-bs-dismiss='modal' id="confirm"> 確定 </button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>
  <div class="table-responsive container">
    <div id="ohsnap"></div>
    <div class="row ">
      <div class="col-10 offset-1">
        <div class="row ">
          <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/left.php"; ?>
          <div class="col-10" id="main0">
            <!--最下層-->
            <div class="row">
              <div class="col-12" id="main1" style="overflow-y:scroll; height:80vh;">
                <form method="post" action="" $align="center" class="vertical-center"></form>
                <h4 id="text1">NBA季後賽 找高手</h4>
                <?php foreach ($data_arr as $value) {
                  if ($value['User_ID'] != $user_id) { ?>

                    <table class="table table-bordered">
                      <tr>
                        <td class="w-25 text-center align-middle bg-white">
                          <div>
                            <?php echo $value['Username']; ?>
                            <br>
                            <div class="mt-3">勝率</div>
                            <div>
                              <?php echo round(($value['Winrate'])); ?>%
                            </div>
                          </div>
                        </td>
                        <td class="w-75 p-0 bg-white">
                          <table class="table table-striped">
                            <tr>
                              <td class="bg-white" colspan="6" style="border-bottom: 3px solid #6b5c00;">
                                玩<?php echo $value['game_times']; ?>場,過<?php echo $value['win']; ?>
                              </td>
                            </tr>
                            <?php
                            $time = strtotime($_COOKIE['time']);
                            $dateshow = date('Y-m-d', $time);
                            $nowtime = date_create($_COOKIE['time']);
                            $date2 = date_create($dateshow . " 17:00");
                            $diff = date_diff($nowtime, $date2)->format("%R");
                            if ($diff === '-') {
                              $dateshow = date_create($dateshow);
                              date_modify($dateshow, "+1 days");
                              $dateshow = date_format($dateshow, "Y-m-d");
                            } ?>
                            <?php foreach ($value['game_detail'] as $game_detail) { ?>
                              <tr style="font-size: 14px;">
                                <td><?php echo $game_detail['game_date']; ?></td>
                                <td><?php echo $game_detail['Game']; ?></td>
                                <td><?php echo $game_detail['Bet_Item']; ?></td>
                                <td><?php echo $game_detail['Bet_Odds']; ?></td>
                                <td>
                                  <?php if ($game_detail['game_date'] < $dateshow) {
                                    if ($game_detail['Total_Cash_Flow'] > 0) { ?>
                                      <div class="rounded-circle bg-success p-1 text-center text-white">準</div>
                                    <?php } else { ?>
                                      <div class="rounded-circle bg-secondary p-1 text-center text-white">冏</div>
                                    <?php }
                                  } else { ?>
                                    <div class="rounded-circle bg-info p-1 text-center text-white">?</div>
                                  <?php } ?>
                                </td>
                                <td>
                                  <?php if ($game_detail['game_date'] >= $dateshow) { ?>
                                    <a href="javascript:void(0)" data-bs-toggle='modal' data-bs-target='#betModal' teamItem='<?php echo $game_detail['Game']; ?>' betItem='<?php echo $game_detail['Bet_Item']; ?>' betOdds='<?php echo $game_detail['Bet_Odds']; ?>' GameID='<?php echo $game_detail['Game_ID']; ?>'>跟注</a>
                                  <?php } else { ?>
                                    <p></p>
                                  <?php } ?>
                                </td>
                              </tr>
                            <?php } ?>
                          </table>
                        </td>
                      </tr>
                    </table>
                  <?php } ?>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>


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
        <button type='button' class='btn btn-secondary' onclick='set_bet(1)' data-bs-dismiss='modal' id="confirm"> 確定 </button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
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
    location.href = 'models/set_bet.php?bet_mount=' + betMount + '&teamItem=' + teamItem + '&GameID=' + GameID + '&betOdds=' + betOdds + '&betItem=' + betItem + '&user_id=' + user_id + '&Remaining=' + Remaining +'&f='+f
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
<style>
  .btn-bet {
    border: 1px solid;
    border-radius: 20px;
  }
</style>