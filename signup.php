<?php
global $conn;
include 'db.php';  // Подключаем db.php, где создается подключение $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка на существование пользователя
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $_POST['username'], $_POST['email']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username or Email already exists.";
        exit();
    }
    $stmt->close();

    // Получение данных из формы
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Обработка изображения профиля
    $profile_picture = $_FILES['profile_picture'];

    // Проверка и перемещение загруженного файла
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true); // Создание папки, если она не существует
    }
    $target_file = $target_dir . basename($profile_picture["name"]);

    // Проверка типа файла и размера
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($profile_picture["tmp_name"]);
    $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif'];
    $max_file_size = 2 * 1024 * 1024; // 2 MB

    if ($check !== false) {
        if (!in_array($imageFileType, $allowed_file_types) || $_FILES['profile_picture']['size'] > $max_file_size) {
            echo "Invalid file type or size too large.";
            exit();
        }

        if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
            // Вставка данных пользователя в базу
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone, profile_picture) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $hashed_password, $email, $phone, $target_file);

            if ($stmt->execute() === TRUE) {
                // Успешная регистрация, перенаправляем на главную страницу
                header("Location: /index.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" href="css-html/main.css?v=1.2">
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
    <label>Username:</label>
    <input type="text" name="username" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Phone number:</label>
    <input type="tel" name="phone" pattern="[0-9]{10,14}" required><br>

    <label>Profile Picture:</label>
    <input type="file" name="profile_picture" accept="image/*"><br>

    <input type="submit" value="Signup"><br>
</form>
</body>
</html>