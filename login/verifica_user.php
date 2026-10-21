<?php 
// SE não tiver esse user vc manda 
if(session_status () == PHP_SESSION_NONE){
    session_start(); // iniciar a sessão
}
// SE tiver esse user vc atribui um ID e manda ele para o painel
if(!isset($_SESSION['id'])){
    header("Location: ../painel.php"); //redireciona
    exit(); // sai
}
?>