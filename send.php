<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute() === TRUE) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
</head>
<body>
<form action="" method="post">
    <label>Name:</label>
    <input type="text" name="name"><br>
    <label>Email:</label>
    <input type="email" name="email"><br>
    <label>Message:</label>
    <textarea name="message"></textarea><br>
    <input type="submit" value="Send"><br>
</form>
</body>
</html>