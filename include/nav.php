<div class="container">
    <div class="row">
        <div class="col-md-10 offset-1">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/final/index.php"><img src="/final/img/logo.png" id="logo"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#"></a>
                            </li>
                        </ul>
                        <div class="row" style="height: 38px;">
                            <div class="col-3" >
                                <form method="post">
                                    <a href="/final/profile.php"><button type="button" class="btn btn-dark">帳戶儲值</button></a>
                                </form>
                            </div>
                            <div class="col-3" >
                                <form method="post">
                                    <a href="/final/bet_history.php"><button type="button" class="btn btn-dark">下注歷史</button></a>
                                </form>
                            </div>
                            <div class="col-3">
                                <form method="post">
                                    <a href="/final/leaderboard.php"><button type="button" class="btn btn-dark">高手下注</button></a>
                                </form>
                            </div>
                            <div class="dropdown col-3">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    帳號設定
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="/final/login/change_password.php">修改密碼</a></li>
                                    <li><a class="dropdown-item" href="/final/login/logout.php">登出</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>

<style>
    body {
    background: url(/final/img/background_main.jpg);
    background-size: cover;
    background-repeat: no-repeat;
    min-height: 100vh;
}

.container .row .col-md-10 {
    /*nav*/
    padding: 0px;
}

.col-10 .row .col-2 {
    /*留言區*/
    background-color: black;
    min-height: 90vh;
}

#main0 {
    /*main*/
    background-color: gray;
    min-height: 90vh;
}

#logo {
    width: 100%;
    padding: 0px;
    height: 10vh;
}

.navbar {
    padding: 0px;
}

.navbar-brand {
    padding: 0px;
}

#navbarSupportedContent .row .col-3 {
    white-space: nowrap;
}

.btn {
    background-color: black;
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
    z-index: 1;
}
</style>