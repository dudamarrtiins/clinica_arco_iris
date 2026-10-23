<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/includes/functions.php';

                        // DADOS DO CALENDÁRIO 
// Pega o mês e ano via GET enviados pela URL, se não for ele usa o padrao
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

// Trata viradas de ano ao clicar nos botões (mês 0 vira dezembro do ano anterior, mês 13 vira janeiro do próximo ano)
if ($mes < 1) {
    $mes = 12;
    $ano--;
} elseif ($mes > 12) {
    $mes = 1;
    $ano++;
}
/* se o user clicar em anterior (jan) ele vai fazer assim:
mes 1-1 = 0, ai o mes ajusta para dezembro e volta um ano,
se el clicar em proximo em dez ele faz assim:
mes 12 + 1 = 13, ai muda para o mes 1 (jan) e avança um ano */

// formatação para exibição com 2 dígitos no cálculo, ou seja o x vira 0x
$mesFormatado = str_pad($mes, 2, '0', STR_PAD_LEFT);

// calculos de navegação (mês anterior e próximo mês) assim ele prepara os valores colocados no botões
$mesAnt = $mes - 1;
$anoAnt = $ano;
if ($mesAnt < 1) { $mesAnt = 12; $anoAnt--; }

$mesProx = $mes + 1;
$anoProx = $ano;
if ($mesProx > 12) { $mesProx = 1; $anoProx++; }

// o nome dos meses
$nomesMeses = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

// -------------------------------------------------------------------

$diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
// ve quantos dias o mes selecionado tem

$primeiroDiaSemana = date('w', strtotime("$ano-$mesFormatado-01"));
// ve quando que cai o primeiro dia do mes

$todasConsultas = listarConsultas($conexao); 

$agenda = [];
foreach ($todasConsultas as $c) {
    if (date('m', strtotime($c['dia'])) == $mesFormatado && date('Y', strtotime($c['dia'])) == $ano) {
        $dia = (int)date('d', strtotime($c['dia']));
        $agenda[$dia][] = $c;
    }
}
/* guarda todo esse resultado em um array */

                           // DADOS DO GRÁFICO 

$financas = obterResumoFinanceiro($conexao);
// execulta a função do resumo e calcula o $saldo

$totalEntradas = $financas['entradas'];
$totalSaidas   = $financas['saidas'];
$saldo         = $totalEntradas - $totalSaidas;

