<?php
session_start();
setcookie('msg', '', time() - 3600);
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/functions.php";
$conn = db_check();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	//something was posted
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password_cfm = $_POST['password_cfm'];
	if ($password != $password_cfm) {
		setcookie('msg', 'pwdwrong', time() + 3600);
	} else {
		$query = "SELECT * FROM users WHERE Username = '$username'";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) { //有這個使用者
			setcookie('msg', 'repeatuser', time() + 3600);
		}
		//save to database
		else {
			$user_id = random_num(20, $conn); //automatic generated
			$query = "INSERT INTO users (User_ID,Username,Password) values ('$user_id','$username','$password')";
			mysqli_query($conn, $query);
			setcookie('msg', 'signupsuccess', time() + 3600);
			header("Location: /final/login/login.php");
			die;
		}
	}
}

?>


<!DOCTYPE html>
<html>

<head>
<title>WIN88 | 用戶註冊</title>
	<?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php"; ?>
	<link rel="stylesheet" href="/final/css/login_style.css">
	<script src="/final/models/message.js"></script>
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
				<label for="login__password">確認密碼</label>
				<input id="login__password_cfm" type="password" name="password_cfm" class="form__input" placeholder="Confirm Password" required>
			</div>

			<div class="form__field">
				<input type="submit" value="註冊">
			</div>

		</form>

		<p style="text-align: center;">已是會員嗎? <a href="/final/login/login.php">點此登入</a></p>

	</div>


</body>

</html>