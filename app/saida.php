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
    <title>Saida</title>

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
            align-items: flex-start;
            justify-content: flex-start;
            padding: 30px 40px;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        /* FORMULÁRIO COMPLETO */
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* TÍTULOS E LABELS */
        h2, label {
            font-size: 18px;
            color: #000000;
            font-weight: bold;
            margin-bottom: 12px;
            display: block;
        }

        h2 {
            margin-bottom: 15px;
        }

        .campo-grupo {
            margin-bottom: 35px;
            width: 100%;
        }

        /* INPUTS (SAÍDA E DIA) - PÍLULAS AZUIS LARGAS */
        input[type="text"],
        input[type="date"] {
            width: 100%;
            height: 48px;
            background-color: #85b4f2; /* Azul padrão */
            border: none;
            border-radius: 18px;
            padding: 0 20px;
            font-size: 16px;
            color: #000000;
            outline: none;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        /* ÁREA DE BOTÕES INFERIOR */
        .botoes-container {
            display: flex;
            align-items: center;
            gap: 40px;
            margin-top: 10px;
        }

        /* BOTÕES DE TEXTO (SALVAR E CANCELAR) */
        input[type="submit"],
        input[type="reset"] {
            background: none;
            border: none;
            color: #000000;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            font-family: Georgia, 'Times New Roman', Times, serif;
            padding: 0;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            text-decoration: underline;
        }

        /* BOTÃO VOLTAR (PÍLULA AZUL) */
        .btn-voltar {
            background-color: #85b4f2;
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 30px;
            border-radius: 15px;
            display: inline-block;
            transition: opacity 0.2s;
        }

        .btn-voltar:hover {
            opacity: 0.9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php 
    include __DIR__ . '/../includes/header.php';
    ?>

    <main> <!--main tem a parte mais import da pag-->
        <form action="" method="post">
            <h2>Adicione o valor da saida:</h2>
            
            <div class="campo-grupo">
                <input type="text" name="saida" id="saida" placeholder="00.00" required> <!--prga o valor da saida-->

                <input type="text" name="motivo" id="motivo" placeholder="Insira o motivo da saída:" required>
                <!--pega o motivo da saida-->
            </div>

            <label for="dia">Dia:</label> <!--pega o dia-->
            <div class="campo-grupo">
                <input type="date" name="dia" id="dia" required>
            </div>

            <div class="botoes-container"> <!--div para o CSS, btns-->
                <input type="submit" value="Registrar Gasto">
                <input type="reset" value="Cancelar">
                <a href="gestao.php" class="btn-voltar">Voltar</a>
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") { // verifica se a pag foi recarregada e roda
            saida($conexao, $_POST['saida'], $_POST['dia'], $_POST['motivo']);
        } // passando os parametros da variavel saida e chamando ela
        ?>

    </main>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
