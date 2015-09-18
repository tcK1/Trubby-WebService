-- --------------------------------------------------------
-- Servidor:                     54.94.128.189
-- Versão do servidor:           5.5.44-0ubuntu0.14.04.1 - (Ubuntu)
-- OS do Servidor:               debian-linux-gnu
-- HeidiSQL Versão:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela trubby.cardapio
CREATE TABLE IF NOT EXISTS `cardapio` (
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `preco_venda` decimal(15,2) NOT NULL,
  `alerta_amarelo` decimal(15,5) NOT NULL,
  `alerta_vermelho` decimal(15,5) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela trubby.estoque
CREATE TABLE IF NOT EXISTS `estoque` (
  `id_estoque` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(1000) NOT NULL,
  `quantidade` decimal(15,5) DEFAULT NULL,
  `quantidade_tipo` set('Kg','Lt','Mç','Us','Co','Dz','Qb') DEFAULT NULL,
  `custo` decimal(15,2) NOT NULL,
  `data_modificacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_estoque`),
  UNIQUE KEY `id_estoque` (`id_estoque`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela trubby.fichas
CREATE TABLE IF NOT EXISTS `fichas` (
  `id_ficha` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(1000) NOT NULL,
  `modo_preparo` text,
  `seq_montagem` text,
  `equipamento` text,
  `n_porcoes` decimal(15,5) DEFAULT NULL,
  `peso_porcao` decimal(15,5) DEFAULT NULL COMMENT 'expresso em Kg',
  `obs` text,
  `foto` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_ficha`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela trubby.ingredientes_uso
CREATE TABLE IF NOT EXISTS `ingredientes_uso` (
  `id_ficha` int(10) unsigned NOT NULL,
  `id_estoque` int(10) unsigned NOT NULL,
  `quantidade_liq` decimal(15,5) unsigned NOT NULL,
  `quantidade_liq_tipo` set('Kg','Lt','Mç','Us','Co','Dz','Qb') NOT NULL,
  `quantidade_brt` int(10) unsigned NOT NULL,
  `quantidade_brt_tipo` set('Kg','Lt','Mç','Us','Co','Dz','Qb') NOT NULL,
  `rendimento` decimal(15,5) unsigned NOT NULL DEFAULT '100.00000',
  `tipo` set('primario','secundario') NOT NULL DEFAULT 'primario',
  `preco_extra` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id_ficha`,`id_estoque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista e especifica os ingredientes em uso em cada ficha técnica cadastrada';

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela trubby.produto
CREATE TABLE IF NOT EXISTS `produto` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_ficha` int(11) DEFAULT NULL,
  `id_estoque` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Consolidação de produtos em fichas técnicas e estoque para o cardápio';

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela trubby.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(1000) NOT NULL,
  `sobrenome` varchar(1000) NOT NULL,
  `cpf` bigint(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` char(40) NOT NULL COMMENT 'hash',
  `dt_nasc` date NOT NULL,
  `tel` varchar(20) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela trubby.vendas
CREATE TABLE IF NOT EXISTS `vendas` (
  `id_venda` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_venda`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela trubby.vendas_itens
CREATE TABLE IF NOT EXISTS `vendas_itens` (
  `id_venda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_venda` int(11) NOT NULL,
  PRIMARY KEY (`id_venda`,`id_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
