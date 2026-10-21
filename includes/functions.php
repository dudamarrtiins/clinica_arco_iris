<?php
require_once __DIR__ . '/../database/connect.php'; 
// puxa a conexão com o banco


//            CREATE - CADASTRAR PACIENTE/ ADICIONAR
function cadastrar($conexao, $nome, $cpf, $nasc, $idade, $convenio, $sexo, $telefone)
{
    // CALCULO EXCLUSIVO EM PHP (Sem JavaScript)
    if (!empty($nasc)) {
        $dataNascimento = new DateTime($nasc);
        $hoje = new DateTime();
        // Calcula a diferença em anos exatos
        $idade = $hoje->diff($dataNascimento)->y; 
    }

    // Validação da idade mínima
    if ($idade < 3) {
        echo "Erro: O paciente precisa ter no mínimo 3 anos de idade!";
        return; // Aborta antes de salvar no banco
    }

    require_once __DIR__ . '/../database/connect.php'; 

    $id_usuario = $_SESSION['id'];

    $sql = "INSERT INTO paciente (nome, cpf, nasc, idade, convenio, sexo, id_usuario) 
            VALUES (:nome, :cpf, :nasc, :idade, :convenio, :sexo, :id_usuario)";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":cpf", $cpf);
        $stmt->bindParam(":nasc", $nasc);
        $stmt->bindParam(":idade", $idade, PDO::PARAM_INT); // Salva o valor calculado pelo PHP
        $stmt->bindParam(":convenio", $convenio);
        $stmt->bindParam(":sexo", $sexo);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);

        $stmt->execute();

        $idPaciente = $conexao->lastInsertId(); 

        $sqlTelefone = "INSERT INTO telefone (id_paciente, telefone) VALUES (:id_paciente, :telefone)";
        $stmtTelefone = $conexao->prepare($sqlTelefone);
        $stmtTelefone->bindParam(":id_paciente", $idPaciente);
        $stmtTelefone->bindParam(":telefone", $telefone);
        $stmtTelefone->execute();

        echo "Paciente inserido com sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} // TERMINADAAAA, NÃO MEXER EM NADA

//                  SELECT - PRONTUARIO - VER TODOS

function prontuario($conexao, $id_usuario)
{
    // Pega o ID do usuário conectado na sessão atual

    $sqlPacientes = "SELECT * FROM paciente WHERE id_usuario = $id_usuario";
    // selecione tudo da tabela paciente onde o id for igual ao id do user da sessão

    try {
        $stmt = $conexao->prepare($sqlPacientes);
        $stmt->execute();

        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pacientes as $paciente) {
            echo "ID: {$paciente['id']}<br>";
            echo "nome: {$paciente['nome']}<br>";
            // printa essas informações

            // Busca O TELEFONE DESTE PACIENTE específico
            $sqlTel = "SELECT telefone FROM telefone WHERE id_paciente = :id_paciente";

            $stmtTel = $conexao->prepare($sqlTel); // prepare esse sql

            $stmtTel->bindParam(":id_paciente", $paciente['id']); //puxa pelo id

            $stmtTel->execute(); //execulta

            $sqltel = $stmtTel->fetch(PDO::FETCH_ASSOC);

            echo "<hr>";
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage(); // mensagem de erro
    }
} // TERMINADA, NÃO MEXER EM NADA, comecei mexer, tem que terminar, terminadaaa!

//                 DELETE - delete/Apagar

// DELETE - delete/Apagar
function apagar($conexao, $nome)
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    try {
        // busca o ID do paciente pelo nome E pelo dono da conta (id_usuario)
        $sqlBusca = "SELECT id FROM paciente WHERE nome = :nome AND id_usuario = :id_usuario";
        $stmtBusca = $conexao->prepare($sqlBusca);
        $stmtBusca->bindParam(":nome", $nome);
        $stmtBusca->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmtBusca->execute(); // execulte
        
        $paciente = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        if ($paciente) {
            $idPaciente = $paciente['id'];

            // Apaga os telefones do paciente
            // sql = delete tudo da tabela de telefones onde o id for igual o id do paciente
            $sqlTelefone = "DELETE FROM telefone WHERE id_paciente = :id_paciente";
            $stmtTelefone = $conexao->prepare($sqlTelefone); // prepare o sql 
            $stmtTelefone->bindParam(":id_paciente", $idPaciente); // puxa o id 
            $stmtTelefone->execute(); // execulta

            // Apaga o paciente garantindo o id do usuário
            // sql = delet tudo da tabela paciente onde o id foir igual do id do paciente e do usuario da sessão
            $sqlPaciente = "DELETE FROM paciente WHERE id = :id AND id_usuario = :id_usuario";
            $stmtPaciente = $conexao->prepare($sqlPaciente); // prepara
            $stmtPaciente->bindParam(":id", $idPaciente); // puxa o id
            $stmtPaciente->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmtPaciente->execute(); // execulta

            echo "Paciente $nome removido com sucesso!"; // printa que tudo deu certo
        } else { // se não for:
            echo "Paciente não encontrado"; // avisando que deu errado
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage(); // mensagem de erro
    }
} // TERMINADA, NÃO MEXER

                                                    // ATUALIZAR 

