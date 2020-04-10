<?php
$into = '<input type="text" placeholder="Логин" id="log">
<input type="text" placeholder="'.captcha().'" id="captcha">
<input type="submit" onclick="post_query(`gform`, `recovery`, `log.captcha`)" value="Восстановить пароль">';
$data = 1;
main ('Восстановление пароля',$into, $data);
?>

