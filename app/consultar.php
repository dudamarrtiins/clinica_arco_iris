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
    <title>Consulta Usuario</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        body {
            background-color: #fff3ed; /* Fundo bege da imagem */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TÍTULO CENTRALIZADO */
        h1 {
            font-size: 24px;
            color: #000000;
            font-weight: bold;
            text-align: center;
            margin-top: 25px;
            margin-bottom: 20px;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 20px 40px 20px;
            width: 100%;
        }

        /* FORMULÁRIO DE CONSULTA */
        form {
            width: 100%;
            max-width: 850px;
            display: flex;
            flex-direction: column;
            margin-bottom: 35px;
        }

        form label {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 8px;
            display: block;
        }

        /* BARRA AZUL DE PESQUISA (CONTAINER DO INPUT + SUBMIT) */
        .barra-busca-container {
            background-color: #85b4f2; /* Azul do protótipo */
            width: 100%;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            padding: 5px 15px;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* CAMPO DE TEXTO DENTRO DA BARRA AZUL */
        .barra-busca-container input[type="text"] {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
        }

        .barra-busca-container input[type="text"]::placeholder {
            color: rgba(255, 255, 255, 0.8);
            font-weight: normal;
        }

        /* BOTÃO DE CONSULTAR */
        .barra-busca-container input[type="submit"] {
            background-color: #ffffff;
            color: #000000;
            border: none;
            padding: 6px 18px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        .barra-busca-container input[type="submit"]:hover {
            opacity: 0.9;
        }

        /* CARD AZUL DO RESULTADO DA CONSULTA */
        .resultado-consulta-card,
        main > div,
        main > table,
        main > p {
            background-color: #85b4f2;
            width: 100%;
            max-width: 420px;
            padding: 25px 30px;
            border-radius: 18px;
            color: #000000;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-top: 10px;
        }

        .resultado-consulta-card p,
        main > div p {
            margin-bottom: 8px;
        }

        .resultado-consulta-card p:last-child,
        main > div p:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>Consultar Paciente</h1>
    <main> <!--main pois o conteudo principal esta aqui:-->
    <form action="" method="post">
        <label for="nome">Nome: </label> <!--pegando o nome-->

        <div class="barra-busca-container"> <!--div para organização do CSS-->
            <input type="text" name="nome" id="nome" placeholder="Insira um nome para consultar" required>
            <input type="submit" value="Consultar"> <!--bnts-->
        </div>
    </form>
    
    <?php 
    if($_SERVER['REQUEST_METHOD']=="POST"){ // verifica se foi carregada a pag
        echo '<div class="resultado-consulta-card">';
        consultar($conexao, $_POST['nome']);
        echo '</div>';
    } // passando os parametros da função consultar e chamando ela

    // novamente o campo de nome é utilizado no lugar do id, assim o sql sabe onde buscar na tab paciente
    ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>