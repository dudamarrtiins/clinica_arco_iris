# ProjetoClinicaArcoIris
### Projeto final de Semestre
# Clínica Arco - Íris
# Especificação de Requisitos

## 1. Introdução

### 1.1 Propósito
Este documento especifica os requisitos funcionais, não-funcionais e regras de negócio para o Sistema da Clínica Arco - Íris, seguindo as diretrizes de especificação do padrão ISO/IEC/IEEE 29148:2018.

### 1.2 Escopo
O sistema consistirá em uma aplicação escrita em PHP, HTML para gerenciar de forma volátil operações de gerenciamento de pacientes de uma clínica psicológica, como, visualização de agenda, ganhos e pacientes.

### 1.3 Definições e Acrônimos
- **RF**: Requisito Funcional (O que o sistema faz).
- **RNF**: Requisito Não-Funcional (Características de qualidade/técnicas).
- **RN**: Regra de Negócio (Diretrizes financeiras e restrições operacionais do banco).
- **NS**: Necessidade do Stakeholder.

### 1.4 Referências
- ISO/IEC/IEEE 29148:2018 - *Systems and software engineering — Life cycle processes — Requirements engineering*.

---

## 2. Descrição Geral

### 2.1 Perspectiva do Produto
O sistema operará de forma autônoma, sendo executado localmente. Os dados persistirão apenas durante a sessão ativa da aplicação.

### 2.2 Funções Principais
- Crie uma conta
- Faça login
- Cadastre novos pacientes
- Atualize os dados de seus pacientes
- Apague os dados de seus pacientes
- Visualize todos os nomes de seus pacientes de um só vez
- Visualize todos os dados de apenas um paciente em específico
- Marque sessões de terapia
- Remarque essas sessões
- Cancele as sessões
- Atualize os status das sessões (cancelada, realizada)
- Visualize as sessões em seu calendário
- Faça logout 



---

## 3. Requisitos Específicos

### 3.0 user story

Responsável que utiliza o sistema - "Eu quero que o sistema me mostre todas informações de meus pacientes, sendo elas o pagamento pela consulta, o dia de atendimento, o telefone, data de nascimento, CPF, convênio e nome completo."

## 3.1 Definições e acrônimos
| **TERMO** | **DEFINIÇÕES** |
| --- | --- |
| CRUD | Sigla representativa para Create, Read, Update and Delete |
| DER | Diagrama Entidade-Relacionamento |
| RF | Requisito Funcional |
| RNF | Requisito Não Funcional |
| RN | Regra de Negócio |

### 3.2 Requisitos Funcionais (RF)

#### RF-001: Abertura de Conta
**Descrição**: O sistema deve permitir a abertura de um novo paciente solicitando obrigatoriamente o nome completo do mesmo.

**Prioridade**: Alta  
**Versão**: 1.0  
**Data**: 2026-10-07 

**Critérios de Aceitação**:
- [ ] O sistema não deve aceitar nomes de titulares vazios.
- [ ] Após o cadastro bem-sucedido, os dados devem ser salvos em memória e o menu principal deve ser liberado.

---

#### RF-002: Modificação de Perfil
**Descrição**: O sistema deve permitir que o usuário root modifique qualquer informação dos pacientes, sendo eles recém-adicionados ou não.

**Prioridade**: Alta  
**Versão**: 1.0  
**Data**: 2026-10-07 

**Critérios de Aceitação**:
- [ ] As novas informações adicionadas não devem ficar vazias.
- [ ] O sistema deve atualizar imediatamente as informações no perfil do paciente.

**Dependências**: RF-001

---

#### RF-003: Apagando Perfis
**Descrição**: O sistema deve permitir que o usuário root apague perfis de antigos clientes.

**Prioridade**: Alta  
**Versão**: 1.0  
**Data**: 2026-10-07 

**Rastreabilidade**: Derivado de NS-001

**Critérios de Aceitação**:
- [ ] O sistema deve apagar todas as informações do perfil de um determinado cliente quando solicitado.

**Dependências**: RN-001

---
#### RF-004: Agendando Consultas
**Descrição**: O sistema deve permitir a adição de uma nova consulta.

**Prioridade**: Alta  
**Versão**: 1.0  
**Data**: 2026-10-07 


**Critérios de Aceitação**:
- [ ] O sistema não deve aceitar campos vazios, sendo eles: nome do paciente, dia e horário.
- [ ] O sistema deve mostrar no calendário a consulta com horário e nome do paciente.

**Dependências**: RN-001

---

#### RF-005: Mudando Status de Consultas
**Descrição**: O sistema deve permitir que o usuário mude o status da consulta.

