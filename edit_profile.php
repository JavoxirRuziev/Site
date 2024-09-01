<?php
global $conn;
session_start();

include 'db.php';  // Подключаем db.php для работы с базой данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit();
}

// Получаем ID пользователя из сессии
$user_id = $_SESSION['user_id'];

// Извлекаем данные пользователя из базы данных
$stmt = $conn->prepare("SELECT username, email, phone, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $phone, $profile_picture);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $new_profile_picture = $_FILES['profile_picture'];

    // Проверка пароля
    if (!empty($new_password) && $new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    }

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (!empty($new_profile_picture['tmp_name'])) {
        $target_file = $target_dir . basename($new_profile_picture["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($new_profile_picture["tmp_name"]);

        if ($check !== false && move_uploaded_file($new_profile_picture["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $target_file = $profile_picture; // Сохраняем старое изображение, если новое не загружено
    }

    if (!empty($new_password)) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, profile_picture = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $new_username, $new_email, $new_phone, $profile_picture, $hashed_password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, profile_picture = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $new_username, $new_email, $new_phone, $profile_picture, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['login_user'] = $new_username;
        $_SESSION['message'] = "Profile updated successfully.";
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Редактировать профиль</title>
    <link rel="stylesheet" href="css-html/main.css?v=1.4">
</head>
<body>
<?php
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
?>
<div class="edit_profile_title">
    Редактировать профиль
</div>
<form class="edit_profile_form" action="" method="post" enctype="multipart/form-data">
    <label>Имя пользователя:</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

    <label>Телефон номер:</label>
    <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone); ?>" pattern="[0-9]{10,14}" required><br>

    <label>Аватарка:</label>
    <input class="avatar_btn" type="file" name="profile_picture" accept="image/*"><br>
    <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-picture"><br>

    <label>Новый пароль:</label>
    <input type="password" name="new_password" placeholder="Оставьте пустым, чтобы не менять"><br>

    <label>Подтвердите новый пароль:</label>
    <input type="password" name="confirm_password" placeholder="Оставьте пустым, чтобы не менять"><br>

    <input type="submit" value="Обновить профиль"><br>
</form>
</body>
</html>