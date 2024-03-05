<?php
session_start();
$servername = "localhost";
$username = "gk10su_test";
$password = "453SMCjJWC98CVR5XB";
$dbname = "gk10su_test";
// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);
// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['logout'])) {
    // Удаляем пользователя из базы данных
    $sql = "DELETE FROM Users WHERE username='" . $_SESSION['username'] . "'";
    $conn->query($sql);

    // Уничтожаем сессию и перенаправляем на index.php
    session_destroy();
    header('Location: index.php');
    exit;
}

// Получаем список пользователей из базы данных
$sql = "SELECT username FROM Users";
$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row['username'];
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>PaintOnline - Canvas</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Рисовалка">
    <meta name="author" content="Grom xETC.ru">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="canvas-content">
    <div id="controlPanel">
        <label for="color">Цвет: </label>
        <input type="color" id="color" value="#000000">
        <label for="thickness">Толщина: </label>
        <input type="range" id="thickness" min="1" max="10" value="1">
        <label for="opacity">Прозрачность: </label>
        <input type="range" id="opacity" min="0" max="1" step="0.1" value="1">
        <button id="clearall">Очистить все</button>
        <form method="post">
            <input type="submit" name="logout" id="logout" value="Выйти">
        </form>
    </div>
    <canvas id="canvas" width="800" height="600"></canvas>
    <div id="onlineUsers">
        <h2>Пользователи онлайн:</h2>
        <ul id="userList">
            <?php
            foreach ($users as $user) {
                if ($user == $_SESSION['username']) {
                    echo "<li style='color:red;'>$user</li>";
                } else {
                    echo "<li>$user</li>";
                }
            }
            ?>
        </ul>
    </div>
</div>
<script src="js/canvas.js"></script>
</body>
</html>
