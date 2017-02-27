<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$user = '##########';
$password = '######';

$dbname = '#######################';
$host = '########################';

$dns = "mysql:host={$host};dbname={$dbname};charset=utf8";

try{

$pdo = new PDO($dns,$user,$password);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
//$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMORE_EXCEPTION);

}catch (Exception $e){
echo '<span class="error">エラーがありました</span></br>';
echo $e->getMessage();
exit();
}


?>