// calculo das porcentagens das barras para o CSS e a %
$maxValor = max($totalEntradas, $totalSaidas, 1);
$pctEntradas = min(100, round(($totalEntradas / $maxValor) * 100));
$pctSaidas   = min(100, round(($totalSaidas / $maxValor) * 100));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Geral</title>
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
            max-width: 1100px;
            margin: 0 auto;
        }

        /* TÍTULO PRINCIPAL */
        h2 {
            font-size: 26px;
            color: #000000;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }

        /* CONTAINER DOS CARDS CENTRALIZADO */
        .dashboard-container {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }

        /* CARD DO GRÁFICO FINANCEIRO */
        .card-grafico {
            background: #ffffff;
            border-radius: 18px;
            padding: 25px;
            flex: 1;
            min-width: 320px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-grafico h3 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }

        .barra-grupo { 
            margin-bottom: 18px; 
        }

        .barra-label { 
            font-size: 14px; 
            font-weight: bold; 
            margin-bottom: 6px; 
            display: flex; 
            justify-content: space-between; 
        }

        .barra-fundo { 
            background: #e0e0e0; 
            height: 22px; 
            border-radius: 12px; 
            overflow: hidden; 
        }

        .barra-preenchimento { 
            height: 100%; 
            border-radius: 12px; 
            transition: width 0.5s ease; 
        }

        .cor-entrada { background-color: #2e7d32; } /* Verde */
        .cor-saida { background-color: #c62828; }   /* Vermelho */

        .resumo-cards { 
            display: flex; 
            justify-content: space-between; 
            margin-top: 20px; 
            padding-top: 15px; 
            border-top: 1px solid #eee; 
        }

        .resumo-box { text-align: center; }
        .resumo-box small { color: #666; font-size: 12px; }
        .resumo-box strong { display: block; font-size: 15px; margin-top: 4px; }

        /* CARD DO CALENDÁRIO */
        .card-calendario { 
            flex: 2; 
            min-width: 500px;
            background: #ffffff;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* NAVEGAÇÃO DO MÊS NO CALENDÁRIO */
        .calendario-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .calendario-header h3 {
            font-size: 20px;
            color: #000000;
            font-weight: bold;
        }

        .btn-nav-mes {
            background-color: #85b4f2;
            color: #ffffff;
            text-decoration: none;
            padding: 6px 16px;
            border-radius: 14px;
            font-weight: bold;
            font-size: 14px;
            transition: opacity 0.2s;
        }

        .btn-nav-mes:hover {
            opacity: 0.85;
        }

        .calendario { 
            width: 100%; 
            border-collapse: collapse; 
            border-radius: 10px;
            overflow: hidden;
        }

        .calendario th { 
            background: #85b4f2; 
            color: white; 
            padding: 10px; 
            width: 14%; 
            font-size: 14px;
        }

        .calendario td { 
            border: 1px solid #eee; 
            height: 75px; 
            vertical-align: top; 
            padding: 6px; 
            background: #fff; 
        }

        .vazio { background: #fafafa; }
        .num-dia { font-weight: bold; font-size: 12px; color: #333; }
        
        .item-agenda { 
            display: block; 
            font-size: 10px; 
            padding: 3px 5px; 
            margin-top: 3px; 
            color: white; 
            text-decoration: none; 
            border-radius: 4px; 
            font-weight: bold;
        }

        .status-agendada { background: #0288d1; }
        .status-realizada { background: #388e3c; }
        .status-cancelada { background: #d32f2f; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <h2>Dashboard</h2>

        <div class="dashboard-container">
            
            <!-- GRÁFICO FINANCEIRO -->
            <div class="card-grafico">
                <h3>Balanço Financeiro</h3>

                <!-- Barra de Entradas -->
                <div class="barra-grupo">
                    <div class="barra-label">

                        <span style="color: #2e7d32;">Entradas</span>

                        <span>R$ <?php echo number_format($totalEntradas, 2, ',', '.'); ?></span>
                    <!--formata os valores para o nosso padrao de reais-->

                    </div>
                    <div class="barra-fundo">
                        <div class="barra-preenchimento cor-entrada" style="width: <?php echo $pctEntradas; ?>%;"></div>
                        <!---- largura em % da barra verde
                    </div>
                </div>

                <!-- Barra de Saídas, funciona igua ao da entrda -->
                <div class="barra-grupo">
                    <div class="barra-label">
                        <span style="color: #c62828;">Saídas</span>
                        <span>R$ <?php echo number_format($totalSaidas, 2, ',', '.'); ?></span>
                    </div>
                    <div class="barra-fundo">
                        <div class="barra-preenchimento cor-saida" style="width: <?php echo $pctSaidas; ?>%;"></div>
                    </div>
                </div>

                <!-- Resumo Numérico -->
                <div class="resumo-cards">

                    <div class="resumo-box">
                        <small>Total Entradas</small>
                        <strong style="color: #2e7d32;">R$ <?php echo number_format($totalEntradas, 2, ',', '.'); ?></strong>
                    </div> <!--define a cor na hora do print-->

                    <div class="resumo-box">
                        <small>Total Saídas</small>
                        <strong style="color: #c62828;">R$ <?php echo number_format($totalSaidas, 2, ',', '.'); ?></strong>
                    </div>

                    <div class="resumo-box">
                        <small>Saldo Atual</small>
                        <strong style="color: <?php echo $saldo >= 0 ? '#2e7d32' : '#c62828'; ?>;">
                            R$ <?php echo number_format($saldo, 2, ',', '.'); ?>
                        </strong>

                    </div>
                </div>
            </div>


            <!-- CALENDÁRIO DA AGENDA COM NAVEGAÇÃO -->
            <div class="card-calendario">
            <!--essa div vai recarregar a pag passando os parametros 
            de navegação do calendario (as continhas do mes)-->

                <div class="calendario-header">
                    <a href="?mes=<?php echo $mesAnt; ?>&ano=<?php echo $anoAnt; ?>" class="btn-nav-mes">&laquo; Anterior</a>
                    <h3>Agenda - <?php echo $nomesMeses[$mes] . ' / ' . $ano; ?></h3>
                    <a href="?mes=<?php echo $mesProx; ?>&ano=<?php echo $anoProx; ?>" class="btn-nav-mes">Próximo &raquo;</a>
                </div>

                <table class="calendario">
                    <tr>
                        <th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th>
                    </tr>
                    <tr>
                    <?php
                    for ($i = 0; $i < $primeiroDiaSemana; $i++) {
                        echo '<td class="vazio"></td>'; 
                        // ele gera os espaços vazios para alinhar a semana se precisar
                        // e gera uma celula td para cada dia do mes
                    }

                    $coluna = $primeiroDiaSemana;
                    /* o loop do dia vai do primeiro ate o ultimo dia do mes, 
                    a cada voltinha ele cria uma caixinha em branco */
                    for ($dia = 1; $dia <= $diasNoMes; $dia++) {
                        echo '<td>';
                        echo '<div class="num-dia">' . $dia . '</div>';

                        if (isset($agenda[$dia])) {
                            /* verifica se existe alguma consulta marcada, se sim roda: */
                            foreach ($agenda[$dia] as $c) {
                                // mostra todas as consultas do dia
                                $nome = $c['paciente_nome'] ?? "Paciente " . $c['id_paciente'];
                                $hora = substr($c['hora'], 0, 5); // corta o horario, para aparecer mais cerinho

                                $statusClass = 'status-' . $c['status']; // para dar a corzinha (css)
                                echo "<a href='app/status.php?id={$c['id']}' class='item-agenda {$statusClass}'>{$hora} - {$nome}</a>";
                                // cria o link clicavel da consulta
                            }
                        }

                        echo '</td>';
                        $coluna++;
                        if ($coluna % 7 == 0) {
                            echo '</tr><tr>';
                        }
                        /* a cada caixinha nova criada (uma semana completa) ele da
                        verdadeiro, fecha a linha atual da tab e abre uma nova (</tr>) */
                    }

                    while ($coluna % 7 != 0) {
                        echo '<td class="vazio"></td>';
                        $coluna++;
                    }
                    /* se não fechar uma semana ele cria caixinhas vazias
                     ate completar a semana, ai ele fecha com tr*/
                    ?>
                    </tr>
                </table>
            </div>

        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>