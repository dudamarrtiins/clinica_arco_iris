<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../includes/functions.php';

$id_consulta = $_GET['id'] ?? null;
/* quando o user clicou no botao de remarcar ele pega o parâmetro do id, se 
a URL não tiver pegado e marca com null */

if (!$id_consulta) {
    header("Location: agenda.php");
    exit();
}

// Processa a atualização
if ($_SERVER['REQUEST_METHOD'] == 'POST') { /*
o bloco roda se o user clicou em atualizar agendamento, ai ele pega a nova data
e o novo horario digitado */
    $novo_dia = $_POST['dia'];
    $nova_hora = $_POST['hora'];

    if (remarcarConsulta($conexao, $id_consulta, $novo_dia, $nova_hora)) {
        // chama a função e manda os parâmetros para fazer o update
        header("Location: agenda.php"); // se der certo
        exit();
    } else { // se der errado:
        $erro = "Erro ao remarcar consulta.";
    }
}

// busca os dados atuais da consulta
$stmt = $conexao->prepare("SELECT * FROM consultas WHERE id = :id");
// sql = selecione tudo da tabela consultas onde o id = id da consulta

$stmt->bindParam(':id', $id_consulta); // gurda na caixinha
$stmt->execute(); //execulta
$consulta = $stmt->fetch(PDO::FETCH_ASSOC); // traz em forma de array
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remarcar Consulta</title>

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
            margin-bottom: 35px;
        }

        /* FORMULÁRIO COMPLETO */
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* GRUPO DE CAMPO: NOVA DATA */
        .campo-grupo-data {
            margin-bottom: 35px;
            width: 100%;
        }

        .campo-grupo-data label {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 12px;
            display: block;
        }

        .campo-grupo-data input[type="date"] {
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

        /* GRUPO DE CAMPO: NOVO HORÁRIO (LADO A LADO) */
        .campo-grupo-hora {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .campo-grupo-hora label {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
        }

        .campo-grupo-hora input[type="time"] {
            width: 160px;
            height: 45px;
            background-color: #85b4f2;
            border: none;
            border-radius: 15px;
            padding: 0 15px;
            font-size: 16px;
            color: #000000;
            outline: none;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        /* ÁREA DE BOTÕES INFERIOR */
        .botoes-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            margin-top: 10px;
        }

        /* BOTÃO DE SUBMIT (TEXTO SIMPLES) */
        input[type="submit"] {
            background: none;
            border: none;
            color: #000000;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            font-family: Georgia, 'Times New Roman', Times, serif;
            padding: 0;
        }

        input[type="submit"]:hover {
            text-decoration: underline;
        }

        /* BOTÃO CANCELAR (PÍLULA AZUL) */
        .btn-cancelar {
            background-color: #85b4f2;
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 35px;
            border-radius: 15px;
            display: inline-block;
            transition: opacity 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-cancelar:hover {
            opacity: 0.9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Remarcar Consulta</h2>

        <?php if (isset($erro)) echo "<p style='color:red; margin-bottom:20px;'>$erro</p>"; ?>

        <form method="POST" action="">
            <div class="campo-grupo-data">
                <label for="dia">Nova Data</label>
                <input type="date" name="dia" id="dia" value="<?php echo $consulta['dia']; ?>" required>
            </div>

            <div class="campo-grupo-hora">
                <label for="hora">Novo Horario</label>
                <input type="time" name="hora" id="hora" value="<?php echo $consulta['hora']; ?>" required>
            </div>

            <div class="botoes-container">
                <input type="submit" value="Atualizar Agendamento">
                <a href="agenda.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>