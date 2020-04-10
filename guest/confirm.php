<?php
if (!$_SESSION['confirm']['code']) not_found();

$into = '<input type="text" placeholder="Код" id="code">
<input type="submit" onclick="post_query(`gform`, `confirm`, `code`)" value="Подтвердить">';
$data = 2;
main ('Подтверждение',$into, $data);
 ?>

