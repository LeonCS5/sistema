-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/06/2025 às 19:14
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
-- Banco de dados: `sistema`
--
CREATE DATABASE IF NOT EXISTS `sistema` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema`;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `data_hora`, `endereco`, `forma_pagamento`, `status`, `status_atualizado_em`) VALUES
(1, NULL, '2025-06-02 22:42:25', 'rua Carlos 200', 'PIX', 'Entregado', NULL),
(2, 2, '2025-06-02 22:46:27', 'Rua Carlos De Carvalho 200', 'PIX', 'Entregado', '2025-06-03 04:13:38'),
(3, 3, '2025-06-05 21:09:39', 'rua matheus', 'Boleto', 'Entregado', NULL),
(4, NULL, '2025-06-06 19:50:46', 'asdasdas', 'Cartão', 'Recebido', NULL),
(5, 3, '2025-06-06 19:51:18', 'rua matheus', 'Boleto', 'Recebido', NULL),
(6, NULL, '2025-06-06 23:33:01', 'asdasdas', 'Cartão', 'Recebido', NULL),
(7, NULL, '2025-06-07 01:30:34', 'asdasdas', 'PIX', 'Entregado', NULL),
(8, 3, '2025-06-07 01:39:02', 'rua matheus', 'PIX', 'Enviado', NULL),
(9, 3, '2025-06-07 02:23:18', 'rua matheus', 'Boleto', 'Entregado', NULL);

--
-- Despejando dados para a tabela `pedido_itens`
--

INSERT INTO `pedido_itens` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`) VALUES
(2, 2, 2, 2, 13.00),
(6, 4, 2, 1, 13.00),
(7, 4, 4, 1, 14.00),
(10, 5, 7, 1, 15.00),
(11, 6, 9, 1, 17.00),
(13, 7, 7, 1, 15.00),
(15, 9, 7, 4, 15.00),
(16, 9, 4, 1, 14.00);

--
-- Despejando dados para a tabela `pedido_status_historico`
--

INSERT INTO `pedido_status_historico` (`id`, `pedido_id`, `status`, `data_hora`) VALUES
(1, 2, 'Entregado', '2025-06-02 23:10:30'),
(2, 2, 'Recebido', '2025-06-02 23:10:33'),
(3, 2, 'Enviado', '2025-06-02 23:10:37'),
(4, 2, 'Entregado', '2025-06-02 23:10:40'),
(5, 2, 'Entregado', '2025-06-02 23:13:38'),
(6, 3, 'Enviado', '2025-06-05 21:09:58'),
(7, 3, 'Entregado', '2025-06-05 21:10:03'),
(8, 7, 'Entregado', '2025-06-07 01:38:11'),
(9, 9, 'Entregado', '2025-06-08 02:42:51'),
(10, 8, 'Entregado', '2025-06-08 02:43:00'),
(11, 8, 'Enviado', '2025-06-08 02:43:04');

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `quantidade`, `disponivel`, `imagem`) VALUES
(2, 'Água Poa 20L', 'Garanta a hidratação ideal com a Água Mineral Natural POA 20L, perfeita para residências, escritórios e estabelecimentos comerciais. Captada de fontes puras e protegidas, a Água POA é sinônimo de qualidade, leveza e sabor refrescante.\r\n\r\nEste galão de 20 litros é econômico, sustentável e compatível com bebedouros e suportes tradicionais. Ideal para o consumo diário, ela passa por rigorosos processos de controle e análise para manter sua pureza e preservar os minerais essenciais à saúde.', 25.00, 197, 1, '6842300ba0bdf_6842292243d96_ÁGUAMINERALINDUGAS.webp'),
(4, 'agua mogiana 20L', 'Água Mogiana', 14.00, 98, 1, '6844fc69d3eb7_6842292e01499_agua_crystal_20L_01_1200-removebg-preview.png'),
(7, 'Agua Lopes 20l', 'agua lopes', 15.00, 84, 1, '6844fc85a6bc0_68422fc2e2e03_68422937e2c70_ete-removebg-preview.png'),
(9, 'Rocha Branca 20L', 'O galão de água mineral de 20 litros sem vasilhame é a escolha perfeita para a sua casa e/ou escritório.\r\nAlém da qualidade da água com um pH de 7,35, você pode se hidratar com todos os benefícios que a água Rocha Branca oferece.', 22.00, 100, 1, '68423cb7738d6.png'),
(10, 'agua 23', 'agua teste 21', 12.00, 12, 1, '6844fc5f99d54_6842292e01499_agua_crystal_20L_01_1200-removebg-preview.png'),
(11, 'Agua Teste 20l', 'Agua Teste', 12.00, 99, 1, '684394c51db29.webp'),
(12, 'Crystal 20L', 'Mantenha-se sempre hidratado com a Água Mineral Crystal 20L, perfeita para quem busca qualidade, confiança e sabor leve no dia a dia. Extraída de fontes cuidadosamente selecionadas, a Crystal passa por um rigoroso controle de qualidade, garantindo pureza e equilíbrio mineral.\r\n\r\nO galão de 20 litros é ideal para famílias, empresas e ambientes com alto consumo, sendo compatível com a maioria dos bebedouros e suportes. Além disso, a embalagem retornável contribui para um consumo mais consciente e sustentável.', 12.00, 4, 1, '6845099e06b30_6844fc69d3eb7_6842292e01499_agua_crystal_20L_01_1200-removebg-preview.png');

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `login`, `senha`, `nome`, `endereco`, `forma_pagamento`, `imagem`, `is_admin`) VALUES
(1, 'admin', '$2y$10$IAlegNsJm538mP3tctyMfurBnDoJ7GxffI1hUKyNJTS.LmGebYu6u', 'Administrador', 'Endereço Admin', 'Cartão', '', 1),
(2, 'CJ', '$2y$10$TNS0zkL0I/Y0pIsVhx4AD.zLZ8U99YgRZlNY4kmLT6JUuwc2p9ivG', 'Carl Johnson', 'Rua Carlos De Carvalho 200', 'PIX', '', 0),
(3, 'matheus', '$2y$10$OTBkV60lIAxu2gEPvrsa0uLdY2NDwZNCIOPKatlIOFkV5UOBUZIe2', 'Matheus Paulo', 'rua matheus', 'Boleto', '68422f82702e3.png', 0),
(4, 'aiwass', '$2y$10$S/lTRhsoMdrY9Ai1YkiXoeq.HrakmYgfKTtsu/ITYw5amyxbtZEIS', 'Aiwass', 'asdasdasd', 'Cartão', '684259d2f1684.png', 0),
(5, 'admin2', '$2y$10$3SzJvQhpsA1.w3V/oGtW..7v4Vh/ZcZKwr5.M0ajSPes2k7KxfmVC', 'asdasd', '123123', 'Cartão', '6844d270c08ae.jpg', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
