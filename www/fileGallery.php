<html>
<head>
    <title>Фотоальбом</title>
</head>
<body>
<?php
$files = scandir(__DIR__ . '/smallPhoto');
$links = [];
foreach ($files as $fileName) {
    if ($fileName === '.' || $fileName === '..') {
        continue;
    }
    $links[] = 'http://project.loc/smallPhoto/' . $fileName;
}

foreach ($links as $link):
    $bigPhoto = strrchr($link, '/'); ?>
    <a href="http://project.loc/uploades<?= $bigPhoto ?>"><img src="<?= $link ?>">&nbsp;&nbsp;</a>
<?php endforeach; ?>
<br>
<a href="/index.php">На главную</a>
</body>
</html>