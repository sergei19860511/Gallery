<?php
setcookie('login', '',0,'/');
setcookie('password', '',0,'/');
header('Location: index.php');
exit();