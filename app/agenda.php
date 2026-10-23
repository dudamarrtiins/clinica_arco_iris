<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../includes/functions.php';

$consultas = listarConsultas($conexao); 
/* ta falando que a minha $ passa a ter o resultado da minha função em array */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agenda</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        body {
            background-color: #fff3ed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
        }

        .agenda-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .agenda-header h2 {
            font-size: 26px;
            color: #000000;
            font-weight: bold;
        }

        .btn-novo-atendimento {
            color: #72a9ed;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            margin-bottom: 30px;
            display: inline-block;
        }

        .btn-novo-atendimento:hover {
            text-decoration: underline;
        }

        /* CONTAINER DE CADA CONSULTA */
        .bloco-consulta-item {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
        }

        /* CAIXINHA DO PACIENTE */
        .card-paciente-azul {
            background-color: #85b4f2;
            width: 100%;
            max-width: 420px;
            padding: 25px 30px;
            border-radius: 18px;
            margin-bottom: 35px;
        }

        .card-paciente-azul p {
            color: #000000;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .card-paciente-azul p:last-child {
            margin-bottom: 0;
        }

        /* BARRA DE AÇÕES SOLTA EMBAIXO */
        .barra-acoes-azul {
            background-color: #85b4f2;
            width: 100%;
            max-width: 900px;
            padding: 16px 20px;
            border-radius: 18px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .barra-acoes-azul a {
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
        }

        .barra-acoes-azul a:hover {
            text-decoration: underline;
        }

        .mensagem-vazia {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

<main>
    <div class="agenda-header">
        <h2>Agenda de Atendimentos</h2>
    </div>

    <a href="agendamento.php" class="btn-novo-atendimento"> + Novo Atendimento</a>

    <?php foreach ($consultas as $c): ?> <!--o $c é o result de 1 só consulta-->
        <?php 
            // Busca o paciente direto pelo ID da consulta
            $stmtP = $conexao->prepare("SELECT nome FROM paciente WHERE id = :id");
            // sql : selecione nome na tabela paciente onde o ID for igual ao ID informad/recebido
           
            $stmtP->bindParam(':id', $c['id_paciente']);
            /*pega o num do paciente guardado na consulta atual ($c['id_paciente']) 
            e guarda na caixinha :id */

            $stmtP->execute(); // execulte o comando
            
            $paciente = $stmtP->fetch(PDO::FETCH_ASSOC); // aqui ele ja tem o nome do paciente buscado e guarda no array
        ?>

        <div class="bloco-consulta-item">
            <!-- Card de informações do paciente -->
            <div class="card-paciente-azul">
                <p><strong>Paciente:</strong> <?php echo $paciente['nome']; ?></p> <!--mostra o nome-->

                <p><strong>Data:</strong> <?php echo $c['dia']; ?> às <?php echo $c['hora']; ?></p> <!--mostra o dia e hora-->

                <p><strong>Status:</strong> <?php echo $c['status']; ?></p> <!--mostra o status-->
            </div>

            <!-- Barra de botões separada -->
            <nav class="barra-acoes-azul">
            <!-- tudo que vem depois do ? no link é enviado para o PHP pelo $_GET-->
                <a href="status.php?acao=realizada&id=<?php echo $c['id']; ?>">Consulta Realizada</a>
            <!--enviar o user para o status.php e passa os dados de acao: realizda e o id da consulta-->

                <a href="status.php?acao=cancelar&id=<?php echo $c['id']; ?>">Cancelar Consulta</a>
             <!-- funciona da mesma forma que o bnt de realizada-->
                <a href="remarcar.php?id=<?php echo $c['id']; ?>">Remarcar</a>
            <!--leva para o remarcar.php-->
                <a href="status.php?acao=deletar&id=<?php echo $c['id']; ?>" onclick="return confirm('Tem certeza que deseja apagar?');">Apagar</a>
            <!--envia a instrução de deletar e o id da consulta,
            o ONCLICK="".... é um comando em javaScript, para abrir uma 
            janelinha de pop-up e evitar que o user apague por acidente-->
            </nav>
        </div>
    <?php endforeach; ?>
</main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>