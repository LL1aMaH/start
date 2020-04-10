<?php

$soc_net = 'vk';

function error ($text, $url) {
    echo "<script type='text/javascript'>alert('$text');
    window.location.href='/' + '$url';</script>";
}



if (!$_GET['code']) error ('Ошибка авторизации', 'login');

$token = json_decode(file_get_contents ('https://oauth.vk.com/access_token?client_id='.ID_VK.'&redirect_uri='.URL_VK.'&client_secret='.SECRET_VK.'&code='.$_GET['code']), true);

if (!$token) error ('Ошибка авторизации', 'login');

$data = json_decode(file_get_contents ('https://api.vk.com/method/users.get?user_id='.$token['user_id'].'&access_token='.$token['access_token'].'&v=5.52&fields=uid,first_name,last_name,photo_big'), true);

$data = $data['response'][0];

$data['name'] = $data['first_name'] .' '. $data['last_name'];

if (!$data) error ('Ошибка авторизации', 'login');



if (mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `login` = '$data[name]'" )))
    error ('Данный логин уже используется другой учетной записью', 'login');


if (mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `soc_net` = '$soc_net' AND `id_soc_net` = '$data[id]'" ))) {

    $row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `email` = '$token[email]'"));

    foreach ($row as $k => $v) 
        $_SESSION[$k] = $v;  

    header('Location: /profile');

}


elseif (mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `email` = '$token[email]'" ))) {
    
    mysqli_query($CONNECT, "UPDATE `users` SET `soc_net` = '$soc_net' WHERE `email` = '$token[email]'");
    mysqli_query($CONNECT, "UPDATE `users` SET `id_soc_net` = '$data[id]' WHERE `email` = '$token[email]'");
    $row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `email` = '$token[email]'"));

    foreach ($row as $k => $v) 
        $_SESSION[$k] = $v;  

    header('Location: /profile');
}


else {

$Pass = random_str(7);

mysqli_query($CONNECT, 'INSERT INTO `users` VALUES ("","'.$data['name'].'","'.md5($Pass).'","'.$token['email'].'","'.$data['id'].'","'.$soc_net.'","'.$data['photo_big'].'")');

send_mail($token['email'], 'Регистрация' ,"Вы зарегистрированы. Ваш логин: $data[name], пароль: $Pass");

$row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `soc_net` = '$soc_net' AND `id_soc_net` = '$data[id]'"));

foreach ($row as $k => $v) 
    $_SESSION[$k] = $v;  

header('Location: /profile');
}
?>