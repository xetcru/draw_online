<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header('Location: canvas.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PaintOnline - Вход/Регистрация</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Рисовалка">
    <meta name="author" content="Grom xETC.ru">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="index-content">
    <div id="loginForm" class="index-form">
        <h2>Вход</h2>
        <form action="php/login.php" method="post">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="submit" name="submit" value="Войти">
        </form>
    </div>
    <div id="registerForm" class="index-form">
        <h2>Регистрация</h2>
        <form action="php/login.php" method="post">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="submit" name="submit" value="Зарегистрироваться">
        </form>
    </div>
</div>
</body>
</html>
