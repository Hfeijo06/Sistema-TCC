-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/11/2024 às 17:09
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_calendario`
--

CREATE TABLE `tb_calendario` (
  `cal_id` int(11) NOT NULL,
  `cal_titulo` varchar(100) NOT NULL,
  `cal_descricao` varchar(100) DEFAULT NULL,
  `cal_cor` varchar(20) NOT NULL,
  `cal_data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_calendario`
--

INSERT INTO `tb_calendario` (`cal_id`, `cal_titulo`, `cal_descricao`, `cal_cor`, `cal_data`) VALUES
(38, 'Conta', 'Conta a Receber', '#228B22', '2024-11-06'),
(39, 'Conta', 'Conta a Receber', '#228B22', '2024-11-13'),
(40, 'Conta', 'Conta a Pagar', '#8B0000', '2024-12-06'),
(41, 'Conta', 'Conta a Receber', '#228B22', '2024-11-05'),
(42, 'Reunião', 'Reunião com fornecedores', '#8B4513', '2024-11-14'),
(43, 'Conta', 'Conta a Receber', '#228B22', '2024-11-06'),
(44, 'Conta', 'Conta a Receber', '#228B22', '2024-11-14'),
(45, 'Conta', 'Conta a Receber', '#228B22', '2024-11-07'),
(46, 'Conta', 'Conta a Pagar', '#8B0000', '2024-12-07'),
(47, 'Conta', 'Conta a Pagar', '#8B0000', '2024-12-07'),
(48, 'Conta', 'Conta a Receber', '#228B22', '2024-11-07'),
(49, 'Conta', 'Conta a Receber', '#228B22', '2024-11-14'),
(50, 'Conta', 'Conta a Receber', '#228B22', '2024-11-07'),
(51, 'Conta', 'Conta a Pagar', '#8B0000', '2024-12-07'),
(52, 'Conta', 'Conta a Receber', '#228B22', '2024-11-07'),
(53, 'Conta', 'Conta a Receber', '#228B22', '2024-11-14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_clientes`
--

