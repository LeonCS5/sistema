-- sistema/sql/create_tables.sql
CREATE DATABASE IF NOT EXISTS sistema;
USE sistema;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    endereco TEXT NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    imagem VARCHAR (255),
    is_admin BOOLEAN DEFAULT 0
);

-- Tabela de produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    imagem VARCHAR (255),
    disponivel BOOLEAN DEFAULT 1
    
);

-- Tabela de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    endereco TEXT NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    status ENUM('Recebido', 'Enviado', 'Entregado') DEFAULT 'Recebido',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabela de itens do pedido
CREATE TABLE pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

-- Tabela Status do pedido --
CREATE TABLE pedido_status_historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    data_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE 
);

-- Inserir um administrador padrão
-- sistema/sql/create_tables.sql (corrigido)
CREATE DATABASE IF NOT EXISTS sistema;
USE sistema;

-- ... (outras tabelas permanecem iguais) ...

-- Corrigindo o admin com senha 'admin123'
INSERT INTO usuarios (login, senha, nome, endereco, forma_pagamento, is_admin) 
VALUES ('admin', '$2y$10$Wq2A5bH0z6cZ7q0J1KbV.OU6I7zQ1S9gR1eX0aG3nY8xJ2vN1dW6', 'Administrador', 'Endereço Admin', 'Cartão', 1);