function atualizar($conexao, $nome, $cpf, $nasc, $idade, $convenio, $sexo, $telefone) // parametros
{
    require_once __DIR__ . '/../database/connect.php';  

    // pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];
   
    // atualiza os dados APENAS se o paciente pertencer ao usuário logado
    $sql = "UPDATE paciente 
            SET cpf = :cpf, nasc = :nasc, idade = :idade, convenio = :convenio, sexo = :sexo 
            WHERE nome = :nome AND id_usuario = :id_usuario";
    // sql = atualiza a tab pacientes onde for coluna X coloque informação X (recebida do forms) apenas onde o nome for igual ao nome recebido do forms e onde o id for igual ao id da sessão atual

    try { 
        $stmt = $conexao->prepare($sql); // prepare o sql
        $stmt->bindParam(":nome", $nome); // faz a mudança
        $stmt->bindParam(":cpf", $cpf);
        $stmt->bindParam(":nasc", $nasc);
        $stmt->bindParam(":idade", $idade);
        $stmt->bindParam(":convenio", $convenio);
        $stmt->bindParam(":sexo", $sexo);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);

        $stmt->execute(); // execulta a mudança

        // Atualiza o telefone onde id_paciente é igual ao $id recebido no parâmetro
        $sqlTelefone = "UPDATE telefone SET telefone = :telefone WHERE id_paciente = :id_paciente";
        // sql = atualize a tab telefone onde telefone for igual ao telefone (recebido do forms) 

        $stmtTelefone = $conexao->prepare($sqlTelefone); // prepare o sql da tabela telefone
        $stmtTelefone->bindParam(":id_paciente", $id); // usa $id diretamente
        $stmtTelefone->bindParam(":telefone", $telefone);
        $stmtTelefone->execute();

        echo "Paciente atualizado com sucesso!"; // print que deu certo
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage(); // mensagem de erro
    }
} // TERMINADA, NÃO MEXER EM NADA


//                                   SELECT - CONSULTAR - VER UM SÓ
function consultar($conexao, $nome) // passando os parametros
{
    // pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "SELECT * FROM paciente WHERE LOWER(nome) LIKE LOWER(:nome) AND id_usuario = :id_usuario";
    // sql = selecione tudo da tabela pacientes onde nome for igual nome e onde o id do user for igual ao do user logado
    // LOWER - para conseguir independente se for minusculo ou maiusculo
    // LIKE - para buscar palavras "parecidas" neste caso, para buscar o primeiro nome 

    try {
        $stmt = $conexao->prepare($sql); // prepare esse sql

        $nomeBusca = "%" . $nome . "%"; // se o nome for parecido (%) 
        $stmt->bindParam(":nome", $nomeBusca); // :nome ta tab vai ser conparado com o $nomeBusca
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute(); // execulta

        $paciente = $stmt->fetch(PDO::FETCH_ASSOC); // printando as infor
        echo "ID: {$paciente['id']} <br>";
        echo "Nome: {$paciente['nome']}<br>";
        echo "CPF: {$paciente['cpf']}<br>";
        echo "Nascimento: {$paciente['nasc']}<br>";
        echo "Idade: {$paciente['idade']}<br>";
        echo "Convenio: {$paciente['convenio']}<br>";
        echo "Sexo: {$paciente['sexo']}<br>";
      
        // mesma coisa que o primeiro sql, só que com outra tabela agora:
        $sqlTel = "SELECT telefone FROM telefone WHERE id_paciente = :id_paciente";
        // sql = selecione  na tabela telefone onde for igual ao telefone recebido e onde o id for igual ao id do paciente

        $stmtTel = $conexao->prepare($sqlTel); // prepara o sql do telefone

        $stmtTel->bindParam(":id_paciente", $paciente['id']); // puxa pelo id

        $stmtTel->execute(); // execulta

        $sqltel = $stmtTel->fetch(PDO::FETCH_ASSOC);

        if ($sqltel) {
            echo "telefone: {$sqltel['telefone']}<br>";
        } else {
            echo "telefone: Não cadastrado<br>";
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} // TERMINADA, NÃO MEXER


//                  ENTRADA DE DINHEIRO - GESTAO

// ENTRADA DE DINHEIRO - GESTAO

function entrada($conexao, $valor_entrada, $dia, $nomePaciente, $metodo) // passando os parametros
{
    // Validação: aceita apenas o valor 200 (seja número ou string "200" / "200.00")
    if ((float)$valor_entrada != 200.00) {
        echo " O valor da entrada deve ser exatamente R$ 200,00!";
        return; // Encerra a função e não executa o INSERT
    }

    require_once __DIR__ . '/../database/connect.php'; // mostra o caminho 

    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "INSERT INTO entrada (valor_entrada, dia, id_usuario, nomepaciente, metodo) VALUES (:entrada, :dia, :id_usuario, :nomePaciente, :metodo)";  

    try { // ta atribuindo os valores para as colunas
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":entrada", $valor_entrada);
        $stmt->bindParam(":dia", $dia);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":nomePaciente", $nomePaciente);
        $stmt->bindParam(":metodo", $metodo, PDO::PARAM_INT);

        $stmt->execute();// execultando

        echo "Registrado Ganho!"; // printa que deu certo

    } catch (PDOException $e) { // tratamento de erro
        echo "Erro: " . $e->getMessage();
    }
}


