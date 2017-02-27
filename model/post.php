<?php
session_start();
date_default_timezone_set('Asia/Tokyo');
$date = date("ymdHis", time());
$PASS = "../assets/img/".$date.$_SESSION['LOGIN_ID'].".jpg";
$TIMELINE_ID = $date.$_SESSION['LOGIN_ID'];

if($_FILES["file"]["name"] == ""){
	$_SESSION['postError'] = "postError";
	header('Location: ../top/');
	exit();
}


if($_FILES['file']){
  	move_uploaded_file($_FILES['file']['tmp_name'], $PASS);
}


require('./dbconnect.php');

$sql = 'INSERT INTO TIMELINES VALUES(:photo,:comment,:login_id);';

$stm = $pdo->prepare($sql);
$stm->bindValue(':photo',$TIMELINE_ID);
$stm->bindValue(':comment',$_POST['comment']);	
$stm->bindValue(':login_id',$_SESSION['LOGIN_ID']);
$stm->execute();


$_SESSION['postCorrect'] = "postCorrect";
header('Location: ../top/');
exit();

?>