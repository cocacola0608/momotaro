<?php
session_start();
date_default_timezone_set('Asia/Tokyo');
$date = date("ymdHis", time());


echo $_SESSION['POST_PHOTO'];
?>
