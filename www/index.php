<?php
require __DIR__ . '/auth.php';
$login = getUserLogin();
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная страница</title>
</head>
<body>
<?php if ($login === null): ?>
    <a href="/login.php">Авторизуйтесь</a>
<?php else: ?>
    Добро пожаловать, <?= $login ?>
    <br><br>
    <a href="/upload.php">Загрузка файлов</a>
    <br><br>
    <a href="/fileGallery.php">Галерея файлов</a>
    <br><br>
    <a href="/logout.php">Выйти</a>
<?php endif; ?>
</body>
</html>
