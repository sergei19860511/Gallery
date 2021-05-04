<?php
$validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
require __DIR__ . '/auth.php';
$login = getUserLogin();
if (!$login) {
    header('Location: /index.php');
}
$tmpPaht = 'smallPhoto/';

    function resize($file)
    {
        global $tmpPaht;

        // Ограничение по ширине в пикселях
        $max_thumb_size = 250;

        // Качество изображения по умолчанию
        if ($quality == null)
            $quality = 75;

        // Cоздаём исходное изображение на основе исходного файла
        if ($file['type'] == 'image/jpeg') {
            $source = imagecreatefromjpeg($file['tmp_name']);
        } elseif ($file['type'] == 'image/jpg') {
            $source = imagecreatefrompng($file['tmp_name']);
        } elseif ($file['type'] == 'image/png') {
            $source = imagecreatefrompng($file['tmp_name']);
        } elseif ($file['type'] == 'image/gif') {
            $source = imagecreatefromgif($file['tmp_name']);
        } else {
            return false;
        }
        // Поворачиваем изображение
        if ($rotate != null)
            $src = imagerotate($source, $rotate, 0);
        else
            $src = $source;

        // Определяем ширину и высоту изображения
        $w_src = imagesx($src);
        $h_src = imagesy($src);

        // В зависимости от типа (эскиз или большое изображение) устанавливаем ограничение по ширине.

        $w = $max_thumb_size;

        // Если ширина больше заданной
        if ($w_src > $w)
        {
            // Вычисление пропорций
            $ratio = $w_src/$w;
            $w_dest = round($w_src/$ratio);
            $h_dest = round($h_src/$ratio);

            // Создаём пустую картинку
            $dest = imagecreatetruecolor($w_dest, $h_dest);

            // Копируем старое изображение в новое с изменением параметров
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

            // Вывод картинки и очистка памяти
            imagejpeg($dest, $tmpPaht . $file['name'], $quality);
            imagedestroy($dest);
            imagedestroy($src);

            return $file['name'];
        }
        else
        {
            // Вывод картинки и очистка памяти
            imagejpeg($src, $tmpPaht . $file['name'], $quality);
            imagedestroy($src);

            return $file['name'];
        }
    }

if ($login !== null && !empty($_FILES)) {
    $file = $_FILES['attachment'];
    //var_dump($file);
    $fileName = resize($file);
    $newPathFile = __DIR__ . '/uploades/' . $fileName;
    //echo $newPathFile;
    $extensions = pathinfo($newPathFile, PATHINFO_EXTENSION);
    $maxWidth = 1280;
    $maxHeight = 720;
    if (!empty($file['tmp_name'])) {
        $imageSize = getimagesize(__DIR__ . '/smallPhoto/' . $fileName);
    }

    if (!in_array($extensions, $validExtensions)) {
        $error = 'не допустимое расширение файла';
    } else if ($imageSize[0] > $maxWidth or $imageSize[1] > $maxHeight) {
        $error = 'ширина или высота картинки слишком большая';
    } else if (file_exists($newPathFile)) {
        $error = 'файл с таким именем уже существует';
    } else if ($file['error'] === 1) {
        $error = 'превышен размер файла';
    } else if ($file['error'] === 2) {
        $error = 'превышен размер файла';
    } else if ($file['error'] === 3) {
        $error = 'Загружаемый файл не был получен полностью';
    } else if ($file['error'] === 4) {
        $error = 'Файл не был загружен';
    } else if (!move_uploaded_file($file['tmp_name'], $newPathFile)) {
        $error = 'ошибка загрузки';
    } else {
        $message = true;
    }
}
?>

<html>
<head>
    <title>Загрузка файла</title>
</head>
<body>
<?php if ($login): ?>
    Добро пожаловать, <?= $login ?>
    <br><br>
    <a href="/logout.php">Выйти</a>
    <br><br>
    <a href="/index.php">На главную</a>
    <br><br>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <h3><?= $error ?></h3>
<?php elseif ($message): ?>
    <img src="/smallPhoto/<?= $fileName ?>">
<?php else: ?>
    <form action="/upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="8000000"/>
        <input type="file" name="attachment">
        <input type="submit">
    </form>
<?php endif; ?>
</body>
</html>
