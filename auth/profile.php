<?php
$into = '<input type="text" placeholder="Новый логин" id="log">
<input type="password" placeholder="Новый пароль" id="password">
<input type="password" placeholder="Введите новый пароль повторно" id="password_2">
<input type="email" placeholder="Изменить Email" id="email">
<input type="submit" onclick="post_query(`aform`, `edit`, `log.password.password_2.email`)" value="Внести изменения">
<a href="/chat">Вернуться в чат</a>';
$data = 2;
main ('Профиль',$into, $data);?>


