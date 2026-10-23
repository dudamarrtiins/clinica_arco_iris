<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../includes/functions.php';

$acao = $_GET['acao'] ?? null; 
/*pega o valor que foi passado, se não tiver pegado da como null */
$id = $_GET['id'] ?? null;
// pega o valor passado e se não tiver pego da como null

if ($acao && $id) { /* se a variavel acao e a variavel id existirem 
ao mesmo tempo com valores (sem ser null) entao:*/
    if ($acao == 'cancelar') { 
        /*se a variavel acao for = a cancelar ele chama a função de cancelar
        e atribui os parâmetros */
        cancelarConsulta($conexao, $id); 
    } elseif ($acao == 'realizada') { // igual ao de cima
        atualizarStatusConsulta($conexao, $id, 'realizada');
    } elseif ($acao == 'deletar') {
        deletarConsulta($conexao, $id);
    }
}

header("Location: agenda.php");
exit(); // fim