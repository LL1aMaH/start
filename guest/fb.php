<?php 

$soc_net = 'fb';

function error ($text, $url) {
    echo "<script type='text/javascript'>alert('$text');
    window.location.href='/' + '$url';</script>";
}

if (!$_GET['code']) error ('Ошибка авторизации', 'login');

$token = json_decode(file_get_contents ('https://graph.facebook.com/v3.3/oauth/access_token?client_id='.ID_F.'&redirect_uri='.URL_F.'&client_secret='.SECRET_F.'&code='.$_GET['code']), true);

if (!$token) error ('Ошибка авторизации', 'login');

$data = json_decode(file_get_contents ('https://graph.facebook.com/v3.3/me?client_id='.ID_F.'&redirect_uri='.URL_F.'&client_secret='.SECRET_F.'&code='.$_GET['code'].'&access_token='.$token['access_token'].'&fields=id,name,email'), true);

if (!$data) error ('Ошибка авторизации', 'login');

$data['ava'] = 'https://graph.facebook.com/v3.3/'.$data['id'].'/pictire?width=200&height=200';




if (mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `login` = '$data[name]'" )))
    error ('Данный логин уже используется другой учетной записью', 'login');


if (mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `soc_net` = '$soc_net' AND `id_soc_net` = '$data[id]'" ))) {

    $row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `email` = '$data[email]'"));

    foreach ($row as $k => $v) 
        $_SESSION[$k] = $v;  

    header('Location: /profile');

}


elseif (mysqli_num_rows (mysqli_query($CONNECT, "SELECT `id` FROM `users` WHERE `email` = '$data[email]'" ))) {
    
    mysqli_query($CONNECT, "UPDATE `users` SET `soc_net` = '$soc_net' WHERE `email` = '$data[email]'");
    mysqli_query($CONNECT, "UPDATE `users` SET `id_soc_net` = '$data[id]' WHERE `email` = '$data[email]'");
    $row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `email` = '$data[email]'"));

    foreach ($row as $k => $v) 
        $_SESSION[$k] = $v;  

    header('Location: /profile');
}


else {

$Pass = random_str(7);

mysqli_query($CONNECT, 'INSERT INTO `users` VALUES ("","'.$data['name'].'","'.md5($Pass).'","'.$data['email'].'","'.$data['id'].'","'.$soc_net.'","'.$data['ava'].'")');

send_mail($data['email'], 'Регистрация' ,"Вы зарегистрированы. Ваш логин: $data[name], пароль: $Pass");

$row = mysqli_fetch_assoc (mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `soc_net` = '$soc_net' AND `id_soc_net` = '$data[id]'"));

foreach ($row as $k => $v) 
    $_SESSION[$k] = $v;  

header('Location: /profile');
}


?>