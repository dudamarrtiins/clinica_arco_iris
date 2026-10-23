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
    <title>Apaga Paciente</title>

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

        /* TÍTULO PRINCIPAL */
        h1 {
            font-size: 24px;
            color: #000000;
            font-weight: bold;
            text-align: center;
            margin-top: 30px;
            margin-bottom: 25px;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 20px 40px 20px;
            width: 100%;
        }

        /* FORMULÁRIO DE REMOÇÃO */
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
            margin-bottom: 12px;
            display: block;
        }

        /* INPUT DE NOME - PAERTE AZUL LARGA */
        input[type="text"] {
            width: 100%;
            height: 48px;
            background-color: #85b4f2; /* Azul do padrão */
            border: none;
            border-radius: 18px;
            padding: 0 20px;
            font-size: 16px;
            color: #000000;
            outline: none;
            font-family: Georgia, 'Times New Roman', Times, serif;
            margin-bottom: 35px;
        }

        input[type="text"]::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }

        /* CONTAINER DOS BOTÕES INFERIORES */
        .botoes-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
        }

        /* BOTÕES APAGAR E VOLTAR COMO PÍLULAS AZUIS */
        input[type="submit"],
        .btn-voltar {
            background-color: #85b4f2;
            color: #ffffff;
            border: none;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            font-family: Georgia, 'Times New Roman', Times, serif;
            padding: 12px 40px;
            border-radius: 15px;
            transition: opacity 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            display: inline-block;
        }

        input[type="submit"]:hover,
        .btn-voltar:hover {
            opacity: 0.9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php';  // inclui o header
    ?> 

    <h1>Apagar paciente</h1>

    <main>

    <form action="" method="post">

<!--action="" - quando deixado em branco, fala que os dados serão enviados para
a propria pag

method="post" - define o tipo de envio, de uma forma oculta pela requisição
do HTML, assim não fica vissel na url

name="nome" - cave que identifica o valor, o forms leva através do $_POST['nome']

required - não deixa ser enviado com campo em branco
-->

        <label for="nome">Nome: </label> <!--digita o nome -->
        <input type="text" name="nome" id="nome" placeholder="Insira o nome completo" required>
        
        <div class="botoes-container">
            <input type="submit" value="Apagar"> 
            <a href="prontuario.php" class="btn-voltar">Voltar</a> <!--bnt para ir para o prontu--> 
        </div> 
    </form> <!--fim do forms-->

    <?php 
    if($_SERVER['REQUEST_METHOD']=="POST"){ // verifica se a pag foi carregada
        apagar($conexao, $_POST['nome']); // chama a função e passa a conexao e o nome 
        // o nome funciona como id, é atraves dele que é possivel saber onde deve-se ser apagado na tab paciente
    }
    // passando os parametros da variavel apagar
    ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>