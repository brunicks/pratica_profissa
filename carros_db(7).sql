-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/04/2025 às 20:56
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
-- Banco de dados: `carros_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carros`
--

CREATE TABLE `carros` (
  `id` int(11) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `ano` int(11) NOT NULL,
  `marca_id` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `km` int(11) NOT NULL DEFAULT 0,
  `descricao` text NOT NULL DEFAULT '',
  `destaque` tinyint(1) DEFAULT 0,
  `cambio` varchar(50) DEFAULT 'Manual',
  `combustivel` varchar(50) DEFAULT 'Gasolina',
  `cor` varchar(50) NOT NULL DEFAULT '',
  `potencia` varchar(50) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `placa` varchar(10) DEFAULT NULL,
  `versao` varchar(100) DEFAULT NULL,
  `portas` int(1) DEFAULT NULL,
  `final_placa` int(1) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `status` varchar(20) DEFAULT 'disponivel'
) ;

--
-- Despejando dados para a tabela `carros`
--

INSERT INTO `carros` (`id`, `modelo`, `ano`, `marca_id`, `imagem`, `preco`, `km`, `descricao`, `destaque`, `cambio`, `combustivel`, `cor`, `potencia`, `created_at`, `placa`, `versao`, `portas`, `final_placa`, `views`, `status`) VALUES
(1, 'Opala 6cil', 1974, 1, 'uploads/carros/1743030528_opala.jpg', 0.00, 0, '', 1, 'Manual', 'Gasolina', '', '', '2025-04-02 22:53:38', NULL, NULL, NULL, NULL, 2, 'disponivel'),
(2, 'Fuscaaaaaaa', 1982, 2, 'uploads/carros/1743030535_fucsa.jpg', 0.00, 0, 'aaaaaaaaaaaaaaaaaaaa', 1, 'Manual', 'Gasolina', 'azul', '', '2025-04-02 22:53:38', NULL, NULL, NULL, NULL, 23, 'disponivel');

--
-- Acionadores `carros`
--
DELIMITER $$
CREATE TRIGGER `tr_carros_placa_insert` BEFORE INSERT ON `carros` FOR EACH ROW BEGIN
    IF NEW.placa IS NOT NULL AND NEW.placa != '' THEN
        SET NEW.final_placa = CAST(SUBSTRING(NEW.placa, -1, 1) AS UNSIGNED);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_carros_placa_update` BEFORE UPDATE ON `carros` FOR EACH ROW BEGIN
    IF NEW.placa IS NOT NULL AND NEW.placa != '' THEN
        SET NEW.final_placa = CAST(SUBSTRING(NEW.placa, -1, 1) AS UNSIGNED);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `galeria_carros`
--

CREATE TABLE `galeria_carros` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `marcas`
--

INSERT INTO `marcas` (`id`, `nome`) VALUES
(1, 'Chevrolet'),
(2, 'Volkswagen'),
(3, 'Porsche'),
(4, 'Toyota'),
(5, 'BMW');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT 0,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acesso` datetime DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `telefone`, `admin`, `data_cadastro`, `ultimo_acesso`, `ativo`) VALUES
(1, 'Admin', 'admin@exemplo.com', '$2y$10$URujO75d7YwxCGDEtT7I4.LxVK4FBife8bcyld8p3H5Vlkb8lFv.K', NULL, 1, '2025-04-15 11:42:44', '2025-04-15 14:23:05', 1),
(2, 'bruno santossss', 'brunicks02@gmail.com', '$2y$10$j7tGBpxdDvxXxpeqfAubdevNMNtu0E/CKe9Bz3BfLhs8ARv/aiZi2', '41997640205', 0, '2025-04-15 12:04:51', '2025-04-15 14:22:48', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_carros_marca_id` (`marca_id`),
  ADD KEY `idx_carros_destaque` (`destaque`);

--
-- Índices de tabela `galeria_carros`
--
ALTER TABLE `galeria_carros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carro_id` (`carro_id`);

--
-- Índices de tabela `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `galeria_carros`
--
ALTER TABLE `galeria_carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carros`
--
ALTER TABLE `carros`
  ADD CONSTRAINT `carros_ibfk_1` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);

--
-- Restrições para tabelas `galeria_carros`
--
ALTER TABLE `galeria_carros`
  ADD CONSTRAINT `galeria_carros_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
