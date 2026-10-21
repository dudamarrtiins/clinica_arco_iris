<?php 
require_once __DIR__ . '/../includes/functions.php';

session_start(); // inicia a sessão

// se já estiver logado, redireciona para o painel.php
if (isset($_SESSION['id'])) {
    header("Location: ../painel.php");
    exit();
}

// guarda a mensagem de erro se o login falhar
$erro_login = "";

// processa o formulário ANTES de desenhar o HTML na tela
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $usuario = consulta_user($conexao, $_POST['email']);

    if ($usuario && $usuario['email'] == $_POST['email'] && $usuario['senha'] == $_POST['senha']) {
        $_SESSION['id'] = $usuario['id']; // salva o ID na sessão que já está aberta
        header("Location: ../painel.php");
        exit();
    } else {
        $erro_login = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
            align-items: center;
        }

        /* FAIXA AZUL DO TOPO */
        .header-topo {
            width: 100%;
            height: 65px;
            background-color: #85b4f2;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            padding: 20px;
        }

        h3 {
            font-size: 24px;
            color: #000000;
            margin-top: 15px;
            margin-bottom: 25px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
        }

        .card-login {
            background-color: #85b4f2;
            width: 100%;
            max-width: 480px;
            padding: 35px 40px;
            border-radius: 18px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .campo-grupo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .campo-grupo label {
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            width: 80px;
            text-align: right;
            margin-right: 15px;
        }

        .campo-grupo input {
            flex: 1;
            background-color: #ffffff;
            border: none;
            height: 38px;
            border-radius: 20px;
            padding: 0 15px;
            font-size: 16px;
            outline: none;
        }

        /* ÁREA DOS BOTÕES (ENTRAR / LIMPAR ESTILO PÍLULA BRANCA) */
        .botoes-grupo {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-top: 30px;
            gap: 20px;
        }

        .botoes-grupo input[type="submit"],
        .botoes-grupo input[type="reset"] {
            background-color: #ffffff;
            color: #85b4f2;
            border: none;
            padding: 10px 28px;
            border-radius: 18px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            font-family: Georgia, 'Times New Roman', Times, serif;
            transition: opacity 0.2s;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }

        .botoes-grupo input[type="submit"]:hover,
        .botoes-grupo input[type="reset"]:hover {
            opacity: 0.9;
        }

        .msg-erro {
            color: #d32f2f;
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
        }
    </style>

</head>

<body>
    
    <!-- Faixa azul superior -->
    <div class="header-topo"></div>

    <main><!--parte importante da pag:-->
        <h3>Faça login para continuar</h3>
        
        <!-- Caixinha azul do login -->
        <div class="card-login"><!--div para o CSS-->
            <form action="" method="post"><!--forms simples:-->

                <div class="campo-grupo">
                    <label for="email">E-mail: </label>
                    <input type="text" name="email" id="email" required>
                </div>

                <div class="campo-grupo">
                    <label for="senha">Senha: </label>
                    <input type="password" name="senha" id="senha" required>
                </div>

                <div class="botoes-grupo">
                    <input type="submit" value="Entrar">
                    <input type="reset" value="Limpar">
                </div>

            </form>
        </div>

        <?php if (!empty($erro_login)): ?>
            <p class="msg-erro"><?php echo $erro_login; ?></p> <!--mensagem de erro-->
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>