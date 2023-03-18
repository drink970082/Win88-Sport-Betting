<?php
session_start();

if(isset($_COOKIE['msg']))
{
    if($_COOKIE['msg']!=='signupsuccess')
        setcookie('msg','',time()-3600);
}
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/functions.php";
$conn = db_check();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //something was posted
    $username = $_POST['username'];
    $password = $_POST['password'];

    //read from database
    $query = "SELECT * FROM users WHERE Username = '$username' limit 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) { //有這個使用者
        $user_data = mysqli_fetch_assoc($result);
        if ($user_data['Password'] === $password) {
            $_SESSION['user_id'] = $user_data['User_ID'];
            $_SESSION['username'] = $user_data['Username'];
            setcookie('msg','',time()-3600);
            header("Location: /final/index.php");
            die;
        } else {
            setcookie('msg','wrong_pwd',time()+3600);
        }
    } else {
        setcookie('msg','no_user',time()+3600);
    }
}


?>


<!DOCTYPE html>
<html>

<head>
<title>WIN88 | 用戶登入</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php"; ?>
    <link rel="stylesheet" href="/final/css/login_style.css">
</head>

<body class="align">

    <div class="grid">
        <div id="ohsnap"></div>
        <img src="/final/img/logo.png">
        <form method="POST" class="form login">

            <div class="form__field">
                <label for="login__username">會員帳號</label>
                <input autocomplete="username" id="login__username" type="text" name="username" class="form__input" placeholder="Username" required>
            </div>

            <div class="form__field">
                <label for="login__password">會員密碼</label>
                <input id="login__password" type="password" name="password" class="form__input" placeholder="Password" required>
            </div>

            <div class="form__field">
                <input type="submit" value="登入">
            </div>

        </form>

        <p style="text-align: center;">不是會員嗎? <a href="/final/login/signup.php">點此註冊</a></p>

    </div>


</body>
</html>