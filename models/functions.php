<?php

function check_login($conn)
{
	if (isset($_SESSION['user_id'])) {
		$query = "SELECT * FROM users WHERE User_ID = '{$_SESSION['user_id']}' limit 1";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}
	//redirect to login
	header("Location: /final/login/login.php");
	die;
}

function random_num($length, $conn)
{
	while (true) {
		$text = "";
		if ($length < 5) {
			$length = 5;
		}
		$len = rand(4, $length);
		for ($i = 0; $i < $len; $i++) {
			$text .= rand(0, 9);
		}
		$query = "SELECT * FROM users WHERE User_ID = '$text' limit 1";
		$result = mysqli_query($conn, $query);
		echo $text;
		echo mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 0) { //有這個使用者
			return $text;
		}
	}
}
function function_alert($message)
{
	// Display the alert box 
	echo "<script>alert('$message');</script>";
}
