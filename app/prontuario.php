<?php
require_once __DIR__ . '/../includes/functions.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!-- VISUALIZAR SEM O W-->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prontuario</title>

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

        /* TÍTULO DO PRONTUÁRIO */
        h2 {
            font-size: 26px;
            color: #000000;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        /* CARD AZUL ENVOLVENDO A SAÍDA DA FUNÇÃO PRONTUÁRIO */
        .card-prontuario-container {
            background-color: #85b4f2;
            width: 100%;
            max-width: 900px;
            padding: 35px 40px;
            border-radius: 20px;
            margin-bottom: 35px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            color: #000000;
        }

        /* LINHA DIVISÓRIA PRETA ENTRE OS PACIENTES */
        hr {
            border: none;
            border-top: 3px solid #000000;
            margin: 25px 0;
            width: 100%;
        }

        /* BARRA AZUL DOS BOTÕES (ADICIONAR / REMOVER / ATUALIZAR) */
        .barra-acoes-prontuario {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .barra-acoes-prontuario nav,
        .barra-acoes-prontuario div,
        .barra-acoes-prontuario header {
            background-color: #85b4f2;
            width: 100%;
            max-width: 900px;
            padding: 16px 20px;
            border-radius: 18px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .barra-acoes-prontuario a {
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
        }

        .barra-acoes-prontuario a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        
        <h2>Prontuário</h2>

        <!-- CARD AZUL QUE ENVOLVE OS DADOS DO PACIENTE -->
        <div class="card-prontuario-container"> <!--div para organizar o CSS-->
            <?php prontuario($conexao, $_SESSION['id']); ?> <!--passando os paramentros e chamando a função-->
        </div>

        <!-- BARRA AZUL SEPARADA NA PARTE INFERIOR -->
        <div class="barra-acoes-prontuario">
            <?php include __DIR__ . '/../includes/headerProntuario.php'; ?>
        </div>
    </main>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>


