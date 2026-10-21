<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de acesso</title>

    <style>
        /* Reset de margens e aplicação da fonte Georgia */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        /* Fundo bege e estrutura da página */
        body {
            background-color: #fff3ed; /* Tom bege da imagem */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* BARRA AZUL SUPERIOR (COM CANTOS ARREDONDADOS) */
        .header-topo {
            width: 100%;
            height: 70px;
            background-color: #85b4f2; /* Azul da foto */
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        /* CONTEÚDO PRINCIPAL CENTRALIZADO */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
        }

        /* TÍTULO PRINCIPAL (PORTAL DE ACESSO) */
        h3 {
            font-size: 26px;
            color: #000000;
            font-weight: bold;
            margin-bottom: 25px; /* AFASSA DO CARD AZUL */
            margin-top: -10px;    /* SUBIR UM POUCO MAIS */
            text-align: center;
        }

        /* CAIXINHA AZUL CENTRAL (CARD) */
        .card-portal {
            background-color: #85b4f2; /* Azul idêntico à imagem */
            width: 100%;
            max-width: 480px;
            padding: 40px 20px;
            border-radius: 20px; /* Cantos arredondados bonitinhos */
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* TEXTOS DENTRO DA CAIXINHA AZUL */
        .card-portal p {
            color: #ffffff; /* Texto branco */
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }

        /* LINKS SUBLINHADOS */
        .card-portal a {
            color: #000000; /* Escrita preta/escura para o link */
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            display: inline-block;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        .card-portal a:hover {
            opacity: 0.8;
        }

        /* BARRA AZUL INFERIOR BEM FININHA */
        .footer-base {
            width: 100%;
            height: 12px; /* Espessura bem fina */
            background-color: #85b4f2;
            margin-top: auto;
        }
    </style>

</head>
<body>

    <div class="header-topo"></div> <!--div para o CSS (div 1)-->
    <main>
        <h3>Portal de Acesso</h3>

        <div class="card-portal"> <!--div para o CSS (div 2)-->
            <p>Já possui uma conta?</p>
            <a href="/clinica_arco_iris/login/login.php">Clique aqui para logar</a> <!--btn-->
            <p>Não possui uma conta?</p>
            <a href="/clinica_arco_iris/login/cadastrar.php">Cadastra-se</a> <!--btn-->
        </div> <!--fim da div 2-->
    </main>

    <div class="footer-base"></div> <!--fim da div 1-->
</body>
</html>
