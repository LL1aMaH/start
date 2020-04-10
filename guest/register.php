<?php
$into = '<input type="text" placeholder="Логин" id="log">
<input type="password" placeholder="Пароль" id="password">
<input type="password" placeholder="Введите пароль повторно" id="password_2">
<input type="email" placeholder="Email" id="email">
<input type="text" placeholder="'.captcha().'" id="captcha">
<input type="submit" onclick="post_query(`gform`, `register`, `log.password.password_2.email.captcha`)" value="РЕГИСТРАЦИЯ">';
$data = 0;
main ('Регистрация',$into, $data);?>