//                                               SAIDA DE DINHEIRO - GESTAO

// SAIDA DE DINHEIRO - GESTAO

function saida($conexao, $valor_saida, $dia, $motivo) // atribuindo os parametros
{
    require_once __DIR__ . '/../database/connect.php'; // mostra o caminho 

    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "INSERT INTO saida (valor_saida, dia, id_usuario, motivo) VALUES (:saida, :dia, :id_usuario, :motivo)";  
    // sql = adicione na tabela saida (colunas) os valores (valores recebidos no forms)

    try { // ta atribuindo os valores para as colunas
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":saida", $valor_saida);
        $stmt->bindParam(":dia", $dia);
        $stmt->bindParam(":id_usuario", $id_usuario,);
        $stmt->bindParam(":motivo", $motivo,
        PDO::PARAM_INT);

        $stmt->execute(); // execulte
        echo "Registrado Gasto!"; // printa que deu certo

    } catch (PDOException $e) { // tratamento de erro
        echo "Erro: " . $e->getMessage();
    }
} // terminada, não mexer


//                                                 EXTRATO

function extrato($conexao) // passando so o parametro de conexao com o banco
{
    require_once __DIR__ . '/../database/connect.php';

    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $stmtE = $conexao->prepare("SELECT * FROM entrada WHERE id_usuario = :id_usuario");
    // sql = selecione tudo na tabela entrada onde id do usuario for igual ao id logado na sessão
    $stmtE->bindParam(":id_usuario", $id_usuario);// vendo se os ids são iguais
    $stmtE->execute(); // execultando
    $entradas = $stmtE->fetchAll(PDO::FETCH_ASSOC);

    
    foreach ($entradas as $e) { // printando as informações
        echo "<br>Valor Ganho: " . $e['valor_entrada'] . "<br>";
        echo "<br>Paciente: " . $e['nomepaciente'] . "<br>";
        echo "<br>Método de Pagamento: " . $e['metodo'] . "<br>";
        echo "Data: " . $e['dia'] . "<br><hr>";
    }

    
    $stmtS = $conexao->prepare("SELECT * FROM saida WHERE id_usuario = :id_usuario");
    // sql = conexao prepare o sql para selecionar tudo da tabela saida onde o id usuario for igual ao id ususario
    $stmtS->bindParam(":id_usuario", $id_usuario); // vendo os ids
    $stmtS->execute(); // execultando
    $saidas = $stmtS->fetchAll(PDO::FETCH_ASSOC);

    
    foreach ($saidas as $s) { // printando
        echo "Valor Gasto: " . $s['valor_saida'] . "<br>";
        echo "Motivo: " . $s['motivo'] . "<br>";
        echo "Data: " . $s['dia'] . "<br><hr>";
    }
} // terminada

//                                                        Cadastrar USER
function cadastrar_user($conexao, $email, $senha) { // passando os parametros
    $sql = "INSERT INTO usuarios (email, senha) VALUES (:email, :senha)";
    // sql = insira na tabela usuarios (colunas) os valores (valores recebidos no forms)
    $stmt = $conexao->prepare($sql);
    $stmt->execute([':email' => $email, ':senha' => $senha]); // execulte, transformando os valores em variaveis
    
    // Retorna o ID do usuário cadastrado
    return $conexao->lastInsertId(); 
}

