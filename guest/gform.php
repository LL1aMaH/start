<?php

if ($_POST['login_f']) {
    captcha_valid();
    login_valid();
    password_valid();

    if (!mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `login` = '$_POST[log]'" )))
    message ('Аккаунт не найден');

    $row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `login` = '$_POST[log]'"));

    if ( $row['password'] != $_POST['password']) 
        message ('Неверный пароль');

    foreach ($row as $k => $v) 
        $_SESSION[$k] = $v;  

    go ('profile');
}

else if ($_POST['register_f']) {
    captcha_valid();
    password_valid();
    login_valid();
    email_valid();

    if (mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `email` = '$_POST[email]'" )))
    message ('Данный email уже используется');

    if (mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `login` = '$_POST[log]'" )))
    message ('Данный логин уже используется');

    if ($_POST['password'] != md5($_POST['password_2']))
        message('Повторный пароль не совпадает');
        
    $code = random_str(10);

    $_SESSION['confirm'] = array(
        'type' => 'register',
        'login' => $_POST['log'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'password_2' => $_POST['password_2'],
        'code' => $code,
    );
    
    send_mail( $_POST['email'], 'Регистрация' ,"Код подтверждения: $code" );

    go ('confirm');
}

else if ($_POST['recovery_f']) {
    captcha_valid();
    login_valid();

    if (!mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `login` = '$_POST[log]'" )))
    message ('Такой логин не найден');

    $row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `login` = '$_POST[log]'"));  

    $newPass = random_str(7);

    mysqli_query($CONNECT, 'UPDATE `users` SET `password` = "'.md5($newPass).'" WHERE `login` = "'.$_POST[log].'"');

    send_mail($row['email'], 'Восстановление пароля' ,"Новый пароль: $newPass");

    go ('login');
}

else if ($_POST['confirm_f']) {

    if ($_SESSION['confirm']['type'] == 'register') {
        
        if ($_SESSION['confirm']['code'] != $_POST['code']) message ('Неверный код');

        $login = $_SESSION['confirm']['login'];

        send_mail($_SESSION['confirm']['email'], 'Регистрация' ,"Вы зарегистрированы. Ваш логин: $login");

        mysqli_query($CONNECT, 'INSERT INTO `users` VALUES ("","'.$_SESSION['confirm']['login'].'","'.$_SESSION['confirm']['password'].'","'.$_SESSION['confirm']['email'].'","","","")');

        $row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `login` = '$login'"));

        foreach ($row as $k => $v) 
            $_SESSION[$k] = $v;  

        unset($_SESSION['confirm']);
    
        go('profile');
    };
     
 }

?>