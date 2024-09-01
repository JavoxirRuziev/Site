<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/style-reg-avt.css?v=1.2">
	<title>Вход в личный кабинет</title>
</head>
<body>
<?php
	$title="Главная страница"; // название формы
	require __DIR__ . '/header.php'; // подключаем шапку проекта
	require "db.php"; // подключаем файл для соединения с БД
?>
	<header class="header">
		<div class="container-reg">
			<div class="header-title">Добро пожаловать</div>
		</div>
	</header>
					<!-- Если авторизован выведет приветствие -->
	<?php if(isset($_SESSION['logged_user'])) : ?>
	Привет, <?php echo $_SESSION['logged_user']->name; ?></br>
		
					<!-- Пользователь может нажать выйти для выхода из системы -->
	<a href="logout.php">Выйти</a> <!-- файл logout.php создадим ниже -->
	<?php else : ?>

					<!-- Если пользователь не авторизован выведет ссылки на авторизацию и регистрацию -->
	<a href="login.php">Авторизоваться</a><br>
	<a href="signup.php">Регистрация</a>
	<?php endif; ?>

	<?php require __DIR__ . '/footer.php'; ?> <!-- Подключаем подвал проекта -->
</body>
</html>

