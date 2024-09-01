<?php
global $conn;
session_start();
include 'db.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['login_user'];

$stmt = $conn->prepare("SELECT username, email, phone, profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($username, $email, $phone, $profile_picture);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="css-html/main.css?v=1.2">
</head>
<body>
<div class="profile-container">
    <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-picture">
    <div class="profile-info">
        <h2><?php echo htmlspecialchars($username); ?></h2>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>Phone: <?php echo htmlspecialchars($phone); ?></p>
        <a href="edit_profile.php">Edit Profile</a>
    </div>
</div>
</body>
</html>