**Prioridade**: Alta  
**Versão**: 1.0  
**Data**: 2026-10-07 

**Critérios de Aceitação**:
- [ ] O sistema deve permitir trocar o status de agendada para realizada.

**Dependências**: RN-004 

---

#### RF-006: Cancelando Consultas
**Descrição**: O sistema deve permitir que o usuário mude o status da consulta para cancelada.

**Prioridade**: Alta  
**Versão**: 1.0  
**Data**: 2026-10-07 

**Critérios de Aceitação**:
- [ ] O sistema deve permitir trocar o status de agendada para cancelada, assim apagando do calendário.

**Dependências**: RN-004 e RN-005

---

#### RF-007: Consulta de Extrato 
**Descrição**: O sistema deve exibir em tela as últimas movimentações financeiras realizadas.

**Prioridade**: Média  
**Versão**: 1.0  
**Data**: 2026-10-07 
**Rastreabilidade**: Derivado de NS-001

**Critérios de Aceitação**:
- [ ] Exibir de forma legível o saldo atual.
- [ ] Apresentar a listagem histórica cronológica.


---

#### RF-008: Menu Interativo 
**Descrição**: O sistema deve disponibilizar um menu de navegação para que o administrador possa navegar pelas páginas.

**Prioridade**: Média  
**Versão**: 1.0  
**Data**: 2026-10-07

**Critérios de Aceitação**:
- [ ] Apresentar opções claras no header.


---

### 3.3 Regras de Negócio (RN)

#### RN-001: Validação da consulta
**Descrição**: Toda consulta deve ser cobrada no valor de 200,00 reais.

**Prioridade**: Crítica  
**Versão**: 1.0  
**Data**: 2026-10-14

---

#### RN-004: Restrição de Idade Mínima
**Descrição**: O sistema de atendimentos é exclusivo para pacientes com idade igual ou superior a 03 anos. Caso a idade informada no cadastro inicial realizado pelo administrador seja menor que 03 anos, o sistema deve exibir uma mensagem impeditiva e encerrar o fluxo imediatamente.

**Prioridade**: Crítica
**Versão**: 1.0
**Data**: 2026-10-14

---

### 3.4 Requisitos Não-Funcionais (RNF)

#### RNF-001: Linguagem e Dependências
**Descrição**: O sistema deve possuir um gráfico para acompanhar a gestão financeira.

**Prioridade**: Baixa
**Versão**: 1.0  
**Data**: 2026-10-14

---

#### RNF-001: Linguagem e Dependências
**Descrição**: O sistema deve possuir um calendário para exibir as consultas agendadas.

**Prioridade**: Alta
**Versão**: 1.0  
**Data**: 2026-10-14

## 4. Controle de Versões

### Histórico de Alterações

## 11. Controle de Versões

| Versão | Data | Descrição das Alterações | Autor(es) |
| :---: | :---: | --- | :---: |
| **1.0** | 10/10/2026 | Criação do repositório, elaboração inicial da documentação, criação do Diagrama Entidade-Relacionamento (DER) e modelagem de dados. | Maria E |
| **1.1** | 11/10/2026 | Continuação da modelagem de dados e evolução da documentação técnica. | Maria E |
| **1.2** | 12/10/2026 | Implementação do controle de sessões e funções de relacionamento para isolamento de contas de usuário. Criação das páginas do CRUD (prontuário, cadastro e visualização individual de pacientes). | Maria E |
| **1.3** | 12/10/2026 | Ajuste e correção dos relacionamentos no banco de dados SQL (chaves primárias e estrangeiras). | Maria E |
| **1.4** | 14/10/2026 | Inclusão do dump do banco de dados e aplicação da função `LOWER` no SQL. Atualização do DER, criação do Dicionário de Dados e inclusão de campos na tabela de entradas e saídas (nome do paciente e motivo). Atualização do README. | Maria E |
| **1.5** | 15/10/2026 | Padronização e reestruturação dos caminhos relativos e absolutos de navegação no painel principal. | Maria E |
| **1.6** | 16/10/2026 | Correção dos links do README e ajustes finais nas rotas centrais da aplicação. | Maria E |
| **1.7** | 20/10/2026 | Atualização e revisão periódica do README. | Maria E |
| **1.8** | 21/10/2026 | Implementação de validações de idade mínima e limite de entradas. Correção na persistência do nome do paciente no extrato, ajuste na função de atualizar idade, correção do link do `git clone` e adição de comentários no código. | Maria E |
| **1.9** | 22/10/2026 | Finalização da documentação do código (comentários) e últimas revisões/modificações no README e na documentação técnica. | Maria E |

---

Fim da documentação. Obrigada pela atenção!
