<?php
session_start(); // inicia a sessão para as variaveis guardadas do user logado
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit(); // se não tem id ele manda fazer login
}

require_once __DIR__ . '/../includes/functions.php';

// 1. Pega o ID do usuário logado na sessão
$id_usuario = $_SESSION['id'];

// Busca lista de pacientes para o seletor (dropdown)
$stmtP = $conexao->prepare("SELECT id, nome FROM paciente WHERE id_usuario = :id_usuario ORDER BY nome ASC");
$stmtP->bindParam(':id_usuario', $id_usuario);

$stmtP->execute();
$pacientes = $stmtP->fetchAll(PDO::FETCH_ASSOC); // guarda tudo em um array

if ($_SERVER['REQUEST_METHOD'] == 'POST') { /* verifica se a pag recarregou
pq o user ciclou no bnt de salvar, que envia o POST */
    $id_paciente = $_POST['id_paciente']; // pega o id do paciente
    $dia = $_POST['dia']; // pega a data
    $hora = $_POST['hora']; //pega a hora

    if (agendarConsulta($conexao, $id_paciente, $dia, $hora)) { /* chama a função
    se retornar como true ele toda que deu certo */
        header("Location: agenda.php");
        exit();
    } else { // se der erro:
        $erro = "Erro ao realizar agendamento.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Agendamento</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        body {
            background-color: #fff3ed; /* Fundo bege */
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
            max-width: 850px;
            margin: 0 auto;
        }

        /* TÍTULO PRINCIPAL */
        h2 {
            font-size: 24px;
            color: #000000;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        /* FORMULÁRIO COMPLETO */
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* ESTILO DOS RÓTULOS (LABELS) */
        label {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 8px;
            display: block;
        }

        /* SELECT DE PACIENTE COM A COR BEGE DA IMAGEM */
        select {
            width: 100%;
            height: 48px;
            background-color: #fbebd9; /* Tom bege da pílula */
            border: none;
            border-radius: 24px; /* Formato pílula arredondado */
            padding: 0 20px;
            font-size: 16px;
            color: #000000;
            outline: none;
            font-family: Georgia, 'Times New Roman', Times, serif;
            margin-bottom: 25px;
            cursor: pointer;
        }

        /* INPUT DE DATA */
        input[type="date"] {
            width: 100%;
            height: 48px;
            background-color: #fbebd9; /* Tom bege da pílula */
            border: none;
            border-radius: 24px;
            padding: 0 20px;
            font-size: 16px;
            color: #000000;
            outline: none;
            font-family: Georgia, 'Times New Roman', Times, serif;
            margin-bottom: 25px;
            cursor: pointer;
        }

        /* INPUT DE HORA (COMPACTO E LIMPO) */
        input[type="time"] {
            width: 160px;
            height: 40px;
            background-color: transparent;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 16px;
            color: #000000;
            outline: none;
            font-family: Georgia, 'Times New Roman', Times, serif;
            margin-bottom: 25px;
            cursor: pointer;
        }

        /* CONTAINER DOS BOTÕES INFERIORES */
        .botoes-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
        }

        /* BOTÕES SALVAR CONSULTA E VOLTAR COMO PÍLULAS AZUIS */
        .botoes-container input[type="submit"],
        .botoes-container a {
            background-color: #85b4f2;
            color: #ffffff;
            border: none;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            font-family: Georgia, 'Times New Roman', Times, serif;
            padding: 12px 35px;
            border-radius: 15px;
            transition: opacity 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            display: inline-block;
        }

        .botoes-container input[type="submit"]:hover,
        .botoes-container a:hover {
            opacity: 0.9;
            text-decoration: none;
        }

        /* MENSAGEM DE ERRO */
        .msg-erro {
            color: #d32f2f;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Marcar Novo Atendimento</h2>

        <?php if (isset($erro)): ?>
            <p class="msg-erro"><?php echo $erro; ?></p>
        <?php endif; ?> <!--se tiver retornado como false, ele roda essa mensagem de erro-->

        <form method="POST" action="">

            <label for="id_paciente">Paciente:</label>

            <select name="id_paciente" id="id_paciente" required>

                <option value="">-- Selecione o Paciente --</option>
                <?php foreach ($pacientes as $p): ?> <!--passa por cada paciente-->
                    <option value="<?php echo $p['id']; ?>"><?php echo $p['nome']; ?></option>
                <!--manda o id, e o nome do paciente -->
                <?php endforeach; ?>
            </select>

            <label for="dia">Data:</label> <!-- recebe o dia -->
            <input type="date" name="dia" id="dia" required>
            

            <label for="hora">Horário:</label> <!--recebe a hora-->
            <input type="time" name="hora" id="hora" required>

            <div class="botoes-container">
                <input type="submit" value="Salvar Consulta"> <!-- btn de mandar o que foi digitado-->
                <a href="agenda.php">Voltar</a> <!--bnt de voltar para o painel-->
            </div>
        </form>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>