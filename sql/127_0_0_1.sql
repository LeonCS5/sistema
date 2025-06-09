-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/06/2025 às 16:08
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

INSERT INTO `pedidos` (`id`, `usuario_id`, `cliente_nome`, `data_hora`, `endereco`, `forma_pagamento`, `status`, `status_atualizado_em`) VALUES
(1, NULL, 'Cliente não identificado', '2025-06-02 22:42:25', 'rua Carlos 200', 'PIX', 'Entregado', NULL),
(2, 2, 'Carl Johnson', '2025-06-02 22:46:27', 'Rua Carlos De Carvalho 200', 'PIX', 'Entregado', '2025-06-03 04:13:38'),
(3, NULL, 'Cliente não identificado', '2025-06-08 11:31:45', 'rua Carlos 200', 'Dinheiro', 'Recebido', NULL),
(4, 4, 'Matheus', '2025-06-08 14:24:46', 'Rua Matheus', 'PIX', 'Entregado', NULL),
(5, 5, 'Aiwass', '2025-06-08 14:35:25', 'Rua Awaiss', 'Boleto', 'Recebido', NULL);

--
-- Despejando dados para a tabela `pedido_itens`
--

INSERT INTO `pedido_itens` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`) VALUES
(2, 2, 2, 2, 13.00),
(3, 3, 1, 1, 12.00),
(4, 4, 11, 3, 12.00),
(5, 5, 4, 3, 14.00);

--
-- Despejando dados para a tabela `pedido_status_historico`
--

INSERT INTO `pedido_status_historico` (`id`, `pedido_id`, `status`, `data_hora`) VALUES
(1, 2, 'Entregado', '2025-06-02 23:10:30'),
(2, 2, 'Recebido', '2025-06-02 23:10:33'),
(3, 2, 'Enviado', '2025-06-02 23:10:37'),
(4, 2, 'Entregado', '2025-06-02 23:10:40'),
(5, 2, 'Entregado', '2025-06-02 23:13:38'),
(6, 3, 'Recebido', '2025-06-08 11:32:50'),
(7, 4, 'Entregado', '2025-06-08 14:25:35');

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `quantidade`, `disponivel`, `imagem`) VALUES
(2, 'Água Poa 20L', 'Garanta a hidratação ideal com a Água Mineral Natural POA 20L, perfeita para residências, escritórios e estabelecimentos comerciais. Captada de fontes puras e protegidas, a Água POA é sinônimo de qualidade, leveza e sabor refrescante.\r\n\r\nEste galão de 20 litros é econômico, sustentável e compatível com bebedouros e suportes tradicionais. Ideal para o consumo diário, ela passa por rigorosos processos de controle e análise para manter sua pureza e preservar os minerais essenciais à saúde.', 25.00, 197, 1, '6845cab79f2d8_6845aae163d76_6844fc4ed3602_6842300ba0bdf_6842292243d96.webp'),
(4, 'Água Mogiana 20L', 'Refresque seu dia com a leveza e a pureza da Água Mineral Mogiana 20L, extraída de fontes naturais cuidadosamente preservadas. Com composição equilibrada de sais minerais e sabor suave, a Mogiana é ideal para quem busca hidratação saudável no dia a dia.\r\n\r\nSeu galão retornável de 20 litros é perfeito para famílias, escritórios, comércios e qualquer ambiente que exija consumo constante de água de qualidade. Compatível com bombas manuais, elétricas e bebedouros padrão.', 19.00, 95, 1, '6846de8a77e00_6844fce978ee9_68423cfd57ac0-removebg-preview.png'),
(7, 'Água Castelo 20L', 'Hidrate-se com qualidade e confiança com a Água Mineral Natural Castelo 20L, perfeita para quem valoriza pureza, leveza e um sabor natural no dia a dia. Captada em fontes cuidadosamente preservadas, a Água Castelo passa por rigorosos processos de controle que garantem a segurança e o equilíbrio ideal de sais minerais.\r\n\r\nCom embalagem retornável, o galão de 20 litros é a escolha ideal para residências, empresas e comércios que buscam economia e sustentabilidade, sem abrir mão da qualidade.', 15.00, 84, 1, '6844fc85a6bc0_68422fc2e2e03_68422937e2c70_ete-removebg-preview.png'),
(9, 'Rocha Branca 20L', 'O galão de água mineral de 20 litros sem vasilhame é a escolha perfeita para a sua casa e/ou escritório.\r\nAlém da qualidade da água com um pH de 7,35, você pode se hidratar com todos os benefícios que a água Rocha Branca oferece.', 22.00, 100, 1, '68423cb7738d6.png'),
(12, 'Crystal 20L', 'Mantenha-se sempre hidratado com a Água Mineral Crystal 20L, perfeita para quem busca qualidade, confiança e sabor leve no dia a dia. Extraída de fontes cuidadosamente selecionadas, a Crystal passa por um rigoroso controle de qualidade, garantindo pureza e equilíbrio mineral.\r\n\r\nO galão de 20 litros é ideal para famílias, empresas e ambientes com alto consumo, sendo compatível com a maioria dos bebedouros e suportes. Além disso, a embalagem retornável contribui para um consumo mais consciente e sustentável.', 12.00, 4, 1, '6845099e06b30_6844fc69d3eb7_6842292e01499_agua_crystal_20L_01_1200-removebg-preview.png'),
(13, 'Água Crystal 500ml', 'Água Mineral Crystal é uma das melhores marcas de água mineral sem gás disponíveis no mercado. Leve e saudável, é obtida diretamente de fontes naturais e contém os principais sais minerais necessários para proporcionar mais vitalidade e ajudar na hidratação do seu corpo. Compre Água Mineral sem Gás Crystal Garrafão e mantenha seu corpo hidratado com água mineral de alta qualidade!', 1.52, 52, 1, '6846e0daa0faa.png'),
(14, 'Água Crystal Com Gás 500ml', 'Água Mineral Crystal é uma das melhores marcas de água mineral com gás disponíveis no mercado. Leve e saudável, é obtida diretamente de fontes naturais e contém os principais sais minerais necessários para proporcionar mais vitalidade e ajudar na hidratação do seu corpo. Compre Água Mineral com Gás Crystal Garrafa e mantenha seu corpo hidratado com água mineral de alta qualidade!', 1.70, 65, 1, '6846e1b0e24dd.png'),
(15, 'Água Crystal Copo 300ml', 'Água Mineral Crystal é uma das melhores marcas de água mineral sem gás disponíveis no mercado. Leve e saudável, é obtida diretamente de fontes naturais e contém os principais sais minerais necessários para proporcionar mais vitalidade e ajudar na hidratação do seu corpo. Compre Água Mineral sem Gás Crystal Garrafão e mantenha seu corpo hidratado com água mineral de alta qualidade!', 0.93, 200, 1, '6846e4e396ebb.png'),
(16, 'Água de Coco Mais Coco 1L', 'Rica em vitaminas, minerais, aminoácidos, carboidratos, antioxidantes, enzimas e outros nutrientes que só fazem bem ao organismo.\r\nUma das qualidades mais importantes da água de coco é promover a hidratação, inclusive da pele, tornando-a mais bonita.', 11.90, 20, 1, '6846e642ac2fd.png'),
(17, 'Água de Coco Mais Coco 200ml', 'Rica em vitaminas, minerais, aminoácidos, carboidratos, antioxidantes, enzimas e outros nutrientes que só fazem bem ao organismo.\r\nUma das qualidades mais importantes da água de coco é promover a hidratação, inclusive da pele, tornando-a mais bonita.', 2.39, 40, 1, '6846e6ba0f7b5.png'),
(18, 'Água Aromatizada Crystal Sparkling 510ml', 'Sabor: Limão e Camomila\r\nCrystal Sabores, um jeito diferente de se hidratar. E tudo com apenas dois ingredientes: água com gás e toques de aromas naturais.', 5.10, 49, 1, '6846e7d28c52d.png'),
(19, 'Água Crystal 1.5L', 'Água Mineral Crystal é uma das melhores marcas de água mineral sem gás disponíveis no mercado. Leve e saudável, é obtida diretamente de fontes naturais e contém os principais sais minerais necessários para proporcionar mais vitalidade e ajudar na hidratação do seu corpo. Compre Água Mineral sem Gás Crystal Garrafão e mantenha seu corpo hidratado com água mineral de alta qualidade!', 2.89, 99, 1, '6846e95f86d7f.png'),
(20, 'Água Crystal Com Gás 1.5L', 'Água Mineral Crystal é uma das melhores marcas de água mineral com gás disponíveis no mercado. Leve e saudável, é obtida diretamente de fontes naturais e contém os principais sais minerais necessários para proporcionar mais vitalidade e ajudar na hidratação do seu corpo. Compre Água Mineral com Gás Crystal Garrafa e mantenha seu corpo hidratado com água mineral de alta qualidade!', 2.96, 99, 1, '6846e9cc65438.png'),
(21, 'Kit Água', 'Contém em nosso Kit:\r\n12 Unidades: Água Crystal sem gás, 500ml\r\n12 Unidades: Água Crystal com gás, 500ml\r\n6 Unidades:  Água Crystal sem gás, 1,5L\r\n6 Unidades:  Água Crystal com gás, 1,5L', 73.32, 20, 1, '6846ea5daee04.png');

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `login`, `senha`, `nome`, `endereco`, `forma_pagamento`, `imagem`, `is_admin`) VALUES
(1, 'admin', '$2y$10$QQrBdquktPD1URI9g5SA8u3sVbF5OXwJ88RbnsyXlTRHdONKBqGjK', 'Administrador', 'Endereço Admin', 'Cartão', '', 1),
(2, 'CJ', '$2y$10$TNS0zkL0I/Y0pIsVhx4AD.zLZ8U99YgRZlNY4kmLT6JUuwc2p9ivG', 'Carl Johnson', 'Rua Carlos De Carvalho 200', 'PIX', '', 0),
(3, 'a', '$2y$10$XtrzPFK/AKX9iSrebDfYE.l2qBdPy15PwtcqiH9A9GY1DCiTb9XCi', 'a', 'a', 'PIX', '6845b13b4df2d.jpg', 0),
(4, 'matheus', '$2y$10$xNbVg/hwsIH5K2Sn/n0x9ebZnRERv9UKxsPjlc1Uszmf2wWom2yU.', 'Matheus', 'Rua Matheus', 'Cartão_Credito', '6845c74710e70.png', 0),
(5, 'aiwass', '$2y$10$nqdcsLCySL0YPjaNgbgOfuE9YBYTCnA/TuUJVSYwNWuQzE/ReO4QK', 'Aiwass', 'Rua Awaiss', 'PIX', '6845c8ba99543.jpg', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
