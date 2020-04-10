<?php

if ($_POST['edit_f']) {
        
    if ($_POST['log']) {
        login_valid();
        if ($_POST['log'] == $_SESSION['login']) message ('Логин совпадает с действующим');
    }

    if ($_POST['password']) {
        password_valid();
        if ($_POST['password'] == $_SESSION['password']) message ('Пароль совпадает с действующим');
        if ($_POST['password'] != md5($_POST['password_2'])) message('Повторный пароль не совпадает');
    }

    if ($_POST['email']) {
        email_valid();
        if ($_POST['email'] == $_SESSION['email']) message ('Email совпадает с действующим');
    }

    if ($_POST['log']) {
        mysqli_query ($CONNECT, 'UPDATE `users` SET `login` = "'.$_POST[log].'" WHERE `id` = "'.$_SESSION[id].'"');
        $_SESSION['login'] = $_POST['log'];
        $_SESSION['change'] = 1;
    }

    if ( $_POST['password'] ) {
        mysqli_query($CONNECT, 'UPDATE `users` SET `password` = "'.$_POST['password'].'" WHERE `id` = "'.$_SESSION[id].'"');
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['change'] = 1;
    }

    if ($_POST['email']) {
        mysqli_query($CONNECT, 'UPDATE `users` SET `email` = "'.$_POST[email].'" WHERE `id` = "'.$_SESSION[id].'"');
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['change'] = 1;
    }

    if ($_SESSION['change'] == 1) mess_go ('Изменения сохранены', 'profile');
    
}

?>
