<?php
require_once('../vendor/autoload.php');

if ($_REQUEST['logout']) {
    setcookie('user', '', time());
    header('Location: /');
}

function isUser ($currentUser) {
    $file = file('../db/user.txt');
    $user = [];
    foreach ($file as $elem) {
        $user[] = explode('@', $elem);
    }
    foreach ($user as $elem) {
        if ($elem[0] === $currentUser)
        return in_array($currentUser, $elem);
    }
}
function isCorrect ($currentUser, $password) {
    $file = file('../db/user.txt');
    $user = [];
    foreach ($file as $elem) {
        $user[] = explode('@', $elem);
    }
    foreach ($user as $elem) {
        if ($currentUser === $elem[0] && hash('md5', $password) . PHP_EOL === $elem[1]) {
            return true;
        }
    }

}

if ($_REQUEST['submit'] === 'enter') {
    if (isCorrect($_REQUEST['user'], $_REQUEST['password'])) {
        setcookie('user', $_REQUEST['user'], time()+60*60*24*30);
        header('Location: /');
    } else {
        echo "Введите корерктные данные";
    }
} elseif ($_REQUEST['submit'] === 'register') {
    if ($_REQUEST['user'] && $_REQUEST['password']){
        if (!isUser($_REQUEST['user'])) {
            $str = $_REQUEST['user'] . '@' . hash('md5' ,$_REQUEST['password']) . PHP_EOL;
            file_put_contents('../db/user.txt', $str, FILE_APPEND);
            setcookie('user', $_REQUEST['user'], time()+60*60*24*30);
            header("Location: /");
        } else {
            echo "Имя пользователя занято";
        }
    }
}

if (!empty($_COOKIE['user'])) {
    include "./info.php";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test user</title>
</head>
<body>
    <?php if (!$_COOKIE['user']): ?>
    <form action="index.php" method="post">
        <label for="user">Имя:</label>
        <input type="text" id="user" name="user">

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password">

        <input type="submit" name="submit" value="enter">
        <input type="submit" name="submit" id="" value="register">
    </form>
    <?php endif;?>
    <?php if (!empty($_COOKIE['user'])) echo '<a href="/?logout=true">Logout</a>' ?>
</body>
</html>

