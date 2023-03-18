window.onload = function() {
    var msg = Cookies.get('msg')
    if (msg === 'no_user') {
        ohSnap('查無此使用者，請再次確認帳號', {
            'duration': '2000'
        });

    }
    if (msg === 'wrong_pwd') {
        ohSnap('密碼錯誤，請再次輸入', {
            'duration': '2000'
        });

    }
    if (msg === 'signupsuccess') {
        ohSnap('註冊成功，請重新登入', {
            'duration': '2000'
        });
    }
    if (msg === 'repeatuser') {
        ohSnap('此帳號已被註冊過，請輸入新帳號或嘗試登入', {
            'duration': '2000'
        });

    }
    if (msg === 'pwdwrong') {
        ohSnap('密碼驗證錯誤，請確認兩次輸入皆為相同', {
            'duration': '2000'
        });

    }
    if (msg === 'change_succ') {
        Swal.fire({
            icon: 'success',
            text: '修改密碼成功!',
        })

    }
    if (msg === 'checkerr') {
        Swal.fire({
            icon: 'error',
            text: '請檢查是否輸入相同的密碼',
        })

    }

    if (msg === 'repeatpwd') {
        Swal.fire({
            icon: 'error',
            text: '請勿輸入同樣的密碼',
        })

    }

    if (msg === 'bet_succ') {
        Swal.fire({
            icon: 'success',
            text: '下注完成!',
        })

    }
    $('body').one('click', function() {
        ohSnapX();
    });
}