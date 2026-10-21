--
-- PostgreSQL database dump
--

\restrict 8l3YqA7AVtbCwxjx8hdQcuzV63T2wnoS9QbIbICUaAIdhDhPpoyxTWoeFABDXVy

-- Dumped from database version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)
-- Dumped by pg_dump version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: consultas; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.consultas (
    id integer NOT NULL,
    id_paciente integer,
    dia date,
    hora time without time zone,
    status character varying(20) DEFAULT 'agendada'::character varying,
    id_usuario integer
);


ALTER TABLE public.consultas OWNER TO root;

--
-- Name: consultas_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.consultas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.consultas_id_seq OWNER TO root;

--
-- Name: consultas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.consultas_id_seq OWNED BY public.consultas.id;


--
-- Name: entrada; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.entrada (
    id integer NOT NULL,
    valor_entrada numeric(15,2),
    dia date,
    id_usuario integer,
    nomepaciente character varying(90),
    metodo character varying(30)
);


ALTER TABLE public.entrada OWNER TO root;

--
-- Name: entrada_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.entrada_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.entrada_id_seq OWNER TO root;

--
-- Name: entrada_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.entrada_id_seq OWNED BY public.entrada.id;


--
-- Name: paciente; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.paciente (
    id integer NOT NULL,
    nome character varying(90),
    cpf character varying(14),
    nasc date,
    idade integer,
    convenio character varying(30),
    sexo character varying(30),
    id_usuario integer
);


ALTER TABLE public.paciente OWNER TO root;

--
-- Name: paciente_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.paciente_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.paciente_id_seq OWNER TO root;

--
-- Name: paciente_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.paciente_id_seq OWNED BY public.paciente.id;


--
-- Name: saida; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.saida (
    id integer NOT NULL,
    valor_saida numeric,
    dia date,
    id_usuario integer,
    motivo character varying(90)
);


ALTER TABLE public.saida OWNER TO root;

--
-- Name: saida_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.saida_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.saida_id_seq OWNER TO root;

--
-- Name: saida_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.saida_id_seq OWNED BY public.saida.id;


--
-- Name: telefone; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.telefone (
    id integer NOT NULL,
    id_paciente integer,
    telefone character varying(13)
);


ALTER TABLE public.telefone OWNER TO root;

--
-- Name: telefone_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.telefone_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.telefone_id_seq OWNER TO root;

--
-- Name: telefone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.telefone_id_seq OWNED BY public.telefone.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    senha character varying(255) NOT NULL
);


ALTER TABLE public.usuarios OWNER TO root;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuarios_id_seq OWNER TO root;

--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: consultas id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.consultas ALTER COLUMN id SET DEFAULT nextval('public.consultas_id_seq'::regclass);


--
-- Name: entrada id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.entrada ALTER COLUMN id SET DEFAULT nextval('public.entrada_id_seq'::regclass);


--
-- Name: paciente id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.paciente ALTER COLUMN id SET DEFAULT nextval('public.paciente_id_seq'::regclass);


--
-- Name: saida id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.saida ALTER COLUMN id SET DEFAULT nextval('public.saida_id_seq'::regclass);


--
-- Name: telefone id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.telefone ALTER COLUMN id SET DEFAULT nextval('public.telefone_id_seq'::regclass);


--
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Name: consultas consultas_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.consultas
    ADD CONSTRAINT consultas_pkey PRIMARY KEY (id);


--
-- Name: entrada entrada_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.entrada
    ADD CONSTRAINT entrada_pkey PRIMARY KEY (id);


--
-- Name: paciente paciente_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.paciente
    ADD CONSTRAINT paciente_pkey PRIMARY KEY (id);


--
-- Name: saida saida_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.saida
    ADD CONSTRAINT saida_pkey PRIMARY KEY (id);


--
-- Name: telefone telefone_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.telefone
    ADD CONSTRAINT telefone_pkey PRIMARY KEY (id);


--
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: consultas consultas_id_paciente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.consultas
    ADD CONSTRAINT consultas_id_paciente_fkey FOREIGN KEY (id_paciente) REFERENCES public.paciente(id);


--
-- Name: consultas consultas_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.consultas
    ADD CONSTRAINT consultas_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id);


--
-- Name: entrada entrada_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.entrada
    ADD CONSTRAINT entrada_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id);


--
-- Name: paciente fk_paciente_usuario; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.paciente
    ADD CONSTRAINT fk_paciente_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id);


--
-- Name: paciente paciente_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.paciente
    ADD CONSTRAINT paciente_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id);


--
-- Name: saida saida_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.saida
    ADD CONSTRAINT saida_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id);


--
-- Name: telefone telefone_id_paciente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.telefone
    ADD CONSTRAINT telefone_id_paciente_fkey FOREIGN KEY (id_paciente) REFERENCES public.paciente(id);


--
-- PostgreSQL database dump complete
--

\unrestrict 8l3YqA7AVtbCwxjx8hdQcuzV63T2wnoS9QbIbICUaAIdhDhPpoyxTWoeFABDXVy

