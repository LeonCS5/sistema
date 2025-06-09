-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 06:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema`
--
CREATE DATABASE IF NOT EXISTS sistema;
USE sistema;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cliente_nome` varchar(100) NOT NULL,
  `data_hora` datetime DEFAULT current_timestamp(),
  `endereco` text NOT NULL,
  `forma_pagamento` varchar(50) NOT NULL,
  `status` enum('Recebido','Enviado','Entregado') DEFAULT 'Recebido',
  `status_atualizado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `cliente_nome`, `data_hora`, `endereco`, `forma_pagamento`, `status`, `status_atualizado_em`) VALUES
(1, NULL, 'Cliente não identificado', '2025-06-02 22:42:25', 'rua Carlos 200', 'PIX', 'Entregado', NULL),
(2, 2, 'Carl Johnson', '2025-06-02 22:46:27', 'Rua Carlos De Carvalho 200', 'PIX', 'Entregado', '2025-06-03 04:13:38'),
(3, NULL, 'Cliente não identificado', '2025-06-08 11:31:45', 'rua Carlos 200', 'Dinheiro', 'Recebido', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedido_itens`
--

INSERT INTO `pedido_itens` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`) VALUES
(2, 2, 2, 2, 13.00),
(3, 3, 1, 1, 12.00);

-- --------------------------------------------------------

--
-- Table structure for table `pedido_status_historico`
--

CREATE TABLE `pedido_status_historico` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `data_hora` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedido_status_historico`
--

INSERT INTO `pedido_status_historico` (`id`, `pedido_id`, `status`, `data_hora`) VALUES
(1, 2, 'Entregado', '2025-06-02 23:10:30'),
(2, 2, 'Recebido', '2025-06-02 23:10:33'),
(3, 2, 'Enviado', '2025-06-02 23:10:37'),
(4, 2, 'Entregado', '2025-06-02 23:10:40'),
(5, 2, 'Entregado', '2025-06-02 23:13:38'),
(6, 3, 'Recebido', '2025-06-08 11:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `disponivel` tinyint(1) DEFAULT 1,
  `imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `quantidade`, `disponivel`, `imagem`) VALUES
(2, 'Água Poa 20L', 'Garanta a hidratação ideal com a Água Mineral Natural POA 20L, perfeita para residências, escritórios e estabelecimentos comerciais. Captada de fontes puras e protegidas, a Água POA é sinônimo de qualidade, leveza e sabor refrescante.\r\n\r\nEste galão de 20 litros é econômico, sustentável e compatível com bebedouros e suportes tradicionais. Ideal para o consumo diário, ela passa por rigorosos processos de controle e análise para manter sua pureza e preservar os minerais essenciais à saúde.', 25.00, 197, 1, '6842300ba0bdf_6842292243d96_ÁGUAMINERALINDUGAS.webp'),
(4, 'agua mogiana 20L', 'Água Mogiana', 14.00, 98, 1, '6844fc69d3eb7_6842292e01499_agua_crystal_20L_01_1200-removebg-preview.png'),
(7, 'Agua Lopes 20l', 'agua lopes', 15.00, 84, 1, '6844fc85a6bc0_68422fc2e2e03_68422937e2c70_ete-removebg-preview.png'),
(9, 'Rocha Branca 20L', 'O galão de água mineral de 20 litros sem vasilhame é a escolha perfeita para a sua casa e/ou escritório.\r\nAlém da qualidade da água com um pH de 7,35, você pode se hidratar com todos os benefícios que a água Rocha Branca oferece.', 22.00, 100, 1, '68423cb7738d6.png'),
(10, 'agua 23', 'agua teste 21', 12.00, 12, 1, '6844fc5f99d54_6842292e01499_agua_crystal_20L_01_1200-removebg-preview.png'),
(11, 'Agua Teste 20l', 'Agua Teste', 12.00, 99, 1, '684394c51db29.webp'),
(12, 'Crystal 20L', 'Mantenha-se sempre hidratado com a Água Mineral Crystal 20L, perfeita para quem busca qualidade, confiança e sabor leve no dia a dia. Extraída de fontes cuidadosamente selecionadas, a Crystal passa por um rigoroso controle de qualidade, garantindo pureza e equilíbrio mineral.\r\n\r\nO galão de 20 litros é ideal para famílias, empresas e ambientes com alto consumo, sendo compatível com a maioria dos bebedouros e suportes. Além disso, a embalagem retornável contribui para um consumo mais consciente e sustentável.', 12.00, 4, 1, '6845099e06b30_6844fc69d3eb7_6842292e01499_agua_crystal_20L_01_1200-removebg-preview.png');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` text NOT NULL,
  `forma_pagamento` varchar(50) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `login`, `senha`, `nome`, `endereco`, `forma_pagamento`, `imagem`, `is_admin`) VALUES
(1, 'admin', '$2y$10$Ca0GOYCdgRxk3dn9Ec.NM.vYKOY6zZ7tvpWpLSjtFE4qDn6v5JaHK', 'Administrador', 'Endereço Admin', 'Cartão', '', 1),
(2, 'CJ', '$2y$10$TNS0zkL0I/Y0pIsVhx4AD.zLZ8U99YgRZlNY4kmLT6JUuwc2p9ivG', 'Carl Johnson', 'Rua Carlos De Carvalho 200', 'PIX', '', 0),
(3, 'a', '$2y$10$XtrzPFK/AKX9iSrebDfYE.l2qBdPy15PwtcqiH9A9GY1DCiTb9XCi', 'a', 'a', 'PIX', '6845b13b4df2d.jpg', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Indexes for table `pedido_status_historico`
--
ALTER TABLE `pedido_status_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pedido_status_historico`
--
ALTER TABLE `pedido_status_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `pedido_itens_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_itens_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pedido_status_historico`
--
ALTER TABLE `pedido_status_historico`
  ADD CONSTRAINT `pedido_status_historico_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
