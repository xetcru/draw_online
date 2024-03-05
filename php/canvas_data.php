<?php
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

// Если получены данные о новой линии, записываем их в базу данных
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['line'])) {
    $line = $data['line'];
    $sql = "INSERT INTO DrawnLines (start_x, start_y, end_x, end_y, color, thickness) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    //$stmt->bind_param("dddds", $line['startX'], $line['startY'], $line['endX'], $line['endY'], $line['color'], $line['thickness']);
    $stmt->bind_param("iiiiis", $line['startX'], $line['startY'], $line['endX'], $line['endY'], $line['thickness'], $line['color']);
    $stmt->execute();
    if ($stmt->error) {
        die("Ошибка выполнения запроса: " . $stmt->error);
    }
}

// Получаем все линии из базы данных
$sql = "SELECT start_x, start_y, end_x, end_y, color, thickness FROM DrawnLines";
$result = $conn->query($sql);
if ($conn->error) {
    die("Ошибка запроса: " . $conn->error);
}
$lines = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $lines[] = array(
            'startX' => $row['start_x'],
            'startY' => $row['start_y'],
            'endX' => $row['end_x'],
            'endY' => $row['end_y'],
            'color' => $row['color'],
            'thickness' => $row['thickness']
        );
    }
}

echo json_encode($lines);

$conn->close();
?>
