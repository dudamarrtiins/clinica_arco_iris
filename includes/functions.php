<?php
require_once __DIR__ . '/../database/connect.php'; 
// puxa a conexão com o banco


//            CREATE - CADASTRAR PACIENTE/ ADICIONAR
//recebe o parametro de conexão e as infor do forms
function cadastrar($conexao, $nome, $cpf, $nasc, $idade, $convenio, $sexo, $telefone)
{  
    // idade 
    if (!empty($nasc)) { // trava de segurançã, SE $nasc NÃO ESTIVER VAZIO
        $dataNascimento = new DateTime($nasc); // converte a string para manipulavel, assim podendo ser utilizada
        $hoje = new DateTime(); //pega a data de hoje
        // Calcula a diferença em anos exatos
        $idade = $hoje->diff($dataNascimento)->y; // o diff calcula a diferença
        // o ->y mostra so os anos
    }

    // Validação da idade mínima
    if ($idade < 3) { // se idade menor que 3 então:
        echo "Erro: O paciente precisa ter no mínimo 3 anos de idade!";
        return; // aborta antes de salvar no banco
    }

    require_once __DIR__ . '/../database/connect.php'; 

    $id_usuario = $_SESSION['id']; //pega o id do user logado

    $sql = "INSERT INTO paciente (nome, cpf, nasc, idade, convenio, sexo, id_usuario) 
            VALUES (:nome, :cpf, :nasc, :idade, :convenio, :sexo, :id_usuario)";
    // sql insira na tabela **, nas colunas *** os valores recebidos do forms
    // o :nome funciona como um "apelido"
    try { //tente:
    // se der erro ele manda uma mensagem falando
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":nome", $nome); //onde estiver o apelido, troque pelo valor 
        // que vai ser armazenado na $
        $stmt->bindParam(":cpf", $cpf); // o bindParam vai tratar e limpar o texto recebido
        $stmt->bindParam(":nasc", $nasc);
        $stmt->bindParam(":idade", $idade, PDO::PARAM_INT); // Salva o valor calculado pelo PHP
        $stmt->bindParam(":convenio", $convenio);
        $stmt->bindParam(":sexo", $sexo);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        // o PDO::PARAM_INT garante que o numero vai ser tratado como inteiro
        // deve usar ele em chaves estrangeiras, e idades
        // não usa em cpf e tele pois se começar com 0 o banco pode remover ao tentar "transformar" em inteiro
        $stmt->execute();

        $idPaciente = $conexao->lastInsertId(); 
        // o bancco cria um id para o paciente novo, ai a gente pega o id recem criado

        $sqlTelefone = "INSERT INTO telefone (id_paciente, telefone) VALUES (:id_paciente, :telefone)";
        $stmtTelefone = $conexao->prepare($sqlTelefone);
        $stmtTelefone->bindParam(":id_paciente", $idPaciente); //usa o idPaciente como chave estrangeira, assim relaciona as 2 tab
        $stmtTelefone->bindParam(":telefone", $telefone);
        $stmtTelefone->execute(); // execulta

        echo "Paciente inserido com sucesso!"; //deu certo
    } catch (PDOException $e) { //deu erro, mensagem de erro:
        echo "Erro: " . $e->getMessage();
    }
} // TERMINADAAAA, NÃO MEXER EM NADA

//                  SELECT - PRONTUARIO - VER TODOS

