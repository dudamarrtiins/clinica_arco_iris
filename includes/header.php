<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Arco-Íris</title>
    
    <style>
        /* Reset para zerar as margens padrão */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        /* Fundo bege da página inteira */
        body {
            background-color: #fff3ed;
            min-height: 100vh;
        }

        /* Menu do topo - Barra Azul Grossa */
        nav {
            background-color: #72a9ed;
            padding: 25px 20px;
            text-align: center;
        }

        /* Os links de navegação */
        nav a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            margin: 0 12px;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
        }

        /* Efeito de contorno branco (estilo o 'consultar' do seu desenho) */
        nav a:hover {
            border: 2px solid white;
        }

        /* Estilo dos campos de texto (input cinza arredondado) */
        input[type="text"], 
        input[type="password"], 
        input[type="date"] {
            width: 100%;
            background-color: antiquewhite;
            border: none;
            padding: 14px 20px;
            border-radius: 25px;
            font-size: 16px;
            margin-top: 10px;
            margin-bottom: 20px;
            outline: none;
        }
    </style>
</head>

<header>
    <nav>
        <div> <!--btns para navegar, aqui esta os endereços das pags-->
        <a href="/clinica_arco_iris/painel.php">Dashboard</a>

        <a href="/clinica_arco_iris/app/agenda.php">Agenda  </a>

        <a href="/clinica_arco_iris/app/prontuario.php">Prontuário </a>

        <a href="/clinica_arco_iris/app/consultar.php">Consultar </a>

        <a href="/clinica_arco_iris/app/gestao.php">Gestao </a>

        <a href="/clinica_arco_iris/login/logout.php">Sair </a>
        </div>
    </nav>

</header>
