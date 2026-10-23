```mermaid
erDiagram

    PACIENTE{
        int ID PK
        int ID_USUARIO FK
        varchar(90) nome
        varchar(14) cpf
        date nasc
        numeric idade
        varchar(30) convenio
        varchar(30) sexo
    }

    USUARIOS{
        int ID PK
        varchar(255) email
        varchar(255) senha
    }

    TELEFONE{
        int ID PK
        int ID_PACIENTE FK
        varchar(13) telefone
        int ID_USUARIO FK
    }

    CONSULTA{
        int ID PK
        int ID_PACIENTE FK
        date dia
        time hora
        string status
        int ID_USUARIO FK
    }

    ENTRADA{
        int ID PK 
        numeric valor_entrada
        date dia
        varchar(90) nomepaciente
        varchar(30) metodo
        int ID_PACIENTE FK
        int ID_USUARIO FK
    }

    SAIDA{
        int ID PK 
        numeric valor_saida
        date dia
        varchar(90) motivo
        int ID_PACIENTE FK
        int ID_USUARIO FK
    }

    USUARIOS ||--o{ PACIENTE : "Possui"
    USUARIOS ||--o{ ENTRADA : "Registra"
    USUARIOS ||--o{ SAIDA : "Registra"
    PACIENTE ||--o{ TELEFONE : "Possui"
    PACIENTE ||--o{ CONSULTA : "Possui"