CREATE TABLE `tb_clientes` (
  `cli_codigo` int(11) NOT NULL,
  `cli_nome` varchar(70) DEFAULT NULL,
  `cli_rg` varchar(14) DEFAULT NULL,
  `cli_cpf` varchar(14) DEFAULT NULL,
  `cli_cep` varchar(20) DEFAULT NULL,
  `cli_celular` varchar(15) DEFAULT NULL,
  `cli_telefone` varchar(20) DEFAULT NULL,
  `cli_cidade` varchar(50) DEFAULT NULL,
  `cli_bairro` varchar(50) DEFAULT NULL,
  `cli_numero` varchar(10) DEFAULT NULL,
  `cli_complemento` varchar(50) DEFAULT NULL,
  `cli_rua` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_clientes`
--

INSERT INTO `tb_clientes` (`cli_codigo`, `cli_nome`, `cli_rg`, `cli_cpf`, `cli_cep`, `cli_celular`, `cli_telefone`, `cli_cidade`, `cli_bairro`, `cli_numero`, `cli_complemento`, `cli_rua`) VALUES
(1, '—', '—', '—', '—', '—', '—', '—', '—', '—', '—', '—'),
(18, 'Eliane Torezan', '36.436.401-2', '234.355.467-57', '13570-050', '(19) 99836-4364', '', 'São Carlos', 'Jardim Ricetti', '09', '', 'Rua Oscar Barros'),
(19, 'João', '32.642.387-4', '237.453.287-45', '13570-050', '(19) 93746-3743', '', 'São Carlos', 'Jardim Ricetti', '23', '', 'Rua Oscar Barros'),
(21, 'Henrique', '21.324.321-2', '233.123.333-33', '13470-050', '(21) 32372-6372', '', 'Americana', 'Parque das Nações', '23', '', 'Rua Senegal');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_compra`
--

CREATE TABLE `tb_compra` (
  `com_codigo` int(11) NOT NULL,
  `for_codigo` int(11) DEFAULT NULL,
  `com_data` date DEFAULT NULL,
  `com_tipo_pagamento` varchar(50) DEFAULT NULL,
  `des_codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_compra`
--

INSERT INTO `tb_compra` (`com_codigo`, `for_codigo`, `com_data`, `com_tipo_pagamento`, `des_codigo`) VALUES
(15, 7, '1233-03-12', '1', NULL),
(45, 17, '2024-11-07', '3', 9),
(47, 19, '2024-11-07', '3', 5);

--
-- Acionadores `tb_compra`
--
DELIMITER $$
CREATE TRIGGER `registra_contas_pagar_compra` AFTER INSERT ON `tb_compra` FOR EACH ROW BEGIN
    INSERT INTO tb_contas_pagar (com_codigo, cp_valor, cp_vencimento, cp_status, des_codigo)
    VALUES (NEW.com_codigo, 0, DATE_ADD(NEW.com_data, INTERVAL 30 DAY), 'Pendente', NEW.des_codigo);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_contas_pagar`
--

CREATE TABLE `tb_contas_pagar` (
  `cp_codigo` int(11) NOT NULL,
  `com_codigo` int(11) DEFAULT NULL,
  `cp_valor` decimal(10,2) DEFAULT NULL,
  `cp_vencimento` date DEFAULT NULL,
  `cp_status` varchar(20) DEFAULT NULL,
  `cp_data_pagamento` date DEFAULT NULL,
  `des_codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_contas_pagar`
--

INSERT INTO `tb_contas_pagar` (`cp_codigo`, `com_codigo`, `cp_valor`, `cp_vencimento`, `cp_status`, `cp_data_pagamento`, `des_codigo`) VALUES
(57, 45, 400.00, '2024-12-07', 'Pago', '2024-11-07', 9),
(59, 47, 108.00, '2024-12-07', 'Pago', '2024-11-07', 5);

--
-- Acionadores `tb_contas_pagar`
--
DELIMITER $$
CREATE TRIGGER `insere_calendario_pagar` AFTER INSERT ON `tb_contas_pagar` FOR EACH ROW BEGIN
    -- Inserir os dados na tabela fluxo de caixa
    INSERT INTO tb_calendario (cal_titulo, cal_data, cal_descricao, cal_cor)
    VALUES ('Conta', NEW.cp_vencimento, 'Conta a Pagar', '#8B0000');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insere_fluxo_caixa_saida` AFTER INSERT ON `tb_contas_pagar` FOR EACH ROW BEGIN
    -- Inserir um registro de saída no fluxo de caixa
    INSERT INTO tb_fluxo_caixa (cp_codigo, fc_data, fc_tipo, fc_valor)
    VALUES (NEW.cp_codigo, NEW.cp_vencimento, 'Saída', NEW.cp_valor);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_contas_receber`
--

CREATE TABLE `tb_contas_receber` (
  `cr_codigo` int(11) NOT NULL,
  `parc_codigo` int(11) DEFAULT NULL,
  `cr_status` varchar(20) DEFAULT NULL,
  `cr_data_pagamento` date DEFAULT NULL,
  `cr_valor` decimal(10,2) DEFAULT NULL,
  `cre_codigo` int(11) DEFAULT NULL,
  `cr_data_vencimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_contas_receber`
--

INSERT INTO `tb_contas_receber` (`cr_codigo`, `parc_codigo`, `cr_status`, `cr_data_pagamento`, `cr_valor`, `cre_codigo`, `cr_data_vencimento`) VALUES
(114, 98, 'Pago', '2024-11-06', 45.50, 7, '2024-11-06'),
(115, 99, 'Pendente', NULL, 45.50, 7, '2024-11-13'),
(116, 100, 'Pago', '2024-11-06', 79.00, 7, '2024-11-05'),
(117, 101, 'Pendente', NULL, 45.50, 7, '2024-11-06'),
(118, 102, 'Pendente', NULL, 45.50, 7, '2024-11-14'),
(119, 103, 'Pago', '2024-11-07', 24.00, 7, '2024-11-07'),
(122, 106, 'Pago', '2024-11-07', 79.00, 7, '2024-11-07'),
(123, 107, 'Pendente', NULL, 51.50, 7, '2024-11-07'),
(124, 108, 'Pendente', NULL, 51.50, 7, '2024-11-14');

--
-- Acionadores `tb_contas_receber`
--
DELIMITER $$
CREATE TRIGGER `insere_calendario` AFTER INSERT ON `tb_contas_receber` FOR EACH ROW BEGIN
    -- Inserir os dados na tabela fluxo de caixa
    INSERT INTO tb_calendario (cal_data, cal_titulo, cal_descricao, cal_cor)
    VALUES (NEW.cr_data_vencimento, 'Conta', 'Conta a Receber', '#228B22');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insere_fluxo_caixa` AFTER INSERT ON `tb_contas_receber` FOR EACH ROW BEGIN

    -- Inserir os dados na tabela fluxo de caixa
    INSERT INTO tb_fluxo_caixa (cr_codigo, fc_data, fc_tipo, fc_valor)
    VALUES (NEW.cr_codigo, NEW.cr_data_vencimento, 'Entrada', NEW.cr_valor);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_credito`
--

CREATE TABLE `tb_credito` (
  `cre_codigo` int(11) NOT NULL,
  `cre_nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_credito`
--

INSERT INTO `tb_credito` (`cre_codigo`, `cre_nome`) VALUES
(1, 'Investimento'),
(7, 'Venda');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_despesas`
--

CREATE TABLE `tb_despesas` (
  `des_codigo` int(11) NOT NULL,
  `des_nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_despesas`
--

INSERT INTO `tb_despesas` (`des_codigo`, `des_nome`) VALUES
(5, 'Pagamento Fornecedor Água'),
(7, 'Conta de Água'),
(8, 'CPFL'),
(9, 'Pagamento Fornecedor Gás');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_estoque`
--

CREATE TABLE `tb_estoque` (
  `est_codigo` int(11) NOT NULL,
  `pro_codigo` int(11) DEFAULT NULL,
  `est_qtde` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_estoque`
--

INSERT INTO `tb_estoque` (`est_codigo`, `pro_codigo`, `est_qtde`) VALUES
(27, 11, 37),
(30, 14, 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_fluxo_caixa`
--

CREATE TABLE `tb_fluxo_caixa` (
  `fc_codigo` int(11) NOT NULL,
  `cr_codigo` int(11) DEFAULT NULL,
  `cp_codigo` int(11) DEFAULT NULL,
  `fc_data` date DEFAULT NULL,
  `fc_tipo` varchar(10) DEFAULT NULL,
  `fc_valor` decimal(10,2) DEFAULT NULL,
  `fc_titulo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_fluxo_caixa`
--

INSERT INTO `tb_fluxo_caixa` (`fc_codigo`, `cr_codigo`, `cp_codigo`, `fc_data`, `fc_tipo`, `fc_valor`, `fc_titulo`) VALUES
(143, 114, NULL, '2024-11-06', 'Entrada', 45.50, NULL),
(144, 115, NULL, '2024-11-13', 'Entrada', 45.50, NULL),
(146, 116, NULL, '2024-11-05', 'Entrada', 79.00, NULL),
(147, 117, NULL, '2024-11-06', 'Entrada', 45.50, NULL),
(148, 118, NULL, '2024-11-14', 'Entrada', 45.50, NULL),
(149, 119, NULL, '2024-11-07', 'Entrada', 24.00, NULL),
(150, NULL, 57, '2024-12-07', 'Saída', 400.00, NULL),
(154, 122, NULL, '2024-11-07', 'Entrada', 79.00, NULL),
(155, NULL, 59, '2024-12-07', 'Saída', 108.00, NULL),
(156, 123, NULL, '2024-11-07', 'Entrada', 51.50, NULL),
(157, 124, NULL, '2024-11-14', 'Entrada', 51.50, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_fornecedores`
--

CREATE TABLE `tb_fornecedores` (
  `for_codigo` int(11) NOT NULL,
  `for_nome` varchar(70) DEFAULT NULL,
  `for_celular` varchar(15) DEFAULT NULL,
  `for_telefone` varchar(20) DEFAULT NULL,
  `for_descricao` varchar(70) DEFAULT NULL,
  `for_email` varchar(100) DEFAULT NULL,
  `for_cnpj` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_fornecedores`
--

INSERT INTO `tb_fornecedores` (`for_codigo`, `for_nome`, `for_celular`, `for_telefone`, `for_descricao`, `for_email`, `for_cnpj`) VALUES
(7, '—', '—', '—', '—', '—', '—'),
(17, 'Felipe Feijó', '(19) 99642-7532', '', 'Fornecedor de Gás', 'exemplo@gmail.com', '97.234.739/0001-02'),
(19, 'Henrique', '(19) 84637-4638', '', 'Fornecedor de Água', 'exemplo@gmail.com', '12.221.378/2828-28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_itens_compra`
--

CREATE TABLE `tb_itens_compra` (
  `itc_codigo` int(11) NOT NULL,
  `com_codigo` int(11) DEFAULT NULL,
  `pro_codigo` int(11) DEFAULT NULL,
  `itc_qtde` int(11) DEFAULT NULL,
  `itc_preco` decimal(10,2) DEFAULT NULL,
  `itc_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_itens_compra`
--

INSERT INTO `tb_itens_compra` (`itc_codigo`, `com_codigo`, `pro_codigo`, `itc_qtde`, `itc_preco`, `itc_total`) VALUES
(35, 45, 11, 10, 40.00, 400.00),
(37, 47, 14, 12, 9.00, 108.00);

--
-- Acionadores `tb_itens_compra`
--
DELIMITER $$
CREATE TRIGGER `atualiza_contas_pagar` AFTER INSERT ON `tb_itens_compra` FOR EACH ROW BEGIN
    UPDATE tb_contas_pagar
    SET cp_valor = (SELECT COALESCE(SUM(itc_total), 0) FROM tb_itens_compra WHERE com_codigo = NEW.com_codigo)
    WHERE com_codigo = NEW.com_codigo;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `atualiza_estoque_compra` AFTER INSERT ON `tb_itens_compra` FOR EACH ROW BEGIN
    UPDATE tb_estoque 
    SET est_qtde = est_qtde + NEW.itc_qtde
    WHERE pro_codigo = NEW.pro_codigo;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `atualiza_estoque_exclusao_compra` BEFORE DELETE ON `tb_itens_compra` FOR EACH ROW BEGIN
    UPDATE tb_estoque 
    SET est_qtde = est_qtde - OLD.itc_qtde
    WHERE pro_codigo = OLD.pro_codigo;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `atualiza_fluxo_caixa_itens_compra` AFTER INSERT ON `tb_itens_compra` FOR EACH ROW BEGIN
    UPDATE tb_fluxo_caixa
    SET fc_valor = (SELECT COALESCE(SUM(itc_total), 0) FROM tb_itens_compra WHERE com_codigo = NEW.com_codigo)
    WHERE cp_codigo = (SELECT cp_codigo FROM tb_contas_pagar WHERE com_codigo = NEW.com_codigo);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_itens_venda`
--

CREATE TABLE `tb_itens_venda` (
  `itv_codigo` int(11) NOT NULL,
  `ven_codigo` int(11) DEFAULT NULL,
  `pro_codigo` int(11) DEFAULT NULL,
  `itv_qtde` int(11) DEFAULT NULL,
  `itv_preco` decimal(10,2) DEFAULT NULL,
  `itv_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_itens_venda`
--

INSERT INTO `tb_itens_venda` (`itv_codigo`, `ven_codigo`, `pro_codigo`, `itv_qtde`, `itv_preco`, `itv_total`) VALUES
(118, 130, 10, 1, 12.00, 12.00),
(119, 130, 11, 1, 79.00, 79.00),
(120, 131, 11, 1, 79.00, 79.00),
(121, 132, 10, 1, 12.00, 12.00),
(122, 132, 11, 1, 79.00, 79.00),
(123, 133, 10, 2, 12.00, 24.00),
(126, 135, 11, 1, 79.00, 79.00),
(127, 136, 14, 2, 12.00, 24.00),
(128, 136, 11, 1, 79.00, 79.00);

--
-- Acionadores `tb_itens_venda`
--
DELIMITER $$
CREATE TRIGGER `atualiza_estoque_exclusao_venda` BEFORE DELETE ON `tb_itens_venda` FOR EACH ROW BEGIN
    UPDATE tb_estoque 
    SET est_qtde = est_qtde + OLD.itv_qtde
    WHERE pro_codigo = OLD.pro_codigo;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `atualiza_estoque_venda` AFTER INSERT ON `tb_itens_venda` FOR EACH ROW BEGIN
    UPDATE tb_estoque 
    SET est_qtde = est_qtde - NEW.itv_qtde
    WHERE pro_codigo = NEW.pro_codigo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_login`
--

CREATE TABLE `tb_login` (
  `log_codigo` int(11) NOT NULL,
  `log_nome` varchar(30) DEFAULT NULL,
  `log_usuario` varchar(20) DEFAULT NULL,
  `log_senha` varchar(20) DEFAULT NULL,
  `log_data_cadastro` date DEFAULT current_timestamp(),
  `log_email` varchar(100) NOT NULL,
  `log_token` varchar(100) DEFAULT NULL,
  `log_token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_login`
--

INSERT INTO `tb_login` (`log_codigo`, `log_nome`, `log_usuario`, `log_senha`, `log_data_cadastro`, `log_email`, `log_token`, `log_token_expira`) VALUES
(38, 'Henrique Feijó', 'Hfeijo', '123', '2024-11-06', 'grupotccach@gmail.com', NULL, NULL),
(39, 'test', 'ADM', 'ADM', '2024-11-06', 'test@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_pagamento`
--

CREATE TABLE `tb_pagamento` (
  `pag_codigo` int(11) NOT NULL,
  `ven_codigo` int(11) DEFAULT NULL,
  `pag_tipo_pag` varchar(50) DEFAULT NULL,
  `pag_descricao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_pagamento`
--

INSERT INTO `tb_pagamento` (`pag_codigo`, `ven_codigo`, `pag_tipo_pag`, `pag_descricao`) VALUES
(7, 7, NULL, NULL),
(67, 130, '3', ''),
(68, 131, '1', ''),
(69, 132, '3', ''),
(70, 133, '1', ''),
(72, 135, '2', ''),
(73, 136, '3', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_parcelas`
--

CREATE TABLE `tb_parcelas` (
  `parc_codigo` int(11) NOT NULL,
  `pag_codigo` int(11) DEFAULT NULL,
  `parc_numero_parcela` int(11) DEFAULT NULL,
  `parc_data_vencimento` date DEFAULT NULL,
  `parc_valor` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_parcelas`
--

INSERT INTO `tb_parcelas` (`parc_codigo`, `pag_codigo`, `parc_numero_parcela`, `parc_data_vencimento`, `parc_valor`) VALUES
(7, 7, NULL, NULL, NULL),
(98, 67, 1, '2024-11-06', 45.50),
(99, 67, 2, '2024-11-13', 45.50),
(100, 68, 1, '2024-11-05', 79.00),
(101, 69, 1, '2024-11-06', 45.50),
(102, 69, 2, '2024-11-14', 45.50),
(103, 70, 1, '2024-11-07', 24.00),
(106, 72, 1, '2024-11-07', 79.00),
(107, 73, 1, '2024-11-07', 51.50),
(108, 73, 2, '2024-11-14', 51.50);

--
-- Acionadores `tb_parcelas`
--
DELIMITER $$
CREATE TRIGGER `trg_after_insert_parcela` AFTER INSERT ON `tb_parcelas` FOR EACH ROW BEGIN
    DECLARE credito INT;

    -- Busca o cre_codigo correspondente à venda relacionada à parcela
    SELECT v.cre_codigo 
    INTO credito
    FROM tb_vendas AS v
    INNER JOIN tb_pagamento AS p ON v.ven_codigo = p.ven_codigo
    WHERE p.pag_codigo = NEW.pag_codigo;

    -- Insere uma nova conta a receber
    INSERT INTO tb_contas_receber (parc_codigo, cr_valor, cr_status, cr_data_vencimento, cre_codigo)
    VALUES (NEW.parc_codigo, NEW.parc_valor, 'Pendente', NEW.parc_data_vencimento, credito);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_produtos`
--

CREATE TABLE `tb_produtos` (
  `pro_codigo` int(11) NOT NULL,
  `pro_nome` varchar(100) NOT NULL,
  `pro_tipo` varchar(50) DEFAULT NULL,
  `pro_preco` decimal(10,2) NOT NULL,
  `pro_descricao` varchar(70) DEFAULT NULL,
  `pro_validade` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_produtos`
--

INSERT INTO `tb_produtos` (`pro_codigo`, `pro_nome`, `pro_tipo`, `pro_preco`, `pro_descricao`, `pro_validade`) VALUES
(4, 'Gás', 'Gás', 79.00, 'Gás 13kg', '2024-10-02'),
(5, 'Água', 'Água', 12.00, 'Água c/ Gás', '2024-10-02'),
(8, 'Água 60L', 'Água', 60.00, 'Água de 60L', '2024-11-06'),
(9, 'wqhdv', 'Água', 223.00, 'dqd', '2024-11-07'),
(10, 'Água', 'Água', 12.00, 'Água c/ Gás', '2024-11-30'),
(11, 'Gás', 'Gás', 79.00, 'Gás 13kg', '2024-11-30'),
(13, 'Água', 'Água', 20.00, 'Ágau c Gás', '2024-11-21'),
(14, 'Água', 'Água', 12.00, 'Agua c gas', '2024-11-14');

--
-- Acionadores `tb_produtos`
--
DELIMITER $$
CREATE TRIGGER `insere_produto_no_estoque` AFTER INSERT ON `tb_produtos` FOR EACH ROW BEGIN
    INSERT INTO tb_estoque (pro_codigo, est_qtde)
    VALUES (NEW.pro_codigo, 0); -- Aqui, 0 é o valor inicial da quantidade de estoque para um novo produto
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_produto_fornecedor`
--

CREATE TABLE `tb_produto_fornecedor` (
  `pro_codigo` int(11) NOT NULL,
  `for_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_produto_fornecedor`
--

INSERT INTO `tb_produto_fornecedor` (`pro_codigo`, `for_codigo`) VALUES
(11, 17),
(14, 19);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_vendas`
--

CREATE TABLE `tb_vendas` (
  `ven_codigo` int(11) NOT NULL,
  `cli_codigo` int(11) DEFAULT NULL,
  `ven_data_lancamento` date DEFAULT NULL,
  `ven_data_entrega` timestamp NULL DEFAULT NULL,
  `ven_tipo_venda` varchar(50) NOT NULL,
  `ven_status_entrega` varchar(50) DEFAULT NULL,
  `cre_codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_vendas`
--

INSERT INTO `tb_vendas` (`ven_codigo`, `cli_codigo`, `ven_data_lancamento`, `ven_data_entrega`, `ven_tipo_venda`, `ven_status_entrega`, `cre_codigo`) VALUES
(7, 1, '0000-00-00', '0000-00-00 00:00:00', ' ', ' ', NULL),
(130, 19, '2024-11-06', '2024-11-06 17:38:00', 'Entrega', 'Entregue', 7),
(131, 18, '2024-11-06', '0000-00-00 00:00:00', 'Presencial', '', 7),
(132, 18, '2024-11-07', '2024-11-07 18:24:00', 'Entrega', 'Entregue', 7),
(133, 19, '2024-11-07', '2024-11-08 04:30:00', 'Entrega', 'Pendente', 7),
(135, 19, '2024-11-07', '2024-11-08 00:18:00', 'Entrega', 'Entregue', 7),
(136, 21, '2024-11-07', '2024-11-08 01:35:00', 'Entrega', 'Pendente', 7);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_calendario`
--
ALTER TABLE `tb_calendario`
  ADD PRIMARY KEY (`cal_id`);

--
-- Índices de tabela `tb_clientes`
--
ALTER TABLE `tb_clientes`
  ADD PRIMARY KEY (`cli_codigo`);

--
-- Índices de tabela `tb_compra`
--
ALTER TABLE `tb_compra`
  ADD PRIMARY KEY (`com_codigo`),
  ADD KEY `for_codigo` (`for_codigo`),
  ADD KEY `fk_despesas2` (`des_codigo`);

--
-- Índices de tabela `tb_contas_pagar`
--
ALTER TABLE `tb_contas_pagar`
  ADD PRIMARY KEY (`cp_codigo`),
  ADD KEY `com_codigo` (`com_codigo`),
  ADD KEY `fk_despesas` (`des_codigo`);

--
-- Índices de tabela `tb_contas_receber`
--
ALTER TABLE `tb_contas_receber`
  ADD PRIMARY KEY (`cr_codigo`),
  ADD KEY `parc_codigo` (`parc_codigo`),
  ADD KEY `fk_credito` (`cre_codigo`);

--
-- Índices de tabela `tb_credito`
--
ALTER TABLE `tb_credito`
  ADD PRIMARY KEY (`cre_codigo`);

--
-- Índices de tabela `tb_despesas`
--
ALTER TABLE `tb_despesas`
  ADD PRIMARY KEY (`des_codigo`);

--
-- Índices de tabela `tb_estoque`
--
ALTER TABLE `tb_estoque`
  ADD PRIMARY KEY (`est_codigo`),
  ADD KEY `pro_codigo` (`pro_codigo`);

--
-- Índices de tabela `tb_fluxo_caixa`
--
ALTER TABLE `tb_fluxo_caixa`
  ADD PRIMARY KEY (`fc_codigo`),
  ADD KEY `cp_codigo` (`cp_codigo`),
  ADD KEY `cr_codigo` (`cr_codigo`);

--
-- Índices de tabela `tb_fornecedores`
--
ALTER TABLE `tb_fornecedores`
  ADD PRIMARY KEY (`for_codigo`);

--
-- Índices de tabela `tb_itens_compra`
--
ALTER TABLE `tb_itens_compra`
  ADD PRIMARY KEY (`itc_codigo`),
  ADD KEY `com_codigo` (`com_codigo`),
  ADD KEY `pro_codigo` (`pro_codigo`);

--
-- Índices de tabela `tb_itens_venda`
--
ALTER TABLE `tb_itens_venda`
  ADD PRIMARY KEY (`itv_codigo`),
  ADD KEY `ven_codigo` (`ven_codigo`),
  ADD KEY `pro_codigo` (`pro_codigo`);

--
-- Índices de tabela `tb_login`
--
ALTER TABLE `tb_login`
  ADD PRIMARY KEY (`log_codigo`);

--
-- Índices de tabela `tb_pagamento`
--
ALTER TABLE `tb_pagamento`
  ADD PRIMARY KEY (`pag_codigo`),
  ADD KEY `ven_codigo` (`ven_codigo`);

--
-- Índices de tabela `tb_parcelas`
--
ALTER TABLE `tb_parcelas`
  ADD PRIMARY KEY (`parc_codigo`),
  ADD KEY `pag_codigo` (`pag_codigo`);

--
-- Índices de tabela `tb_produtos`
--
ALTER TABLE `tb_produtos`
  ADD PRIMARY KEY (`pro_codigo`);

--
-- Índices de tabela `tb_produto_fornecedor`
--
ALTER TABLE `tb_produto_fornecedor`
  ADD PRIMARY KEY (`pro_codigo`,`for_codigo`),
  ADD KEY `for_codigo` (`for_codigo`);

--
-- Índices de tabela `tb_vendas`
--
ALTER TABLE `tb_vendas`
  ADD PRIMARY KEY (`ven_codigo`),
  ADD KEY `cli_codigo` (`cli_codigo`),
  ADD KEY `fk_credito2` (`cre_codigo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_calendario`
--
ALTER TABLE `tb_calendario`
  MODIFY `cal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `tb_clientes`
--
ALTER TABLE `tb_clientes`
  MODIFY `cli_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `tb_compra`
--
ALTER TABLE `tb_compra`
  MODIFY `com_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `tb_contas_pagar`
--
ALTER TABLE `tb_contas_pagar`
  MODIFY `cp_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de tabela `tb_contas_receber`
--
ALTER TABLE `tb_contas_receber`
  MODIFY `cr_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT de tabela `tb_credito`
--
ALTER TABLE `tb_credito`
  MODIFY `cre_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `tb_despesas`
--
ALTER TABLE `tb_despesas`
  MODIFY `des_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tb_estoque`
--
ALTER TABLE `tb_estoque`
  MODIFY `est_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `tb_fluxo_caixa`
--
ALTER TABLE `tb_fluxo_caixa`
  MODIFY `fc_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT de tabela `tb_fornecedores`
--
ALTER TABLE `tb_fornecedores`
  MODIFY `for_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `tb_itens_compra`
--
ALTER TABLE `tb_itens_compra`
  MODIFY `itc_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `tb_itens_venda`
--
ALTER TABLE `tb_itens_venda`
  MODIFY `itv_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de tabela `tb_login`
--
ALTER TABLE `tb_login`
  MODIFY `log_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `tb_pagamento`
--
ALTER TABLE `tb_pagamento`
  MODIFY `pag_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de tabela `tb_parcelas`
--
ALTER TABLE `tb_parcelas`
  MODIFY `parc_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de tabela `tb_produtos`
--
ALTER TABLE `tb_produtos`
  MODIFY `pro_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `tb_vendas`
--
ALTER TABLE `tb_vendas`
  MODIFY `ven_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_compra`
--
ALTER TABLE `tb_compra`
  ADD CONSTRAINT `fk_despesas2` FOREIGN KEY (`des_codigo`) REFERENCES `tb_despesas` (`des_codigo`),
  ADD CONSTRAINT `tb_compra_ibfk_1` FOREIGN KEY (`for_codigo`) REFERENCES `tb_fornecedores` (`for_codigo`);

--
-- Restrições para tabelas `tb_contas_pagar`
--
ALTER TABLE `tb_contas_pagar`
  ADD CONSTRAINT `fk_despesas` FOREIGN KEY (`des_codigo`) REFERENCES `tb_despesas` (`des_codigo`),
  ADD CONSTRAINT `tb_contas_pagar_ibfk_1` FOREIGN KEY (`com_codigo`) REFERENCES `tb_compra` (`com_codigo`);

--
-- Restrições para tabelas `tb_contas_receber`
--
ALTER TABLE `tb_contas_receber`
  ADD CONSTRAINT `fk_credito` FOREIGN KEY (`cre_codigo`) REFERENCES `tb_credito` (`cre_codigo`),
  ADD CONSTRAINT `tb_contas_receber_ibfk_1` FOREIGN KEY (`parc_codigo`) REFERENCES `tb_parcelas` (`parc_codigo`);

--
-- Restrições para tabelas `tb_estoque`
--
ALTER TABLE `tb_estoque`
  ADD CONSTRAINT `tb_estoque_ibfk_1` FOREIGN KEY (`pro_codigo`) REFERENCES `tb_produtos` (`pro_codigo`);

--
-- Restrições para tabelas `tb_fluxo_caixa`
--
ALTER TABLE `tb_fluxo_caixa`
  ADD CONSTRAINT `tb_fluxo_caixa_ibfk_1` FOREIGN KEY (`cr_codigo`) REFERENCES `tb_contas_receber` (`cr_codigo`),
  ADD CONSTRAINT `tb_fluxo_caixa_ibfk_2` FOREIGN KEY (`cp_codigo`) REFERENCES `tb_contas_pagar` (`cp_codigo`),
  ADD CONSTRAINT `tb_fluxo_caixa_ibfk_3` FOREIGN KEY (`cr_codigo`) REFERENCES `tb_contas_receber` (`cr_codigo`);

--
-- Restrições para tabelas `tb_itens_compra`
--
ALTER TABLE `tb_itens_compra`
  ADD CONSTRAINT `tb_itens_compra_ibfk_1` FOREIGN KEY (`com_codigo`) REFERENCES `tb_compra` (`com_codigo`),
  ADD CONSTRAINT `tb_itens_compra_ibfk_2` FOREIGN KEY (`pro_codigo`) REFERENCES `tb_produtos` (`pro_codigo`);

--
-- Restrições para tabelas `tb_itens_venda`
--
ALTER TABLE `tb_itens_venda`
  ADD CONSTRAINT `tb_itens_venda_ibfk_1` FOREIGN KEY (`ven_codigo`) REFERENCES `tb_vendas` (`ven_codigo`),
  ADD CONSTRAINT `tb_itens_venda_ibfk_2` FOREIGN KEY (`pro_codigo`) REFERENCES `tb_produtos` (`pro_codigo`);

--
-- Restrições para tabelas `tb_pagamento`
--
ALTER TABLE `tb_pagamento`
  ADD CONSTRAINT `tb_pagamento_ibfk_1` FOREIGN KEY (`ven_codigo`) REFERENCES `tb_vendas` (`ven_codigo`);

--
-- Restrições para tabelas `tb_parcelas`
--
ALTER TABLE `tb_parcelas`
  ADD CONSTRAINT `tb_parcelas_ibfk_1` FOREIGN KEY (`pag_codigo`) REFERENCES `tb_pagamento` (`pag_codigo`);

--
-- Restrições para tabelas `tb_produto_fornecedor`
--
ALTER TABLE `tb_produto_fornecedor`
  ADD CONSTRAINT `tb_produto_fornecedor_ibfk_1` FOREIGN KEY (`pro_codigo`) REFERENCES `tb_produtos` (`pro_codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_produto_fornecedor_ibfk_2` FOREIGN KEY (`for_codigo`) REFERENCES `tb_fornecedores` (`for_codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_vendas`
--
ALTER TABLE `tb_vendas`
  ADD CONSTRAINT `fk_credito2` FOREIGN KEY (`cre_codigo`) REFERENCES `tb_credito` (`cre_codigo`),
  ADD CONSTRAINT `tb_vendas_ibfk_1` FOREIGN KEY (`cli_codigo`) REFERENCES `tb_clientes` (`cli_codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
