-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2015 at 12:42 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trubby`
--

-- --------------------------------------------------------

--
-- Table structure for table `cardapio`
--

CREATE TABLE IF NOT EXISTS `cardapio` (
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `preco_venda` decimal(15,5) NOT NULL,
  `alerta_amarelo` decimal(15,5) NOT NULL,
  `alerta_vermelho` decimal(15,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cardapio`
--

INSERT INTO `cardapio` (`id_usuario`, `id_produto`, `preco_venda`, `alerta_amarelo`, `alerta_vermelho`) VALUES
(4, 5, '0.90000', '1.00000', '1.00000'),
(4, 6, '23.10000', '1.00000', '1.00000');

-- --------------------------------------------------------

--
-- Table structure for table `estoque`
--

CREATE TABLE IF NOT EXISTS `estoque` (
`id_estoque` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(1000) NOT NULL,
  `quantidade` decimal(15,5) DEFAULT NULL,
  `quantidade_tipo` set('Kg','Lt','Mç','Us','Co','Dz','Qb') DEFAULT NULL,
  `custo` decimal(15,5) NOT NULL,
  `data_modificacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fichas`
--

CREATE TABLE IF NOT EXISTS `fichas` (
`id_ficha` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(1000) NOT NULL,
  `modo_preparo` text,
  `seq_montagem` text,
  `equipamento` text,
  `n_porcoes` decimal(15,5) DEFAULT NULL,
  `peso_porcao` decimal(15,5) DEFAULT NULL COMMENT 'expresso em Kg',
  `obs` text,
  `foto` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ingredientes_uso`
--

CREATE TABLE IF NOT EXISTS `ingredientes_uso` (
  `id_ficha` int(10) unsigned NOT NULL,
  `id_estoque` int(10) unsigned NOT NULL,
  `quantidade_liq` decimal(15,5) unsigned NOT NULL,
  `quantidade_liq_tipo` set('Kg','Lt','Mç','Us','Co','Dz','Qb') NOT NULL,
  `quantidade_brt` int(10) unsigned NOT NULL,
  `quantidade_brt_tipo` set('Kg','Lt','Mç','Us','Co','Dz','Qb') NOT NULL,
  `rendimento` decimal(15,5) unsigned NOT NULL DEFAULT '100.00000',
  `tipo` set('primario','secundario') NOT NULL DEFAULT 'primario',
  `preco_extra` decimal(15,5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista e especifica os ingredientes em uso em cada ficha técnica cadastrada';

-- --------------------------------------------------------

--
-- Table structure for table `produto`
--

CREATE TABLE IF NOT EXISTS `produto` (
`id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_ficha` int(11) DEFAULT NULL,
  `id_estoque` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Consolidação de produtos em fichas técnicas e estoque para o cardápio' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
`id_usuario` int(11) NOT NULL,
  `nome` varchar(1000) NOT NULL,
  `sobrenome` varchar(1000) NOT NULL,
  `cpf` bigint(20) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `senha` char(40) NOT NULL COMMENT 'hash',
  `dt_nasc` date NOT NULL,
  `tel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vendas`
--

CREATE TABLE IF NOT EXISTS `vendas` (
`id_venda` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vendas_itens`
--

CREATE TABLE IF NOT EXISTS `vendas_itens` (
  `id_venda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_venda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cardapio`
--
ALTER TABLE `cardapio`
 ADD PRIMARY KEY (`id_usuario`,`id_produto`);

--
-- Indexes for table `estoque`
--
ALTER TABLE `estoque`
 ADD PRIMARY KEY (`id_estoque`), ADD UNIQUE KEY `id_estoque` (`id_estoque`), ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `fichas`
--
ALTER TABLE `fichas`
 ADD PRIMARY KEY (`id_ficha`), ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `ingredientes_uso`
--
ALTER TABLE `ingredientes_uso`
 ADD PRIMARY KEY (`id_ficha`,`id_estoque`);

--
-- Indexes for table `produto`
--
ALTER TABLE `produto`
 ADD PRIMARY KEY (`id_produto`), ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
 ADD PRIMARY KEY (`id_usuario`), ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Indexes for table `vendas`
--
ALTER TABLE `vendas`
 ADD PRIMARY KEY (`id_venda`), ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `vendas_itens`
--
ALTER TABLE `vendas_itens`
 ADD PRIMARY KEY (`id_venda`,`id_produto`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `estoque`
--
ALTER TABLE `estoque`
MODIFY `id_estoque` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fichas`
--
ALTER TABLE `fichas`
MODIFY `id_ficha` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `produto`
--
ALTER TABLE `produto`
MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vendas`
--
ALTER TABLE `vendas`
MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
