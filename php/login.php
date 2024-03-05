<?php
/*
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);

CREATE TABLE DrawnLines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    start_x INT,
    start_y INT,
    end_x INT,
    end_y INT,
    color VARCHAR(7),
    thickness INT,
    opacity FLOAT,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
*/
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

// Получаем данные из формы
$form_username = $_POST['username'];
$form_password = $_POST['password'];

// Проверяем, это вход или регистрация
if ($_POST['submit'] == 'Войти') {
    $sql = "SELECT * FROM Users WHERE username='$form_username' AND password='$form_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Пользователь найден, устанавливаем переменную сессии и перенаправляем на canvas.html
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $form_username;
        header('Location: ../canvas.php');
    } else {
        echo "Неверное имя пользователя или пароль.</br><a href='../'>назад</a>";
    }
} else if ($_POST['submit'] == 'Зарегистрироваться') {
    // Проверяем, существует ли уже такой пользователь
    $sql = "SELECT * FROM Users WHERE username='$form_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Пользователь уже существует, выводим сообщение об ошибке
        echo "Пользователь с таким именем уже существует.";
    } else {
        // Пользователя не существует, регистрируем нового пользователя
        $sql = "INSERT INTO Users (username, password) VALUES ('$form_username', '$form_password')";

        if ($conn->query($sql) === TRUE) {
            // Пользователь зарегистрирован, устанавливаем переменные сессии и перенаправляем на canvas.html
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $form_username;
            header('Location: ../canvas.php');
        } else {
            echo "Ошибка: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
