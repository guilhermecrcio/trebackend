--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.9
-- Dumped by pg_dump version 9.6.9

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: vendas; Type: TABLE; Schema: public; Owner: tray
--

CREATE TABLE public.vendas (
    id integer NOT NULL,
    vendedor_id integer NOT NULL,
    valor double precision NOT NULL,
    data_venda timestamp without time zone DEFAULT now(),
    comissao double precision NOT NULL
);


ALTER TABLE public.vendas OWNER TO tray;

--
-- Name: vendas_id_seq; Type: SEQUENCE; Schema: public; Owner: tray
--

CREATE SEQUENCE public.vendas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vendas_id_seq OWNER TO tray;

--
-- Name: vendas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: tray
--

ALTER SEQUENCE public.vendas_id_seq OWNED BY public.vendas.id;


--
-- Name: vendedores; Type: TABLE; Schema: public; Owner: tray
--

CREATE TABLE public.vendedores (
    id integer NOT NULL,
    nome text NOT NULL,
    email text NOT NULL,
    ativo boolean DEFAULT true,
    comissao double precision DEFAULT 8.5
);


ALTER TABLE public.vendedores OWNER TO tray;

--
-- Name: vendedores_id_seq; Type: SEQUENCE; Schema: public; Owner: tray
--

CREATE SEQUENCE public.vendedores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vendedores_id_seq OWNER TO tray;

--
-- Name: vendedores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: tray
--

ALTER SEQUENCE public.vendedores_id_seq OWNED BY public.vendedores.id;


--
-- Name: vendas id; Type: DEFAULT; Schema: public; Owner: tray
--

ALTER TABLE ONLY public.vendas ALTER COLUMN id SET DEFAULT nextval('public.vendas_id_seq'::regclass);


--
-- Name: vendedores id; Type: DEFAULT; Schema: public; Owner: tray
--

ALTER TABLE ONLY public.vendedores ALTER COLUMN id SET DEFAULT nextval('public.vendedores_id_seq'::regclass);


--
-- Data for Name: vendas; Type: TABLE DATA; Schema: public; Owner: tray
--

COPY public.vendas (id, vendedor_id, valor, data_venda, comissao) FROM stdin;
1	6	100	2018-10-09 21:58:58.736422	8.5
2	6	244	2018-10-09 21:59:15.7195	8.5
3	6	123	2018-10-09 22:00:02.751238	8.5
4	6	6546	2018-10-09 22:00:05.580821	8.5
5	6	324	2018-10-09 22:00:07.648439	8.5
6	6	6452	2018-10-09 22:00:10.078912	8.5
7	6	213	2018-10-09 22:00:12.337275	8.5
8	6	643	2018-10-09 22:00:14.359925	8.5
9	6	1235	2018-10-09 22:00:17.016265	8.5
10	6	643	2018-10-09 22:00:19.015658	8.5
11	7	134	2018-10-09 22:36:37.784344	8.5
\.


--
-- Name: vendas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: tray
--

SELECT pg_catalog.setval('public.vendas_id_seq', 13, true);


--
-- Data for Name: vendedores; Type: TABLE DATA; Schema: public; Owner: tray
--

COPY public.vendedores (id, nome, email, ativo, comissao) FROM stdin;
7	Guilherme Curcio 2	guilherme.crcio2@gmail.com	t	8.5
6	Guilherme Curcio put	guilherme.crcio@gmail.com	t	8.5
\.


--
-- Name: vendedores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: tray
--

SELECT pg_catalog.setval('public.vendedores_id_seq', 7, true);


--
-- Name: vendas vendas_pkey; Type: CONSTRAINT; Schema: public; Owner: tray
--

ALTER TABLE ONLY public.vendas
    ADD CONSTRAINT vendas_pkey PRIMARY KEY (id);


--
-- Name: vendedores vendedores_pkey; Type: CONSTRAINT; Schema: public; Owner: tray
--

ALTER TABLE ONLY public.vendedores
    ADD CONSTRAINT vendedores_pkey PRIMARY KEY (id);


--
-- Name: vendas vendas_vendedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: tray
--

ALTER TABLE ONLY public.vendas
    ADD CONSTRAINT vendas_vendedor_id_fkey FOREIGN KEY (vendedor_id) REFERENCES public.vendedores(id);


--
-- PostgreSQL database dump complete
--

