-- Tabela de usuários
CREATE TABLE usuarios (
    id_usuario INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo VARCHAR(20) DEFAULT 'cliente',
    PRIMARY KEY (id_usuario),
    UNIQUE KEY email (email)
) ENGINE=InnoDB;

-- Tabela de categorias
CREATE TABLE categorias (
    id_categoria INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    PRIMARY KEY (id_categoria)
) ENGINE=InnoDB;

-- Tabela de produtos
CREATE TABLE produtos (
    id_produto INT(11) NOT NULL AUTO_INCREMENT,
    id_categoria INT(11) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT DEFAULT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT(11) NOT NULL DEFAULT 0,
    imagem VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id_produto),
    KEY produtos_ibfk_1 (id_categoria),
    CONSTRAINT produtos_ibfk_1
        FOREIGN KEY (id_categoria)
        REFERENCES categorias (id_categoria)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
) ENGINE=InnoDB;

-- Tabela de pedidos
CREATE TABLE pedidos (
    id_pedido INT(11) NOT NULL AUTO_INCREMENT,
    id_usuario INT(11) NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    forma_pagamento VARCHAR(30) NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_pedido),
    KEY pedidos_ibfk_1 (id_usuario),
    CONSTRAINT pedidos_ibfk_1
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios (id_usuario)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
) ENGINE=InnoDB;

-- Tabela de itens dos pedidos
CREATE TABLE itens_pedido (
    id_item INT(11) NOT NULL AUTO_INCREMENT,
    id_pedido INT(11) NOT NULL,
    id_produto INT(11) NOT NULL,
    quantidade INT(11) NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_item),
    KEY itens_pedido_ibfk_1 (id_pedido),
    KEY itens_pedido_ibfk_2 (id_produto),
    CONSTRAINT itens_pedido_ibfk_1
        FOREIGN KEY (id_pedido)
        REFERENCES pedidos (id_pedido)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,
    CONSTRAINT itens_pedido_ibfk_2
        FOREIGN KEY (id_produto)
        REFERENCES produtos (id_produto)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
) ENGINE=InnoDB;

-- Categorias iniciais
INSERT INTO categorias (id_categoria, nome) VALUES
(1, 'Smartphones'),
(2, 'Notebooks'),
(3, 'Acessórios'),
(4, 'Games');

-- Produtos iniciais
INSERT INTO produtos
(id_produto, nome, descricao, preco, estoque, id_categoria, imagem)
VALUES
(1, 'Samsung Galaxy', 'Smartphone com excelente desempenho.', 1499.90, 6, 1, NULL),
(4, 'PlayStation 5', 'Console PlayStation 5 com gráficos em 4K.', 3999.90, 11, 4, NULL),
(5, 'Notebook Lenovo', 'Notebook Lenovo V14 com design moderno e compacto.', 3299.99, 13, 2, NULL),
(6, 'IPhone Duo', 'O iPhone Duo é o primeiro smartphone dobrável da Apple.', 29000.00, 5, 1, NULL),
(7, 'Carregador Samsung USB-C 25W', 'Carregador Samsung USB-C de 25W com tecnologia de carregamento rápido.', 89.90, 25, 3, NULL);
