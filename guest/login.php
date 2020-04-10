<?php

$into = '<input type="text" placeholder="Логин" id="log">
<input type="password" placeholder="Пароль" id="password">
<input type="text" placeholder="'.captcha().'" id="captcha">
<input type="submit" onclick="post_query(`gform`, `login`, `log.password.captcha`)" value="ВОЙТИ">
<input type="submit" onclick="go(`register`)" value="РЕГИСТРАЦИЯ">
<input type="submit" onclick="go(`recovery`)" value="Восстановление пароля">';
main ('Авторизация',$into,'');?>