//                                                       VE SE O USER EXISTE 

function consulta_user($conexao, $email) // passando os parametros
{

    $sql = "SELECT id, email, senha FROM usuarios WHERE email = :email";
    // sql = selecione as colunas XXX da tabela usuarios onde o email for igual ao recebido no forms

    try {
        $stmt = $conexao->prepare($sql); // prepare o sql
        $stmt->bindParam(":email", $email); // email vira email
        $stmt->execute(); // execulte

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario; // para globalizar ela
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

//                                                    AGENDAMENTO 
//  Marca um novo atendimento (status inicia automaticamente como 'agendada')
function agendarConsulta($conexao, $id_paciente, $dia, $hora) // passando os parametros
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "INSERT INTO consultas (id_paciente, dia, hora, status, id_usuario) 
            VALUES (:id_paciente, :dia, :hora, 'agendada', :id_usuario)";
    // sql = insira na tabela consulta (nas colunas) os valores (valores recebidos no forms)
    
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao agendar: " . $e->getMessage();
        return false;
    }
} // terminado

// 2. Atualiza o status da consulta ('agendada', 'realizada', 'cancelada')
function atualizarStatusConsulta($conexao, $id_consulta, $novo_status)
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "UPDATE consultas SET status = :status WHERE id = :id AND id_usuario = :id_usuario";
    
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':status', $novo_status);
        $stmt->bindParam(':id', $id_consulta);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao atualizar status: " . $e->getMessage();
        return false;
    }
} // terminado

// 3. Atalho para cancelar a consulta
function cancelarConsulta($conexao, $id_consulta)
{
    return atualizarStatusConsulta($conexao, $id_consulta, 'cancelada');
}

// 4. Apaga definitivamente do banco de dados (Delete)
function deletarConsulta($conexao, $id_consulta)
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "DELETE FROM consultas WHERE id = :id AND id_usuario = :id_usuario";
    
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $id_consulta);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao deletar: " . $e->getMessage();
        return false;
    }
} // terminada

// 5. Remarca a data e o horário de uma consulta
function remarcarConsulta($conexao, $id_consulta, $novo_dia, $nova_hora)
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "UPDATE consultas SET dia = :dia, hora = :hora WHERE id = :id AND id_usuario = :id_usuario";
    
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':dia', $novo_dia);
        $stmt->bindParam(':hora', $nova_hora);
        $stmt->bindParam(':id', $id_consulta);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao remarcar: " . $e->getMessage();
        return false;
    }
} // terminada

// 6. Lista todas as consultas marcadas
function listarConsultas($conexao)
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "SELECT * FROM consultas WHERE id_usuario = :id_usuario ORDER BY dia ASC, hora ASC";
    
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao buscar consultas: " . $e->getMessage();
        return [];
    }
} // terminada, acho que nao vai funcionar!

                                                       // GRAFICO
function obterResumoFinanceiro($conexao)
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    try {
        // Soma a coluna 'valor_entrada' da tabela 'entrada'
        $sqlEntradas = "SELECT COALESCE(SUM(valor_entrada), 0) AS total FROM entrada WHERE id_usuario = :id_usuario";
        $stmtEntradas = $conexao->prepare($sqlEntradas);
        $stmtEntradas->bindParam(':id_usuario', $id_usuario);
        $stmtEntradas->execute();
        $totalEntradas = (float) $stmtEntradas->fetchColumn();

        // Soma a coluna 'valor_saida' da tabela 'saida'
        $sqlSaidas = "SELECT COALESCE(SUM(valor_saida), 0) AS total FROM saida WHERE id_usuario = :id_usuario";
        $stmtSaidas = $conexao->prepare($sqlSaidas);
        $stmtSaidas->bindParam(':id_usuario', $id_usuario);
        $stmtSaidas->execute();
        $totalSaidas = (float) $stmtSaidas->fetchColumn();

        return [
            'entradas' => $totalEntradas,
            'saidas'   => $totalSaidas
        ];
    } catch (PDOException $e) {
        return ['entradas' => 0, 'saidas' => 0];
    } // terminada
}


                                           //   DESCOBRE A IDADE
function idade($conexao, $idade){
    require_once __DIR__ . '/../database/connect.php';
    $id_usuario = $_SESSION['id'];

}

?>