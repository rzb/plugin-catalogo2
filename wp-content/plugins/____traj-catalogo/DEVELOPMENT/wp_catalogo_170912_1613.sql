-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tempo de Geração: Set 17, 2012 as 04:11 PM
-- Versão do Servidor: 5.0.51
-- Versão do PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Banco de Dados: `wp_teste`
-- 

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `traj_palavras`
-- 

CREATE TABLE `traj_palavras` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `palavra` varchar(45) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `traj_palavras`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `traj_trabalhos`
-- 

CREATE TABLE `traj_trabalhos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `autor` varchar(45) NOT NULL,
  `titulo` varchar(45) NOT NULL,
  `revista` varchar(45) NOT NULL,
  `resumo` text NOT NULL,
  `volume` int(10) NOT NULL,
  `numero` int(10) NOT NULL,
  `primeira_pag` int(10) NOT NULL,
  `ultima_pag` int(10) NOT NULL,
  `ano` year(4) NOT NULL,
  `palavra_ids` int(10) NOT NULL,
  `data_criacao` datetime NOT NULL,
  `data_modificacao` datetime NOT NULL,
  `fotocopias` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `traj_trabalhos`
-- 

