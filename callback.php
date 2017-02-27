<?php
 
// セッション開始
session_start();
ini_set( 'display_errors', 1 );
require('./model/dbconnect.php');
// TwitterOAuthのコアファイルとTwitterアプリケーションの設定値を読み込み
require_once 'socialLogin/twitter/common.php';
require_once 'socialLogin/twitter/twitteroauth/autoload.php';
 
// セッション情報を変数に代入
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
 
// 本人確認
if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    die( 'えらーです。' );
    exit;
}
 
//OAuth トークンも用いて TwitterOAuth をインスタンス化
$twitter = new Abraham\TwitterOAuth\TwitterOAuth(
                    ConsumerKey, 
                    ConsumerSecret, 
                    $request_token['oauth_token'], 
                    $request_token['oauth_token_secret']
            );
 
// tokenを取得
$result = $twitter->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
 
$getUser = new Abraham\TwitterOAuth\TwitterOAuth(
                    ConsumerKey, 
                    ConsumerSecret, 
                    $result['oauth_token'], 
                    $result['oauth_token_secret']
            );
 
// ユーザ情報取得
$user = $getUser->get("account/verify_credentials");

$sql = 'SELECT * FROM USERS WHERE LOGIN_ID = :user_id;';

$stm = $pdo->prepare($sql);
$stm->bindValue(':user_id',$user->id_str);  
$stm->execute();

if( $result = $stm->fetch(PDO::FETCH_ASSOC) ){
    $_SESSION['LOGIN_ID'] = $result['LOGIN_ID'];
    $_SESSION['USER_NAME'] = $result['USER_NAME'];
}
else{
    $sql = 'INSERT INTO USERS VALUES (:user_id,:user_name,:user_thum,:user_auth,:datetime,:comment);';

    $stm = $pdo->prepare($sql);
    $stm->bindValue(':user_id',$user->id_str);
    $stm->bindValue(':user_name',$user->name);  
    $stm->bindValue(':user_thum',"thum");
    $stm->bindValue(':user_auth',1);   
    $stm->bindValue(':datetime',date('c'));
    $stm->bindValue(':comment',"よろしく");      
    $result = $stm->execute();
    $_SESSION['LOGIN_ID'] = $result['LOGIN_ID'];
    $_SESSION['USER_NAME'] = $result['USER_NAME'];
}

  header('Location: ./top');
 
?>


