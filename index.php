<?php
global $conn;
session_start();

include 'db.php';  // Подключаем db.php для работы с базой данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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

// Закрываем соединение с базой данных
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOLINA PRO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Castoro+Titling&display=swap" rel="stylesheet">
    <script src="script.js"></script>
    <link rel="stylesheet" href="css-html/main.css?v=1.4">
</head>
<body>
    <header class="header">
        <div class="box">
            <div class="container">
                <div class="header-inner">
                    <div class="header-top">
                        <div class="header-logo"><a href="">DOLINA PRO</a></div>
                        <nav class="header-nav">
                            <ul class="header-nav__menu">
                                <li class="header-list">
                                    <a href="#about-us" class="header-top_link">О нас</a>   
                                </li>
                                <li class="header-list">
                                    <a href="#services" class="header-top_link">Виды услуг</a>   
                                </li>
                                <li class="header-list">
                                    <a href="" class="header-top_link">Оставьте заявку</a>   
                                </li>
                                <li class="header-list">
                                    <a href="" class="header-top_link">Контакты</a>   
                                </li>
                                <li class="header-list">
                                    <a href="?p=login" class="header-top_link">Личный кабинет</a>   
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="header-center">
                        <div class="header-center__left">
                            <div class="center__left-title">
                                Бюро переводов Dolina-pro
                            </div>
                            <div class="profile-container">
                                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-picture">
                                <div class="profile-info">
                                    <div class="profile_name"><?php echo htmlspecialchars($username); ?></div>
                                    <p>Email: <?php echo htmlspecialchars($email); ?></p>
                                    <p>Номер тел: <?php echo htmlspecialchars($phone); ?></p>
                                    <a class="profile-info_btn" href="edit_profile.php">Редактировать профиль</a> <!-- Добавлена ссылка для редактирования профиля -->
                                </div>
                            </div>
                        </div>
                        <div class="header-center__right">
                            <img src="images/рука.avif" alt="">
                        </div>
                    </div>
                    <div class="header-button">
                        <a class="btn-head" href="#forma">Получить консультацию</a>
                    </div>
                </div>
            </div>    
        </div>
    </header>
    <section class="personal_kab">
        <?php
        if (isset($_GET['p']) && $_GET['p'] == 'login') {
            require('login.php');
        }
        ?>
    </section>
    <section class="about-us" id="about-us">
        <div class="container">
            <div class="about-us__title">О нас "dolina pro"</div>
            <div class="about-us__inner">
                <div class="about-us__text">
                   «Dolina pro Бюро переводов» — безусловно, лидер сургутского рынка, компания уже более 8 лет предоставляет услуги перевода широкому кругу клиентов - от крупных предприятий до частных лиц. Мы оказываем услуги оперативного письменного перевода и редактирования перевода носителем языка или специалистом отрасли. Мы также выполняем синхронный и последовательный перевод, лингвистическое сопровождение внешнеэкономической деятельности (в том числе виртуальный офис), перевод телефонных переговоров. Миссия компании: предоставление высококачественных переводческих услуг и постоянное повышение качества обслуживания.
                </div>    
            </div>  
        </div>
    </section>
    <section class="services" id="services">
        <div class="container">
            <div class="services-inner">
                <div class="services-inner__title">Виды услуг</div>
                <div class="services-inner__subtitle">
                    Перевод личных документов с нотариальным заверением
                </div>
                <div class="slideshow">
                    <img src="images/photo1.jpg" alt="Photo 1" class="slide">
                    <img src="images/photo2.jpg" alt="Photo 2" class="slide">
                    <img src="images/photo3.jpg" alt="Photo 3" class="slide">
                    <button id="prevButton" class="btn-slide">Назад</button>
                    <button id="nextButton" class="btn-slide">Далее</button>
                </div>
            </div>
        </div>
    </section>
    <form class="form" method="post" action="send.php" id="forma">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label class="text_form" for="name">Имя:</label>
        <input type="text" name="name" id="name" required>

        <label class="text_form" for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label class="text_form" for="message">Сообщение:</label>
        <textarea name="message" id="message" rows="5" required></textarea>

        <input type="submit" value="Отправить">
    </form>
    <div class="phpmessage">
        <?php
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            $_SESSION['message'] = null;
        }
        ?>
    </div>
    <footer class="footer-contact" id="footer">
        <div class="container">
            <div class="contact-wrap">
                <div class="location-wrap war-icon">
                    <div class="location-wrap_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="32" width="24" viewBox="0 0 384 512"><path d="M384 192c0 87.4-117 243-168.3 307.2c-12.3 15.3-35.1 15.3-47.4 0C117 435 0 279.4 0 192C0 86 86 0 192 0S384 86 384 192z"/></svg>
                    </div>
                    <div class="location-wrap_text">г.Сургут ул.Ленина 1</div>
                </div>
                <div class="footer-tel_wrap war-icon">
                    <div class="footer-tel_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 512 512"><path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>
                    </div>
                    <a href="tel: 79044655248">+79044655248</a>
                </div>
                <div class="footer-messanger-wrap">
                    <div class="footer-messanger_insta">
                        <a class="messager" href="https://www.instagram.com/r_j_065?igsh=dHBmOXc4Mno1cnZo">
                            <svg xmlns="http://www.w3.org/2000/svg" height="32" width="28" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                            <div class="messanger_insta-text">Instagramm</div>
                        </a>
                    </div>
                    <div class="footer-messanger_telegram">
                        <a class="messager" href="https://t.me/R_J_Z_065">
                            <svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 496 512"><path d="M248 8C111 8 0 119 0 256S111 504 248 504 496 393 496 256 385 8 248 8zM363 176.7c-3.7 39.2-19.9 134.4-28.1 178.3-3.5 18.6-10.3 24.8-16.9 25.4-14.4 1.3-25.3-9.5-39.3-18.7-21.8-14.3-34.2-23.2-55.3-37.2-24.5-16.1-8.6-25 5.3-39.5 3.7-3.8 67.1-61.5 68.3-66.7 .2-.7 .3-3.1-1.2-4.4s-3.6-.8-5.1-.5q-3.3 .7-104.6 69.1-14.8 10.2-26.9 9.9c-8.9-.2-25.9-5-38.6-9.1-15.5-5-27.9-7.7-26.8-16.3q.8-6.7 18.5-13.7 108.4-47.2 144.6-62.3c68.9-28.6 83.2-33.6 92.5-33.8 2.1 0 6.6 .5 9.6 2.9a10.5 10.5 0 0 1 3.5 6.7A43.8 43.8 0 0 1 363 176.7z"/></svg>
                            <div class="messanger_telegram-text">Telegramm</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>