<?php
session_start();
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/functions.php";
$conn = db_check();
$user_data = check_login($conn);
$user_id = $user_data['User_ID'];
$Remaining = $user_data['Remaining'];
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width" />
<meta charset="UTF-8">

<head>
  <title>WIN88 | 下注歷史</title>
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
                <!--第2下層-->
                <table>
                  <tr class="border-bottom">
                    <th>比賽日期</th>
                    <th>下注場次</th>
                    <th>下注金額</th>
                    <th>下注項目</th>
                    <th>賠率</th>
                    <th>盈虧狀況</th>
                  </tr>
                  <?php
                  $total = 0;
                  $sql = "SELECT Game_ID, User_ID ,Game, Bet_Item, Bet_Odds, Money_Bet, Total_Cash_Flow, Pay FROM history WHERE User_ID='$user_id' ORDER BY Game_ID DESC";
                  $result = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    while ($row = mysqli_fetch_assoc($result)) {
                      $day_sql = "SELECT 日期 FROM sport WHERE Game_ID={$row["Game_ID"]}";
                      $gameday = mysqli_fetch_assoc(mysqli_query($conn, $day_sql));
                      echo "<tr>";
                      echo "<td>{$gameday["日期"]}</td>";
                      echo "<td>{$row["Game"]}</td>";
                      echo "<td>{$row["Money_Bet"]}</td>";
                      echo "<td>{$row["Bet_Item"]}</td>";
                      echo "<td>{$row["Bet_Odds"]}</td>";
                      $game_sql = "SELECT 日期 FROM sport WHERE Game_ID = '{$row["Game_ID"]}'";
                      $gametime = mysqli_fetch_assoc(mysqli_query($conn, $game_sql));
                      $time = strtotime($_COOKIE['time']);
                      $dateshow = date('Y-m-d', $time);
                      $nowtime = date_create($_COOKIE['time']);
                      $date2 = date_create($dateshow . " 17:00");
                      $diff = date_diff($nowtime, $date2)->format("%R");
                      if (($diff === '-' && $dateshow == $gametime['日期'])||$dateshow>$gametime['日期']) {
                        echo "<td>{$row["Total_Cash_Flow"]}</td>";
                        if (!$row['Pay']) {
                          if($row['Total_Cash_Flow']<0){
                            $re=0;
                          }
                          else{ 
                            $re=$row['Total_Cash_Flow']+$row['Money_Bet'];
                          }
                          $d_query = "UPDATE users SET Remaining=(Remaining+$re) WHERE User_ID='$user_id'";
                          mysqli_query($conn, $d_query);
                          $d_query = "UPDATE history SET Pay=1 WHERE Game_ID='{$row["Game_ID"]}'AND User_ID='$user_id'";
                          mysqli_query($conn, $d_query);
                          $win_sql = "SELECT count(*) 'game_times',count(IF(Total_Cash_Flow>0,true,NULL)) 'win'
                                  FROM history WHERE User_ID = '{$row["User_ID"]}'";
                          $win = mysqli_fetch_assoc(mysqli_query($conn, $win_sql));
                          $winrate = (100 * $win['win']) / $win['game_times'];
                          $d_query = "UPDATE users SET Winrate=$winrate WHERE User_ID='$user_id'";
                          mysqli_query($conn, $d_query);
                        }
                        $total = $total + $row['Total_Cash_Flow'];
                      } else echo "<td>未開獎</td>";
                      echo "</tr>";
                    }
                  }
                  /*
                  $user_sql = "SELECT User_ID From users";
                  $result = mysqli_query($conn, $user_sql);
                  if (mysqli_num_rows($result) > 0) {
                    while ($row1 = mysqli_fetch_assoc($result)) {
                      $history_sql = "SELECT * FROM history WHERE User_ID='{$row1['User_ID']}'AND Pay=0";
                      $his_result = mysqli_query($conn, $history_sql);
                      if (mysqli_num_rows($his_result) > 0) {
                        while ($row2 = mysqli_fetch_assoc($his_result)) {
                          $d_query = "UPDATE users SET Remaining=(Remaining+{$row2['Money_Bet']}+{$row2['Total_Cash_Flow']}) WHERE User_ID='{$row1['User_ID']}'";
                          mysqli_query($conn, $d_query);
                          $d_query = "UPDATE history SET Pay=1 WHERE Game_ID='{$row2["Game_ID"]}'AND User_ID='{$row1['User_ID']}'";
                          mysqli_query($conn, $d_query);
                          $win_sql = "SELECT count(*) 'game_times',count(IF(Total_Cash_Flow>0,true,NULL)) 'win'
                                FROM history WHERE User_ID = '{$row1["User_ID"]}'";
                          $win = mysqli_fetch_assoc(mysqli_query($conn, $win_sql));
                          $winrate = (100 * $win['win']) / $win['game_times'];
                          $d_query = "UPDATE users SET Winrate=$winrate WHERE User_ID='{$row1['User_ID']}'";
                          mysqli_query($conn, $d_query);
                        }
                        
                      }
                    }
                  }
*/
                  ?>
                </table>
                <br>
                <?php
                echo "<div class='col-12' style='text-align:right;'><p>" . "總收益：" . $total . " 元" . "</p></div>";
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>

<style>
  table {
    border-collapse: collapse;
    width: 100%;
    color: grey;
    font-family: monospace;
    font-size: 18px;
    text-align: left;
    margin-top: 10px;
    text-align: center;
    line-height: 40px;
  }

  th {
    color: rgb(14, 102, 131);
  }

  .border-bottom {
    border-bottom: 1px solid black !important;
  }
</style>