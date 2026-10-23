<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
require_once __DIR__ . '/../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        body {
            background-color: #fff3ed; /* Fundo bege padrão */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px 40px 20px;
            width: 100%;
        }

        /* TÍTULO PRINCIPAL EM DUAS LINHAS */
        h1 {
            font-size: 26px;
            color: #000000;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 30px;
            line-height: 1.3;
            max-width: 300px;
        }

        /* CARD AZUL GRANDE ENVOLVENDO O EXTRATO */
        .card-extrato-container {
            background-color: #85b4f2; /* Azul do protótipo */
            width: 100%;
            max-width: 850px;
            padding: 35px 40px;
            border-radius: 18px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            color: #000000;
            font-size: 16px;
            line-height: 1.6;
        }

        /* LINHAS PRETAS DIVISÓRIAS DENTRO DO CARD */
        .card-extrato-container hr,
        hr {
            border: none;
            border-top: 3px solid #000000;
            margin: 25px 0;
            width: 100%;
        }

        /* BOTÃO VOLTAR (PÍLULA AZUL CENTRALIZADA) */
        .btn-voltar {
            background-color: #85b4f2;
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            padding: 12px 40px;
            border-radius: 15px;
            display: inline-block;
            transition: opacity 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-voltar:hover {
            opacity: 0.9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main> <!--main, pois o impor da pag esta aqui:-->
        <h1>Sua movimentação<br>financeira:</h1>

        <!-- Card azul gigante contendo a listagem do extrato -->
        <div class="card-extrato-container">
            <?php extrato($conexao); ?> <!--passando o parametro e chamando a função-->
        </div>

        <!-- Botão Voltar azul e arredondado em baixo -->
        <a href="gestao.php" class="btn-voltar">Voltar</a> <!--bnt-->
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