function prontuario($conexao, $id_usuario) // passa os paramentros
{
    $sqlPacientes = "SELECT * FROM paciente WHERE id_usuario = $id_usuario";
    // selecione tudo da tabela paciente onde o id for igual ao id do user da sessão

    try {
        $stmt = $conexao->prepare($sqlPacientes); //prepara o sql
        $stmt->execute(); //execulta

        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        /*
        o banco não recebe todas as inf prontas, na verdade elas vem em um objeto especial
        chamado PDO statement, quando o fetchAll() é chamado ele faz com que todas
        as linhas encontardas virem uma lista/array. o PDO cria um arry associativo,
        onde cada chave tem o nome da coluna da tabela
        */ 

        foreach ($pacientes as $paciente) { /* utiliza do loop para percorrer
        cada linha, passa de paciente por paciente */
            echo "ID: {$paciente['id']}<br>"; //printa
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
function apagar($conexao, $nome)
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    try {
        // busca o ID do paciente pelo nome E pelo dono da conta (id_usuario)
        $sqlBusca = "SELECT id FROM paciente WHERE nome = :nome AND id_usuario = :id_usuario";
        $stmtBusca = $conexao->prepare($sqlBusca);
        $stmtBusca->bindParam(":nome", $nome);
        $stmtBusca->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT); // n int
        $stmtBusca->execute(); // execulte
        
        $paciente = $stmtBusca->fetch(PDO::FETCH_ASSOC); // traz uma linha só da busca e guarda no $paciente

        if ($paciente) { // se idPaciente = $paciente
            $idPaciente = $paciente['id']; //pega o valor da coluna 'id' do paciente encontrado

            // Apaga os telefones do paciente
            // sql = delete tudo da tabela de telefones onde o id for igual o id do paciente
            $sqlTelefone = "DELETE FROM telefone WHERE id_paciente = :id_paciente";

            $stmtTelefone = $conexao->prepare($sqlTelefone); // prepare o sql 

            $stmtTelefone->bindParam(":id_paciente", $idPaciente); /* substitui o "apelido"
            do : para o numero real que esta na variavel $, ou seja = where id_paciente = 5 */

            $stmtTelefone->execute(); // execulta


        /* primeiro se apaga o telefone por estar relacionado com a tab de paciente,
        depois de apagar o tele, podemos apagar o paciente */

            // sql = delet tudo da tabela paciente onde o id for igual o id do paciente e do usuario da sessão
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

function atualizar($conexao, $nome, $cpf, $nasc, $convenio, $sexo, $telefone) // parametros
{
    require_once __DIR__ . '/../database/connect.php';  

    if (!empty($nasc)) {
        $dataNascimento = new DateTime($nasc);
        $hoje = new DateTime();
        $idade = $hoje->diff($dataNascimento)->y; 
    } else {
        $idade = 0; // Garante um valor numérico padrão caso não haja data
    }

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

      // busca o ID do paciente para atualizar o telefone
        $sqlBusca = "SELECT id FROM paciente WHERE nome = :nome AND id_usuario = :id_usuario";
        // selecione id na tabela paciente onde nome for = nome e o id_user = id_user
        $stmtBusca = $conexao->prepare($sqlBusca); // prepra sql

        $stmtBusca->bindParam(":nome", $nome);// o "apelido" fica salvo na $

        $stmtBusca->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT); // mesma coisa só que com o id

        $stmtBusca->execute(); // execulta
        
        $paciente = $stmtBusca->fetch(PDO::FETCH_ASSOC); // mostra uma linha só do array em $paciente

        if ($paciente) { //ve se encontrou algum $paciente
            $idPaciente = $paciente['id']; 
            // extrai e guarda apenas o número do 'id' que veio do banco dentro da variável $idPaciente

            $sqlTelefone = "UPDATE telefone SET telefone = :telefone WHERE id_paciente = :id_paciente";
            // atualize a tab tele onde a informação vai ser atualizada pelo valor recebido do forms/apelido, onde id_paciente....

            $stmtTelefone = $conexao->prepare($sqlTelefone); //prepra

            $stmtTelefone->bindParam(":id_paciente", $idPaciente, PDO::PARAM_INT); // salva a variavel no "apelido"
            $stmtTelefone->bindParam(":telefone", $telefone); // mesma coisa
            $stmtTelefone->execute(); //execulta

            echo "Paciente $nome atualizado com sucesso!"; //deu certo
        } else {
            echo "Paciente não encontrado!"; // deu errado
        }

    } catch (PDOException $e) { //tratamento de erro
        echo "Erro: " . $e->getMessage();
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

        $paciente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // printando as infor
        echo "ID: {$paciente['id']} <br>";
        echo "Nome: {$paciente['nome']}<br>";
        echo "CPF: {$paciente['cpf']}<br>";
        echo "Nascimento: {$paciente['nasc']}<br>";
        echo "Idade: {$paciente['idade']}<br>";
        echo "Convenio: {$paciente['convenio']}<br>";
        echo "Sexo: {$paciente['sexo']}<br>";
      
        // mesma coisa que o primeiro sql, só que com outra tabela agora:
        $sqlTel = "SELECT telefone FROM telefone WHERE id_paciente = :id_paciente";
        // sql = selecione  na tabela telefone  onde o id for igual ao id do paciente

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
    // aceita apenas o valor 200 
    if ((float)$valor_entrada != 200.00) { // float para ser decimal
        echo " O valor da entrada deve ser exatamente R$ 200,00!";
        return; // encerra a função e não executa o INSERT se for diferente
    }

    require_once __DIR__ . '/../database/connect.php'; // mostra o caminho 

    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "INSERT INTO entrada (valor_entrada, dia, id_usuario, nomepaciente, metodo) VALUES (:entrada, :dia, :id_usuario, :nomePaciente, :metodo)";  

    try { // ta atribuindo os valores para as colunas
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":entrada", $valor_entrada); // guarda o que veio do forms nas caixinhas/apelidos
        $stmt->bindParam(":dia", $dia);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":nomePaciente", $nomePaciente);
        $stmt->bindParam(":metodo", $metodo);

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
    require_once __DIR__ . '/../database/connect.php'; //carrega a conexão

    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $stmtE = $conexao->prepare("SELECT * FROM entrada WHERE id_usuario = :id_usuario");
    // sql = selecione tudo na tabela entrada onde id do usuario for igual ao id logado na sessão
    $stmtE->bindParam(":id_usuario", $id_usuario);// vendo se os ids são iguais
    $stmtE->execute(); // execultando
    $entradas = $stmtE->fetchAll(PDO::FETCH_ASSOC); // vira lista/array

    
    foreach ($entradas as $e) { /* o $e é uma variavel temporaria, para ser 
    uma unica entrada no extrato cada vez que passa pelo loop, ou seja
    passa no loop, pega a inf, e guarda na $e */
        // passa de registro por registro 
        // printando as informações
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

    
    foreach ($saidas as $s) { // passa de regristo por registro
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
    $stmt->execute([':email' => $email, ':senha' => $senha]); // execulte, guardando na caixinha
    /* faz um array associativo, ou seja pega os dados do forms, guarda na caixinha
    e manda tudo para o banco! */
    
    // Retorna o ID do usuário cadastrado
    return $conexao->lastInsertId(); // gera o id automatico que esta sendo resgatado
}

//                                                       VE SE O USER EXISTE 

function consulta_user($conexao, $email) // passando os parametros
{

    $sql = "SELECT id, email, senha FROM usuarios WHERE email = :email";
    // sql = selecione as colunas XXX da tabela usuarios onde o email for igual ao recebido no forms

    try {
        $stmt = $conexao->prepare($sql); // prepare o sql
        $stmt->bindParam(":email", $email); // email vira email, ou seja vai para a caixinha dele
        $stmt->execute(); // execulte

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // traz 1 linha (array associativo)
        return $usuario; // retorna a resposta, se o email não existe ele da false
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

//                                                    AGENDAMENTO - 6 functions
//  1 - marca um novo atendimento (status inicia automaticamente como 'agendada')
function agendarConsulta($conexao, $id_paciente, $dia, $hora) // passando os parametros
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "INSERT INTO consultas (id_paciente, dia, hora, status, id_usuario) 
            VALUES (:id_paciente, :dia, :hora, 'agendada', :id_usuario)";
    // sql = insira na tabela consulta (nas colunas) os valores (valores recebidos no forms)
    
    try {
        $stmt = $conexao->prepare($sql); //prepara
        // guarda tudo na caixinha
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute(); // execulta e retorna a resposta

        // se deu erro:
    } catch (PDOException $e) {
        echo "Erro ao agendar: " . $e->getMessage();
        return false;
    }
} // terminado

// 2 atualiza o status da consulta ('agendada', 'realizada', 'cancelada')
function atualizarStatusConsulta($conexao, $id_consulta, $novo_status) // parametros
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "UPDATE consultas SET status = :status WHERE id = :id AND id_usuario = :id_usuario";
    // sql = atulize a tab consulta a coluna status, para status, onde id for = id e o id_usuario for = ao id_usuario
    
    try {
        $stmt = $conexao->prepare($sql); // prepra
        //guarda na caixinha
        $stmt->bindParam(':status', $novo_status);
        $stmt->bindParam(':id', $id_consulta);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute(); // execulta e da a resposta

        //se deu erro:
    } catch (PDOException $e) {
        echo "Erro ao atualizar status: " . $e->getMessage();
        return false;
    }
} // terminado

// 3 para cancelar a consulta
function cancelarConsulta($conexao, $id_consulta)
{
    return atualizarStatusConsulta($conexao, $id_consulta, 'cancelada');
    // retorna a function de status e passa o status de cancelar
}

// 4 apaga a consulta
function deletarConsulta($conexao, $id_consulta) // parametrosss
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "DELETE FROM consultas WHERE id = :id AND id_usuario = :id_usuario";
    // sql = delete da tab consultas ond id = id (id da consulta) e id_usuario = id_usuario (id user sessão)
    
    try {
        $stmt = $conexao->prepare($sql); // prepra
       //guarda na caixinha do apelido
        $stmt->bindParam(':id', $id_consulta);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute(); // execulta

        // se deu erro :
    } catch (PDOException $e) {
        echo "Erro ao deletar: " . $e->getMessage();
        return false;
    }
} // terminada

// 5 remarca a data e o horário da consulta
function remarcarConsulta($conexao, $id_consulta, $novo_dia, $nova_hora) //parametros
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "UPDATE consultas SET dia = :dia, hora = :hora WHERE id = :id AND id_usuario = :id_usuario";
    // sql = atualize a tab consultas na coluna dia com o valor :dia, na coluna hora atualize com :hora onde o id = :id e o id_usuario = id_usuario
    
    try {
        $stmt = $conexao->prepare($sql); //prepara
        // guarda na caixinha
        $stmt->bindParam(':dia', $novo_dia);
        $stmt->bindParam(':hora', $nova_hora);
        $stmt->bindParam(':id', $id_consulta);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute(); //execulta

        // tratamento de erro:
    } catch (PDOException $e) {
        echo "Erro ao remarcar: " . $e->getMessage();
        return false;
    }
} // terminada

// 6 lista todas as consultas marcadas
function listarConsultas($conexao) // passa só o parametro da conexão com o banco
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    $sql = "SELECT * FROM consultas WHERE id_usuario = :id_usuario ORDER BY dia ASC, hora ASC";
    /*sql = selecione tudo da tab consultas onde o id_usuario = id_usuario
    e mostre isso em ordem crescente (É AQUI QUE ENTRA O ASC) se tiver consulta
    marcada no mesmo dia vai mostrar por horarios*/
    try {
        $stmt = $conexao->prepare($sql);// prepra
        
        //guarda nos apelidos
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute(); // execulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // traz em forma de lista/array

        // tratamento de erros
    } catch (PDOException $e) {
        echo "Erro ao buscar consultas: " . $e->getMessage();
        return [];
    }
} // terminada, 

                                 // GRAFICO
function obterResumoFinanceiro($conexao) // passando o parametro de conexao
{
    // Pega o ID do usuário conectado na sessão atual
    $id_usuario = $_SESSION['id'];

    try {
        // sql = selecione a soma da coluna valor_entrada da tabela entrada onde id_usuario for igual ao id da sessão
        // COALESCE - se a soma for vazia (NULL), transforma em 0
        // SUM - para somar todos os valores da coluna de uma vez só
        // AS total - da o apelido de 'total' para o resultado da soma
        $sqlEntradas = "SELECT COALESCE(SUM(valor_entrada), 0) AS total FROM entrada WHERE id_usuario = :id_usuario";
        
        $stmtEntradas = $conexao->prepare($sqlEntradas); // prepara o sql das entradas
        $stmtEntradas->bindParam(':id_usuario', $id_usuario); // guarda na caixinha do apelido
        $stmtEntradas->execute(); // execulta
        
        // fetchColumn() - pega direto o valor do resultado (a soma) e (float) garante que vire numero decimal
        $totalEntradas = (float) $stmtEntradas->fetchColumn();

        // sql = selecione a soma da coluna valor_saida da tabela saida onde id_usuario for igual ao id da sessão
        $sqlSaidas = "SELECT COALESCE(SUM(valor_saida), 0) AS total FROM saida WHERE id_usuario = :id_usuario";
        
        $stmtSaidas = $conexao->prepare($sqlSaidas); // prepara o sql das saidas
        $stmtSaidas->bindParam(':id_usuario', $id_usuario); // guarda na caixinha do apelido
        $stmtSaidas->execute(); // execulta
        
        // pega direto o valor total somado das saidas
        $totalSaidas = (float) $stmtSaidas->fetchColumn();

        // retorna um array com o total de entradas e o total de saidas para quem chamou a funcao
        return [
            'entradas' => $totalEntradas,
            'saidas'   => $totalSaidas
        ];
        
    } catch (PDOException $e) { // se der erro no banco, devolve 0 para os dois lados
        return ['entradas' => 0, 'saidas' => 0];
    } 
} // terminada
?>