<?php
session_start();
setcookie('msg', '', time() - 3600);
$id = $_SESSION["user_id"];/* userid of the user */
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/functions.php";
$conn = db_check();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST["newPassword"] !== $_POST["currentPassword"]) {
        if ($_POST["newPassword"] == $_POST["confirmPassword"]) {
            $result = mysqli_query($conn, "SELECT * FROM users WHERE User_ID='$id'");
            $row = mysqli_fetch_array($result);
            if ($_POST["currentPassword"] == $row["Password"]) {
                mysqli_query($conn, "UPDATE users set Password='{$_POST["newPassword"]}' WHERE User_ID='$id'");
                setcookie('msg', 'change_succ', time() + 3600);
                header("Location: /final/index.php");
            } else {
                setcookie('msg', 'wrong_pwd', time() + 3600);
            }
        } else {
            setcookie('msg', 'checkerr', time() + 3600);
        }
    } else {
        setcookie('msg', 'repeatpwd', time() + 3600);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
<title>WIN88 | 修改密碼</title>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/head.php"; ?>
    <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/nav.php"; ?>
    <link href="/final/css/cpwd.css" rel="stylesheet">
</head>

<div class="table-responsive container">
    <div id="ohsnap"></div>
    <div class="row ">
        <div class="col-10 offset-1">
            <div class="row ">
                <?php require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/include/left.php"; ?>
                <div class="col-10" id="main0">
                    <!--最下層-->
                    <div class="row">
                        <div class="col-12" id="main1">
                            <!--第2下層-->
                            <!--<div class="row">
                                    <div class="col-12" id="main2"></!--最上層-->

                            <form method="post" action="" $align="center" class="vertical-center">
                                <h4 id="text1">您在 WIN88 服務中的會員資料資訊</h4>
                                <div class="row g-3 align-items-center d-flex justify-content-center">
                                    <div class="col-auto">
                                        <label for="inputPassword6" class="col-form-label">目前密碼: </label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="password" name="currentPassword" id="nowPwd" class="form-control " aria-describedby="passwordHelpInline" required>
                                    </div>
                                </div>

                                <div class="row g-3 align-items-center d-flex justify-content-center">
                                    <div class="col-auto">
                                        <label for="inputPassword6" class="col-form-label">新的密碼: </label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="password" name="newPassword" id="newPwd" class="form-control " aria-describedby="passwordHelpInline" required>
                                    </div>
                                </div>

                                <div class="row g-3 align-items-center d-flex justify-content-center">
                                    <div class="col-auto">
                                        <label for="inputPassword6" class="col-form-label">確認密碼: </label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="password" name="confirmPassword" id="cfnPwd" class="form-control " aria-describedby="passwordHelpInline" required>
                                    </div>
                                </div>
                                <div>
                                    <div class="row" id="btn1">
                                        <!--btn1 main1內的按鈕-->
                                        <div class="col-4 offset-2">
                                            <button type="submit" value="submit" class="btn btn-dark">更新會員資料</button>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-dark" onclick="clearAll()">清空</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function clearAll() {
        document.getElementById('nowPwd').value = ''
        document.getElementById('newPwd').value = ''
        document.getElementById('cfnPwd').value = ''
    }
</script>
</body>
<style>
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
</html>