<?php

include 'guest/config.php';

if ( $_SERVER['REQUEST_URI'] == '/' ) $page = 'login';
else {
	$page = substr($_SERVER['REQUEST_URI'], 1);
	$page_soc = substr($page, 0, 6);
}

// $CONNECT = mysqli_connect('localhost', 'chatepam', 'eI6U71cuf3', 'chatepam');
// if ( !$CONNECT ) exit('MySQL error');

session_start();

if ( $_SESSION['id'] and file_exists('auth/'.$page.'.php') ) include 'auth/'.$page.'.php';
else if ( !$_SESSION['id'] and file_exists('guest/'.$page.'.php') ) include 'guest/'.$page.'.php';
else if ( !$_SESSION['id'] and file_exists('guest/'.$page_soc)) include 'guest/'.$page_soc;
else not_found();

function not_found() {
	exit('Страница 404');
}

function message ($text) {
	exit('{ "message" : "'.$text.'"}'); 
}

function mess_go ($text, $url) {
	exit ('{ "go" : "'.$url.'" , "message" : "'.$text.'" }'); 
}

function go($url) {
	exit('{ "go" : "'.$url.'"}');
}

function random_str( $num = 30 ) {
	return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $num);
}

function email_valid() {
	if ( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL))
		message('E-mail указан неверно');
}

function login_valid() {
	if ( !$_POST['log']) 
		message('Укажите логин');
}

function password_valid() {
	if ( !preg_match('/^[A-z0-9]{7,30}$/', $_POST['password']) )
		message('Пароль должен содеражть 7 - 30 латинских символов или цифр');
	$_POST['password'] = md5($_POST['password']);
}

function captcha() {
	$questions = array (
		1 => 'Cтолица Австралии ?',
		2 => 'Cтолица Китая ?',
		3 => 'Хотите работать в EPAM ? (да/нет)',
		4 => 'Chrome или Яндекс.Браузер ?',
		5 => 'Telegram или Одноклассники ?', 
	);

	$num = mt_rand(1, count($questions));
	$_SESSION['captcha'] = $num;
	return $questions[$num];
} 

function send_mail($email, $title, $text) {
	mail($email, $title, '<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>'.$title.'</title>
	</head>
	<body style="margin:0">
	<div style="margin:0; padding:0; font-size: 18px; font-family: Arial, sans-serif; font-weight: bold; text-align: center; background: #FCFCFD">
	<div style="margin:0; background: #464E78; padding: 25px; color:#fff">'.$title.'</div>
	<div style="padding:30px;">
	<div style="background: #fff; border-radius: 10px; padding: 25px; border: 1px solid #EEEFF2">'.$text.'</div>
	</div>
	</div>
	</body>
	</html>', "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8");
	}
	
function captcha_valid() {
	$answers = array (
		1 => 'канберра',
		2 => 'пекин',
		3 => 'да',
		4 => 'chrome',
		5 => 'telegram', 
	);
	
	if ( $_SESSION['captcha'] != array_search( mb_strtolower ($_POST['captcha'], 'UTF-8'), $answers) )
		message('Неверный ответ');
}

function main ($title , $into, $data) {
    echo '<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="yandex-verification" content="7fa574774c6ab0e9" />
	<title>'.$title.'</title>
	<link rel="stylesheet" href="style.css">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="
  		crossorigin="anonymous">
	</script>
	<script src="/script.js"></script>  
</head>
<body>';
if ( !$_SESSION['id']) echo '
	<div class="main-signin">
		<div class="main-signin__head">
			<p>'.$title.'</p>
		</div>
		<div class="main-signin__middle">
			<div class="middle__form">'
				 .$into.	
			'</div>
		</div>';
		if ($data == 0) echo '
		<div class="main-signin__foot">
			<div class="foot__left">
				<p>Войти через:</p>
			</div>
			<div class="foot__right">
				<div class="vk"><a href="https://oauth.vk.com/authorize?client_id='.ID_VK.'&display=page&redirect_uri='.URL_VK.'&scope=email&response_type=code"></a></div>
				<div class="face"><a href="https://www.facebook.com/v3.3/dialog/oauth?client_id='.ID_F.'&redirect_uri='.URL_F.'"></a></div>
			</div>;';
		if ($data == 1) echo '
		<div class="main-signin__foot">
			<div class="foot__center">
				<p>Пароль будет оправлен на ваш Email.</p>
			</div>
		</div>';
		if ($data == 2) echo '
			<div class="main-signin__foot">
			</div>
	</div>';

if ( $_SESSION['id']) echo '
	<div class="menu">
	<a href="/profile">'.$_SESSION['login'].'</a>
		<div class="menu_input">
			<input type="submit" onclick="go(`logout`)" value="Выход">
		</div>
		<div class="menu_input">
			'.$into.'
		</div>
	</div>';
	if ($into == '') {
		echo'
		<script src="auth/chatJS.js"></script>
		<div class="chat">
		<div class="roof"></div>
		<div class="messages"></div>
		<div class="box" id="box"></div>
		<textarea id="writemessage" placeholder="Введите сообщение"></textarea>
		<div class="chat_sub">
		<input type="submit"  value="Картинка">
		<input type="submit" onclick="" value="Видео">
		<input type="submit" onclick="" value="Отправить сообщение">
		</div>
		</div>';
	}
'</body>
</html>';}
?>


