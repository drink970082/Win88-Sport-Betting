<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]) ."/final/models/db_check.php";
$conn = db_check();
$betMount = (float)htmlspecialchars($_GET["bet_mount"]);
$teamItem = htmlspecialchars($_GET["teamItem"]);
$betOdds = htmlspecialchars($_GET["betOdds"]);
$GameID = htmlspecialchars($_GET["GameID"]);
$betItem = htmlspecialchars($_GET["betItem"]);
$Remaining = (float)htmlspecialchars($_GET["Remaining"]);
$user_id = htmlspecialchars($_GET["user_id"]);
$Total=htmlspecialchars($_GET["Total"]);
$f=htmlspecialchars($_GET["f"]);

$sql = "SELECT * FROM result WHERE Game_ID='$GameID'";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if ($betItem == "不讓分") {
        $realOdds = $betOdds;
        $realOdds=(float)$realOdds;
    } else {
        $realOdds = substr($betOdds, 3, strlen($betOdds));
        $first=substr($betOdds, 0, 3);
        $realOdds=(float)$realOdds;
    }
    $win = $row[$betItem];
    $cash_flow = $realOdds * $betMount - $betMount;
    if (($betItem == "不讓分"||$betItem == "讓分")&&$win == 0) {
        $cash_flow = 0 - $betMount;
    }
    else if ($betItem == "大小分"&&(($first=="小"&&$win==1)||($first=="大"&&$win==0)))
    {
        $cash_flow = 0 - $betMount;
    }
    else if ($betItem == "單雙"&&(($first=="雙"&&$win==1)||($first=="單"&&$win==0)))
    {
        $cash_flow = 0 - $betMount;
    }
    $sql = "INSERT INTO history(User_ID, Game_ID, Game, Bet_Item, Bet_Odds, Money_Bet, Total_Cash_Flow) VALUES 
    ('$user_id', '$GameID', '$teamItem', '$betItem', '$betOdds', '$betMount','$cash_flow')";
    mysqli_query($conn, $sql);
    $d_query = "UPDATE users SET Remaining=(Remaining-'$betMount') WHERE User_ID='$user_id'";
    mysqli_query($conn, $d_query);
    $Remaining = $Remaining - $betMount;  
        
    echo"<script>Cookies.set('msg','bet_succ')</script>";
    //setcookie('msg','bet_succ',time()+3600);
    if($f)header("Location: /final/leaderboard.php");
    else header("Location: /final/index.php");
}