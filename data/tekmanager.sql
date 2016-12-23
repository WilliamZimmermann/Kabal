-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Dec 23, 2016 at 03:39 PM
-- Server version: 5.5.49-log
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tekmanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `article_category`
--

CREATE TABLE IF NOT EXISTS `article_category` (
  `idCategory` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `article_category`
--

INSERT INTO `article_category` (`idCategory`, `website_id`, `title`, `active`) VALUES
(3, 30, 'Variedades', 1),
(4, 30, 'Esportes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `article_category_has_language`
--

CREATE TABLE IF NOT EXISTS `article_category_has_language` (
  `idCategoryLanguage` bigint(20) NOT NULL,
  `language_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `article_category_has_language`
--

INSERT INTO `article_category_has_language` (`idCategoryLanguage`, `language_id`, `category_id`, `title`, `slug`, `active`) VALUES
(2, 1, 4, 'Esportes', 'esportes', 1),
(3, 2, 4, 'Sports', 'sports', 1),
(6, 1, 3, 'Variedades', 'variedades', 0);

-- --------------------------------------------------------

--
-- Table structure for table `article_has_category`
--

CREATE TABLE IF NOT EXISTS `article_has_category` (
  `language_idArticle` bigint(20) NOT NULL,
  `language_idCategory` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `article_has_category`
--

INSERT INTO `article_has_category` (`language_idArticle`, `language_idCategory`) VALUES
(1, 2),
(3, 2),
(2, 3),
(1, 6),
(3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `article_has_language`
--

CREATE TABLE IF NOT EXISTS `article_has_language` (
  `idArticleLanguage` bigint(20) NOT NULL,
  `language_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `title` varchar(85) NOT NULL,
  `description` varchar(200) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `resume` varchar(200) NOT NULL,
  `section_title` varchar(100) DEFAULT NULL,
  `section_description` varchar(250) DEFAULT NULL,
  `content` text NOT NULL,
  `lastUpdateDate` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `article_has_language`
--

INSERT INTO `article_has_language` (`idArticleLanguage`, `language_id`, `article_id`, `title`, `description`, `slug`, `resume`, `section_title`, `section_description`, `content`, `lastUpdateDate`, `active`) VALUES
(1, 1, 1, 'Novo site da TekSul', 'O novo site da TekSul foi lançado', 'novo-site-da-teksul', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec lorem ac elit ornare vulputate id at est. Integer at sollicitudin nisl. Etiam rutrum metus in lorem lacinia laoreet.&nbsp;', NULL, NULL, '<p>Teste de par&aacute;grafos.</p>\r\n\r\n<p>Esse &eacute; o seguindo par&aacute;grafo.</p>\r\n\r\n<p>Esse &eacute; o terceiro.</p>\r\n', '2016-12-14 19:33:12', 1),
(2, 2, 1, 'New TekSul website', 'Was released the new TekSul website', 'new-teksul-website', 'New TekSul Website released', NULL, NULL, '<p><span font-size:="" open="" style="color: rgb(119, 119, 119); font-family: ">New TekSul Website released</span></p>\r\n', '2016-12-14 19:33:35', 1),
(3, 1, 2, 'Artigo de Teste', 'Artigo de teste', 'artigo-de-teste', 'Esse é um artigo teste', NULL, NULL, '<p>Esse &eacute; um artigo teste</p>\r\n', '2016-12-14 19:32:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE IF NOT EXISTS `color` (
  `idColor` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `hexa` varchar(45) NOT NULL,
  `image` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `idCompany` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `max_users` smallint(3) NOT NULL DEFAULT '1',
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`idCompany`, `name`, `status`, `max_users`, `creationDate`) VALUES
(1, 'TekSul Informática', 1, 999, '2016-12-16 17:23:57'),
(12, 'Dilo Fiação', 1, 1, '2016-12-16 17:24:10'),
(13, 'TekNorte Sistemas', 1, 1, '2016-10-26 19:22:50'),
(14, 'MaxPet', 1, 2, '2016-12-16 17:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `company_user`
--

CREATE TABLE IF NOT EXISTS `company_user` (
  `idUser` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `type` smallint(1) NOT NULL DEFAULT '2',
  `name` varchar(60) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(35) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_user`
--

INSERT INTO `company_user` (`idUser`, `company_id`, `type`, `name`, `email`, `password`, `status`, `creationDate`) VALUES
(1, 1, 1, 'William Zimmermann', 'william.zimmer@teksul.com.br', '938dceb208db2a75fb64f6743a9c1311', 1, '2016-10-13 20:11:28'),
(2, 12, 2, 'Administrador', 'adm@dilofiacao.com.br', '938dceb208db2a75fb64f6743a9c1311', 1, '2016-10-14 18:39:47'),
(6, 13, 2, 'Pandolfo', 'pandolfo@teknorte.com.br', '938dceb208db2a75fb64f6743a9c1311', 1, '2016-10-26 19:48:28'),
(8, 14, 2, 'Administrador', 'adm@maxpet.com.br', '938dceb208db2a75fb64f6743a9c1311', 1, '2016-12-16 18:30:29'),
(9, 14, 3, 'Adalberto Barroso', 'adalberto@maxpet.com.br', '938dceb208db2a75fb64f6743a9c1311', 1, '2016-12-16 19:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `company_user_has_company_website`
--

CREATE TABLE IF NOT EXISTS `company_user_has_company_website` (
  `company_user_idUser` int(11) NOT NULL,
  `company_website_idWebsite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_user_has_company_website`
--

INSERT INTO `company_user_has_company_website` (`company_user_idUser`, `company_website_idWebsite`) VALUES
(1, 30),
(2, 32),
(6, 31),
(8, 33),
(9, 33),
(1, 33);

-- --------------------------------------------------------

--
-- Table structure for table `company_user_permissions`
--

CREATE TABLE IF NOT EXISTS `company_user_permissions` (
  `company_user_idUser` int(11) NOT NULL,
  `website_module_idWebsite` int(11) NOT NULL,
  `website_module_idModule` int(11) NOT NULL,
  `insertP` tinyint(1) NOT NULL DEFAULT '0',
  `editP` tinyint(1) NOT NULL DEFAULT '0',
  `deleteP` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_user_permissions`
--

INSERT INTO `company_user_permissions` (`company_user_idUser`, `website_module_idWebsite`, `website_module_idModule`, `insertP`, `editP`, `deleteP`) VALUES
(1, 30, 1, 1, 1, 1),
(1, 30, 2, 1, 1, 1),
(1, 30, 3, 1, 1, 1),
(1, 30, 6, 1, 1, 1),
(1, 30, 7, 1, 1, 1),
(1, 33, 2, 1, 1, 1),
(1, 33, 3, 1, 1, 1),
(1, 33, 9, 1, 1, 1),
(2, 32, 3, 1, 1, 1),
(6, 31, 2, 1, 1, 1),
(6, 31, 3, 1, 1, 1),
(6, 31, 4, 1, 1, 1),
(6, 31, 5, 1, 1, 1),
(8, 33, 2, 1, 1, 1),
(8, 33, 3, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `company_website`
--

CREATE TABLE IF NOT EXISTS `company_website` (
  `idWebsite` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `apiKey` varchar(33) DEFAULT NULL,
  `apiIp` varchar(45) DEFAULT NULL,
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_website`
--

INSERT INTO `company_website` (`idWebsite`, `company_id`, `name`, `status`, `apiKey`, `apiIp`, `creationDate`) VALUES
(30, 1, 'TekSul Informática', 1, 'ZsvXrKOzOyDQh4kCQc3V7TAnpfoOJBrf', '127.0.0.1', '2016-10-19 17:23:17'),
(31, 13, 'TekNorte Sistemas', 1, NULL, NULL, '2016-10-21 18:28:21'),
(32, 12, 'Dilo Fiação', 1, NULL, NULL, '2016-10-25 19:08:53'),
(33, 14, 'Pedidos Online', 1, NULL, NULL, '2016-12-16 19:35:57');

-- --------------------------------------------------------

--
-- Table structure for table `company_website_has_language`
--

CREATE TABLE IF NOT EXISTS `company_website_has_language` (
  `company_website_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_website_has_language`
--

INSERT INTO `company_website_has_language` (`company_website_id`, `language_id`) VALUES
(30, 1),
(30, 2),
(30, 4);

-- --------------------------------------------------------

--
-- Table structure for table `company_website_has_system_module`
--

CREATE TABLE IF NOT EXISTS `company_website_has_system_module` (
  `company_website_idWebsite` int(11) NOT NULL,
  `system_module_idModule` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_website_has_system_module`
--

INSERT INTO `company_website_has_system_module` (`company_website_idWebsite`, `system_module_idModule`) VALUES
(30, 1),
(30, 2),
(31, 2),
(33, 2),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(31, 4),
(31, 5),
(30, 6),
(30, 7),
(33, 9);

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `countryId` smallint(3) NOT NULL,
  `name` varchar(70) NOT NULL,
  `name2` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`countryId`, `name`, `name2`) VALUES
(1, 'AFEGANISTÃO', 'AFGHANISTAN'),
(2, 'ACROTÍRI E DECELIA', 'AKROTIRI E DEKÉLIA'),
(3, 'ÁFRICA DO SUL', 'SOUTH AFRICA'),
(4, 'ALBÂNIA', 'ALBANIA'),
(5, 'ALEMANHA', 'GERMANY'),
(6, 'AMERICAN SAMOA', 'AMERICAN SAMOA'),
(7, 'ANDORRA', 'ANDORRA'),
(8, 'ANGOLA', 'ANGOLA'),
(9, 'ANGUILLA', 'ANGUILLA'),
(10, 'ANTÍGUA E BARBUDA', 'ANTIGUA AND BARBUDA'),
(11, 'ANTILHAS NEERLANDESAS', 'NETHERLANDS ANTILLES'),
(12, 'ARÁBIA SAUDITA', 'SAUDI ARABIA'),
(13, 'ARGÉLIA', 'ALGERIA'),
(14, 'ARGENTINA', 'ARGENTINA'),
(15, 'ARMÉNIA', 'ARMENIA'),
(16, 'ARUBA', 'ARUBA'),
(17, 'AUSTRÁLIA', 'AUSTRALIA'),
(18, 'ÁUSTRIA', 'AUSTRIA'),
(19, 'AZERBAIJÃO', 'AZERBAIJAN'),
(20, 'BAHAMAS', 'BAHAMAS, THE'),
(21, 'BANGLADECHE', 'BANGLADESH'),
(22, 'BARBADOS', 'BARBADOS'),
(23, 'BARÉM', 'BAHRAIN'),
(24, 'BASSAS DA ÍNDIA', 'BASSAS DA INDIA'),
(25, 'BÉLGICA', 'BELGIUM'),
(26, 'BELIZE', 'BELIZE'),
(27, 'BENIM', 'BENIN'),
(28, 'BERMUDAS', 'BERMUDA'),
(29, 'BIELORRÚSSIA', 'BELARUS'),
(30, 'BOLÍVIA', 'BOLIVIA'),
(31, 'BÓSNIA E HERZEGOVINA', 'BOSNIA AND HERZEGOVINA'),
(32, 'BOTSUANA', 'BOTSWANA'),
(33, 'BRASIL', 'BRAZIL'),
(34, 'BRUNEI DARUSSALAM', 'BRUNEI DARUSSALAM'),
(35, 'BULGÁRIA', 'BULGARIA'),
(36, 'BURQUINA FASO', 'BURKINA FASO'),
(37, 'BURUNDI', 'BURUNDI'),
(38, 'BUTÃO', 'BHUTAN'),
(39, 'CABO VERDE', 'CAPE VERDE'),
(40, 'CAMARÕES', 'CAMEROON'),
(41, 'CAMBOJA', 'CAMBODIA'),
(42, 'CANADÁ', 'CANADA'),
(43, 'CATAR', 'QATAR'),
(44, 'CAZAQUISTÃO', 'KAZAKHSTAN'),
(45, 'CENTRO-AFRICANA REPÚBLICA', 'CENTRAL AFRICAN REPUBLIC'),
(46, 'CHADE', 'CHAD'),
(47, 'CHILE', 'CHILE'),
(48, 'CHINA', 'CHINA'),
(49, 'CHIPRE', 'CYPRUS'),
(50, 'COLÔMBIA', 'COLOMBIA'),
(51, 'COMORES', 'COMOROS'),
(52, 'CONGO', 'CONGO'),
(53, 'CONGO REPÚBLICA DEMOCRÁTICA', 'CONGO DEMOCRATIC REPUBLIC'),
(54, 'COREIA DO NORTE', 'KOREA NORTH'),
(55, 'COREIA DO SUL', 'KOREA SOUTH'),
(56, 'COSTA DO MARFIM', 'IVORY COAST'),
(57, 'COSTA RICA', 'COSTA RICA'),
(58, 'CROÁCIA', 'CROATIA'),
(59, 'CUBA', 'CUBA'),
(60, 'DINAMARCA', 'DENMARK'),
(61, 'DOMÍNICA', 'DOMINICA'),
(62, 'EGIPTO', 'EGYPT'),
(63, 'EMIRADOS ÁRABES UNIDOS', 'UNITED ARAB EMIRATES'),
(64, 'EQUADOR', 'ECUADOR'),
(65, 'ERITREIA', 'ERITREA'),
(66, 'ESLOVÁQUIA', 'SLOVAKIA'),
(67, 'ESLOVÉNIA', 'SLOVENIA'),
(68, 'ESPANHA', 'SPAIN'),
(69, 'ESTADOS UNIDOS', 'UNITED STATES'),
(70, 'ESTÓNIA', 'ESTONIA'),
(71, 'ETIÓPIA', 'ETHIOPIA'),
(72, 'FAIXA DE GAZA', 'GAZA STRIP'),
(73, 'FIJI', 'FIJI'),
(74, 'FILIPINAS', 'PHILIPPINES'),
(75, 'FINLÂNDIA', 'FINLAND'),
(76, 'FRANÇA', 'FRANCE'),
(77, 'GABÃO', 'GABON'),
(78, 'GÂMBIA', 'GAMBIA'),
(79, 'GANA', 'GHANA'),
(80, 'GEÓRGIA', 'GEORGIA'),
(81, 'GIBRALTAR', 'GIBRALTAR'),
(82, 'GRANADA', 'GRENADA'),
(83, 'GRÉCIA', 'GREECE'),
(84, 'GRONELÂNDIA', 'GREENLAND'),
(85, 'GUADALUPE', 'GUADELOUPE'),
(86, 'GUAM', 'GUAM'),
(87, 'GUATEMALA', 'GUATEMALA'),
(88, 'GUERNSEY', 'GUERNSEY'),
(89, 'GUIANA', 'GUYANA'),
(90, 'GUIANA FRANCESA', 'FRENCH GUIANA'),
(91, 'GUINÉ', 'GUINEA'),
(92, 'GUINÉ EQUATORIAL', 'EQUATORIAL GUINEA'),
(93, 'GUINÉ-BISSAU', 'GUINEA-BISSAU'),
(94, 'HAITI', 'HAITI'),
(95, 'HONDURAS', 'HONDURAS'),
(96, 'HONG KONG', 'HONG KONG'),
(97, 'HUNGRIA', 'HUNGARY'),
(98, 'IÉMEN', 'YEMEN'),
(99, 'ILHA BOUVET', 'BOUVET ISLAND'),
(100, 'ILHA CHRISTMAS', 'CHRISTMAS ISLAND'),
(101, 'ILHA DE CLIPPERTON', 'CLIPPERTON ISLAND'),
(102, 'ILHA DE JOÃO DA NOVA', 'JUAN DE NOVA ISLAND'),
(103, 'ILHA DE MAN', 'ISLE OF MAN'),
(104, 'ILHA DE NAVASSA', 'NAVASSA ISLAND'),
(105, 'ILHA EUROPA', 'EUROPA ISLAND'),
(106, 'ILHA NORFOLK', 'NORFOLK ISLAND'),
(107, 'ILHA TROMELIN', 'TROMELIN ISLAND'),
(108, 'ILHAS ASHMORE E CARTIER', 'ASHMORE AND CARTIER ISLANDS'),
(109, 'ILHAS CAIMAN', 'CAYMAN ISLANDS'),
(110, 'ILHAS COCOS (KEELING)', 'COCOS (KEELING) ISLANDS'),
(111, 'ILHAS COOK', 'COOK ISLANDS'),
(112, 'ILHAS DO MAR DE CORAL', 'CORAL SEA ISLANDS'),
(113, 'ILHAS FALKLANDS (ILHAS MALVINAS)', 'FALKLAND ISLANDS (ISLAS MALVINAS)'),
(114, 'ILHAS FEROE', 'FAROE ISLANDS'),
(115, 'ILHAS GEÓRGIA DO SUL E SANDWICH DO SUL', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS'),
(116, 'ILHAS MARIANAS DO NORTE', 'NORTHERN MARIANA ISLANDS'),
(117, 'ILHAS MARSHALL', 'MARSHALL ISLANDS'),
(118, 'ILHAS PARACEL', 'PARACEL ISLANDS'),
(119, 'ILHAS PITCAIRN', 'PITCAIRN ISLANDS'),
(120, 'ILHAS SALOMÃO', 'SOLOMON ISLANDS'),
(121, 'ILHAS SPRATLY', 'SPRATLY ISLANDS'),
(122, 'ILHAS VIRGENS AMERICANAS', 'UNITED STATES VIRGIN ISLANDS'),
(123, 'ILHAS VIRGENS BRITÂNICAS', 'BRITISH VIRGIN ISLANDS'),
(124, 'ÍNDIA', 'INDIA'),
(125, 'INDONÉSIA', 'INDONESIA'),
(126, 'IRÃO', 'IRAN'),
(127, 'IRAQUE', 'IRAQ'),
(128, 'IRLANDA', 'IRELAND'),
(129, 'ISLÂNDIA', 'ICELAND'),
(130, 'ISRAEL', 'ISRAEL'),
(131, 'ITÁLIA', 'ITALY'),
(132, 'JAMAICA', 'JAMAICA'),
(133, 'JAN MAYEN', 'JAN MAYEN'),
(134, 'JAPÃO', 'JAPAN'),
(135, 'JERSEY', 'JERSEY'),
(136, 'JIBUTI', 'DJIBOUTI'),
(137, 'JORDÂNIA', 'JORDAN'),
(138, 'KIRIBATI', 'KIRIBATI'),
(139, 'KOWEIT', 'KUWAIT'),
(140, 'LAOS', 'LAOS'),
(141, 'LESOTO', 'LESOTHO'),
(142, 'LETÓNIA', 'LATVIA'),
(143, 'LÍBANO', 'LEBANON'),
(144, 'LIBÉRIA', 'LIBERIA'),
(145, 'LÍBIA', 'LIBYAN ARAB JAMAHIRIYA'),
(146, 'LISTENSTAINE', 'LIECHTENSTEIN'),
(147, 'LITUÂNIA', 'LITHUANIA'),
(148, 'LUXEMBURGO', 'LUXEMBOURG'),
(149, 'MACAU', 'MACAO'),
(150, 'MACEDÓNIA', 'MACEDONIA'),
(151, 'MADAGÁSCAR', 'MADAGASCAR'),
(152, 'MALÁSIA', 'MALAYSIA'),
(153, 'MALAVI', 'MALAWI'),
(154, 'MALDIVAS', 'MALDIVES'),
(155, 'MALI', 'MALI'),
(156, 'MALTA', 'MALTA'),
(157, 'MARROCOS', 'MOROCCO'),
(158, 'MARTINICA', 'MARTINIQUE'),
(159, 'MAURÍCIA', 'MAURITIUS'),
(160, 'MAURITÂNIA', 'MAURITANIA'),
(161, 'MAYOTTE', 'MAYOTTE'),
(162, 'MÉXICO', 'MEXICO'),
(163, 'MIANMAR', 'MYANMAR BURMA'),
(164, 'MICRONÉSIA', 'MICRONESIA'),
(165, 'MOÇAMBIQUE', 'MOZAMBIQUE'),
(166, 'MOLDÁVIA', 'MOLDOVA'),
(167, 'MÓNACO', 'MONACO'),
(168, 'MONGÓLIA', 'MONGOLIA'),
(169, 'MONTENEGRO', 'MONTENEGRO'),
(170, 'MONTSERRAT', 'MONTSERRAT'),
(171, 'NAMÍBIA', 'NAMIBIA'),
(172, 'NAURU', 'NAURU'),
(173, 'NEPAL', 'NEPAL'),
(174, 'NICARÁGUA', 'NICARAGUA'),
(175, 'NÍGER', 'NIGER'),
(176, 'NIGÉRIA', 'NIGERIA'),
(177, 'NIUE', 'NIUE'),
(178, 'NORUEGA', 'NORWAY'),
(179, 'NOVA CALEDÓNIA', 'NEW CALEDONIA'),
(180, 'NOVA ZELÂNDIA', 'NEW ZEALAND'),
(181, 'OMÃ', 'OMAN'),
(182, 'PAÍSES BAIXOS', 'NETHERLANDS'),
(183, 'PALAU', 'PALAU'),
(184, 'PALESTINA', 'PALESTINE'),
(185, 'PANAMÁ', 'PANAMA'),
(186, 'PAPUÁSIA-NOVA GUINÉ', 'PAPUA NEW GUINEA'),
(187, 'PAQUISTÃO', 'PAKISTAN'),
(188, 'PARAGUAI', 'PARAGUAY'),
(189, 'PERU', 'PERU'),
(190, 'POLINÉSIA FRANCESA', 'FRENCH POLYNESIA'),
(191, 'POLÓNIA', 'POLAND'),
(192, 'PORTO RICO', 'PUERTO RICO'),
(193, 'PORTUGAL', 'PORTUGAL'),
(194, 'QUÉNIA', 'KENYA'),
(195, 'QUIRGUIZISTÃO', 'KYRGYZSTAN'),
(196, 'REINO UNIDO', 'UNITED KINGDOM'),
(197, 'REPÚBLICA CHECA', 'CZECH REPUBLIC'),
(198, 'REPÚBLICA DOMINICANA', 'DOMINICAN REPUBLIC'),
(199, 'ROMÉNIA', 'ROMANIA'),
(200, 'RUANDA', 'RWANDA'),
(201, 'RÚSSIA', 'RUSSIAN FEDERATION'),
(202, 'SAHARA OCCIDENTAL', 'WESTERN SAHARA'),
(203, 'SALVADOR', 'EL SALVADOR'),
(204, 'SAMOA', 'SAMOA'),
(205, 'SANTA HELENA', 'SAINT HELENA'),
(206, 'SANTA LÚCIA', 'SAINT LUCIA'),
(207, 'SANTA SÉ', 'HOLY SEE'),
(208, 'SÃO CRISTÓVÃO E NEVES', 'SAINT KITTS AND NEVIS'),
(209, 'SÃO MARINO', 'SAN MARINO'),
(210, 'SÃO PEDRO E MIQUELÃO', 'SAINT PIERRE AND MIQUELON'),
(211, 'SÃO TOMÉ E PRÍNCIPE', 'SAO TOME AND PRINCIPE'),
(212, 'SÃO VICENTE E GRANADINAS', 'SAINT VINCENT AND THE GRENADINES'),
(213, 'SEICHELES', 'SEYCHELLES'),
(214, 'SENEGAL', 'SENEGAL'),
(215, 'SERRA LEOA', 'SIERRA LEONE'),
(216, 'SÉRVIA', 'SERBIA'),
(217, 'SINGAPURA', 'SINGAPORE'),
(218, 'SÍRIA', 'SYRIA'),
(219, 'SOMÁLIA', 'SOMALIA'),
(220, 'SRI LANCA', 'SRI LANKA'),
(221, 'SUAZILÂNDIA', 'SWAZILAND'),
(222, 'SUDÃO', 'SUDAN'),
(223, 'SUÉCIA', 'SWEDEN'),
(224, 'SUÍÇA', 'SWITZERLAND'),
(225, 'SURINAME', 'SURINAME'),
(226, 'SVALBARD', 'SVALBARD'),
(227, 'TAILÂNDIA', 'THAILAND'),
(228, 'TAIWAN', 'TAIWAN'),
(229, 'TAJIQUISTÃO', 'TAJIKISTAN'),
(230, 'TANZÂNIA', 'TANZANIA'),
(231, 'TERRITÓRIO BRITÂNICO DO OCEANO ÍNDICO', 'BRITISH INDIAN OCEAN TERRITORY'),
(232, 'TERRITÓRIO DAS ILHAS HEARD E MCDONALD', 'HEARD ISLAND AND MCDONALD ISLANDS'),
(233, 'TIMOR-LESTE', 'TIMOR-LESTE'),
(234, 'TOGO', 'TOGO'),
(235, 'TOKELAU', 'TOKELAU'),
(236, 'TONGA', 'TONGA'),
(237, 'TRINDADE E TOBAGO', 'TRINIDAD AND TOBAGO'),
(238, 'TUNÍSIA', 'TUNISIA'),
(239, 'TURKS E CAICOS', 'TURKS AND CAICOS ISLANDS'),
(240, 'TURQUEMENISTÃO', 'TURKMENISTAN'),
(241, 'TURQUIA', 'TURKEY'),
(242, 'TUVALU', 'TUVALU'),
(243, 'UCRÂNIA', 'UKRAINE'),
(244, 'UGANDA', 'UGANDA'),
(245, 'URUGUAI', 'URUGUAY'),
(246, 'USBEQUISTÃO', 'UZBEKISTAN'),
(247, 'VANUATU', 'VANUATU'),
(248, 'VENEZUELA', 'VENEZUELA'),
(249, 'VIETNAME', 'VIETNAM'),
(250, 'WALLIS E FUTUNA', 'WALLIS AND FUTUNA'),
(251, 'ZÂMBIA', 'ZAMBIA'),
(252, 'ZIMBABUÉ', 'ZIMBABWE');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `idCustomer` bigint(20) NOT NULL,
  `company_id` int(11) NOT NULL,
  `addedBy` int(11) DEFAULT NULL,
  `customerType` smallint(1) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(35) NOT NULL,
  `birthDate` date NOT NULL,
  `country_id` smallint(3) NOT NULL,
  `comments` text,
  `log` text,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`idCustomer`, `company_id`, `addedBy`, `customerType`, `email`, `password`, `birthDate`, `country_id`, `comments`, `log`, `dateCreated`, `dateUpdated`, `active`) VALUES
(1, 1, 1, 2, 'william.zimmer@teksul.com.br', '938dceb208db2a75fb64f6743a9c1311', '1999-01-01', 33, 'Teste\r\nOutro teste\r\nMais um teste', '<br>Cliente alterado por meio do site pelo usuário William Zimmermann (1) em 22/12/2016 20:11:06.<br>Cliente alterado por meio do site pelo usuário William Zimmermann (1) em 22/12/2016 20:12:54.<br>Cliente alterado por meio do site pelo usuário William Zimmermann (1) em 22/12/2016 20:13:45.<br>Cliente alterado por meio do site pelo usuário William Zimmermann (1) em 22/12/2016 20:14:18.<br>Cliente alterado por meio do site pelo usuário William Zimmermann (1) em 22/12/2016 20:14:33.<br>Cliente alterado por meio do site pelo usuário William Zimmermann (1) em 22/12/2016 20:15:24.<br>Cliente alterado por meio do site pelo usuário William Zimmermann (1) em 22/12/2016 20:16:26.<br>Cliente alterado por meio do site pelo usuário William Zimmermann (1) em 22/12/2016 20:18:38.', '2016-12-22 18:24:23', '2016-12-22 20:18:38', 1),
(12, 1, 1, 1, 'me@williamzimmermann.com.br', '938dceb208db2a75fb64f6743a9c1311', '1993-03-31', 33, 'Teste', 'Cliente adicionado por meio do site pelo usuário William Zimmermann (1) em 22-12-2016 20:24:45.', '2016-12-22 20:24:45', '2016-12-22 20:24:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE IF NOT EXISTS `customer_address` (
  `idAddress` bigint(20) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `name` varchar(45) NOT NULL,
  `street` varchar(60) NOT NULL,
  `house_number` varchar(15) DEFAULT NULL,
  `complement` varchar(60) DEFAULT NULL,
  `neighborhood` varchar(60) DEFAULT NULL,
  `city` varchar(60) NOT NULL,
  `zip_code` varchar(45) NOT NULL,
  `zone` varchar(60) NOT NULL,
  `country_id` smallint(3) NOT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_company`
--

CREATE TABLE IF NOT EXISTS `customer_company` (
  `customer_id` bigint(20) NOT NULL,
  `social_name` varchar(100) NOT NULL,
  `fantasy_name` varchar(100) NOT NULL,
  `document_1` varchar(30) DEFAULT NULL,
  `document_2` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer_company`
--

INSERT INTO `customer_company` (`customer_id`, `social_name`, `fantasy_name`, `document_1`, `document_2`) VALUES
(1, 'TekSul Informática LTDA', 'Teksul Informática', '01.171.298/0001-65', '13412412');

-- --------------------------------------------------------

--
-- Table structure for table `customer_contacts`
--

CREATE TABLE IF NOT EXISTS `customer_contacts` (
  `idContact` bigint(20) NOT NULL,
  `customer_idCustomer` bigint(20) NOT NULL,
  `type` smallint(3) NOT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT '0',
  `desc` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_externalsystem`
--

CREATE TABLE IF NOT EXISTS `customer_externalsystem` (
  `idExternalSystem` bigint(20) NOT NULL,
  `customer_idCustomer` bigint(20) NOT NULL,
  `description` varchar(200) NOT NULL,
  `code` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_person`
--

CREATE TABLE IF NOT EXISTS `customer_person` (
  `customer_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `document_1` varchar(30) DEFAULT NULL,
  `document_2` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer_person`
--

INSERT INTO `customer_person` (`customer_id`, `name`, `last_name`, `document_1`, `document_2`) VALUES
(12, 'William', 'Zimmermann', '024.551.200-40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `idImage` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `extension` varchar(5) NOT NULL,
  `label` varchar(120) DEFAULT NULL,
  `alt` tinytext,
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`idImage`, `website_id`, `name`, `extension`, `label`, `alt`, `creationDate`) VALUES
(5, 30, '1478027832', 'png', 'Site da TekNorte', 'Foto da página inicial da TekNorte', '2016-11-01 19:17:11'),
(6, 30, '1478027833', 'png', 'Foto de um site', 'Foto do site da Procedere', '2016-11-01 19:17:12'),
(7, 30, '1478805245', 'jpg', NULL, NULL, '2016-11-10 19:14:05'),
(8, 30, '1478805247', 'jpg', NULL, NULL, '2016-11-10 19:14:06');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `idLanguage` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `code` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`idLanguage`, `name`, `code`) VALUES
(1, 'Português (Brasil)', 'pt-br'),
(2, 'Inglês', 'en'),
(3, 'Espanhol', 'es'),
(4, 'Francês', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `order_has_product`
--

CREATE TABLE IF NOT EXISTS `order_has_product` (
  `product_order_id` bigint(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `quantity_type` smallint(2) NOT NULL,
  `original_price` double(10,2) NOT NULL,
  `reduction` decimal(10,0) NOT NULL,
  `final_price` double(10,2) NOT NULL,
  `obs` text,
  `obs_seller` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE IF NOT EXISTS `package` (
  `idPackage` int(11) NOT NULL,
  `identification_name` varchar(50) NOT NULL,
  `height` int(5) DEFAULT NULL,
  `width` int(5) DEFAULT NULL,
  `length` int(5) DEFAULT NULL,
  `weight` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `page_has_language`
--

CREATE TABLE IF NOT EXISTS `page_has_language` (
  `language_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `title` varchar(85) NOT NULL,
  `description` varchar(200) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `section_title` varchar(100) DEFAULT NULL,
  `section_description` varchar(250) DEFAULT NULL,
  `content` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `page_has_language`
--

INSERT INTO `page_has_language` (`language_id`, `page_id`, `title`, `description`, `slug`, `section_title`, `section_description`, `content`, `active`, `creationDate`) VALUES
(1, 1, 'Home', 'Página inicial do site', 'home', NULL, NULL, NULL, 0, '2016-11-28 20:14:51'),
(1, 2, 'Quem Somos', 'Página com as descrições sobre Quem Somos', 'quem-somos', 'Quem Somos', 'Saiba quem somos', 'Saiba mais quem somos\r\n', 1, '2016-11-25 16:46:13'),
(4, 1, 'Accueil', 'Accueil', 'accueil', NULL, NULL, NULL, 0, '2016-11-09 19:31:02'),
(4, 2, 'QUI SOMMES-NOUS', 'QUI SOMMES-NOUS ?', 'qui-sommes-nous', 'QUI SOMMES-NOUS ?', 'QUI SOMMES-NOUS ?', NULL, 0, '2016-11-09 19:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `idProduct` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  `creationDate` datetime NOT NULL,
  `title` varchar(85) NOT NULL,
  `reference` varchar(45) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `content` text,
  `price_orginal` double(10,2) DEFAULT NULL,
  `price_actual` double(10,2) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_item`
--

CREATE TABLE IF NOT EXISTS `product_item` (
  `idProductSize` bigint(20) NOT NULL,
  `color_id` int(11) NOT NULL,
  `size` varchar(45) DEFAULT NULL,
  `size_kind` smallint(3) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `product_featurecol` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_order`
--

CREATE TABLE IF NOT EXISTS `product_order` (
  `idOrder` bigint(20) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `customer_address_id` bigint(20) NOT NULL,
  `orderDate` datetime NOT NULL,
  `coments` text,
  `status` tinyint(4) NOT NULL,
  `trackingURL` varchar(250) DEFAULT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_relationships`
--

CREATE TABLE IF NOT EXISTS `product_relationships` (
  `product_idProduct` int(11) NOT NULL,
  `product_idProduct1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `related_articles`
--

CREATE TABLE IF NOT EXISTS `related_articles` (
  `id1_articleLanguage` bigint(20) NOT NULL,
  `id2_articleLanguage` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `idStatus` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `website_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_module`
--

CREATE TABLE IF NOT EXISTS `system_module` (
  `idModule` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `user_type` smallint(1) NOT NULL DEFAULT '2'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `system_module`
--

INSERT INTO `system_module` (`idModule`, `name`, `description`, `active`, `user_type`) VALUES
(1, 'Empresas', 'Módulo para gerenciar as empresas cadastradas no sistema.', 1, 1),
(2, 'Websites', 'Módulo que mostra os websites do sistema e os relaciona com as empresas.', 1, 2),
(3, 'Usuários', 'Módulo para gerenciar usuários e editar suas permissões de acesso.', 1, 2),
(4, 'Banco de Imagens', 'Módulo onde ficam armazenadas quaisquer imagens do sistema. Esse é um módulo obrigatório para qualquer novo website que deseje operar imagens em produtos, notícias, etc.', 1, 2),
(5, 'Páginas', 'Módulo para cadastro de páginas de um website.', 1, 2),
(6, 'Conversor Exportadores', 'Módulo que efetua a conversão de arquivos gerados por exportadores para o padrão do software Domínio Contábil.', 1, 2),
(7, 'Webservices', 'Este módulo permite ao usuário acessar os Webservices de alguns módulos tais como o REST para obter dados por meio do método GET.', 1, 2),
(8, 'Artigos', 'Módulo para publicar artigos/notícias. É possível criar categorias de notícias, notícias e mais.', 1, 2),
(9, 'Clientes', 'Módulo para o gerenciamento de clientes.', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `system_module_has_image`
--

CREATE TABLE IF NOT EXISTS `system_module_has_image` (
  `system_module_idModule` int(11) NOT NULL,
  `image_idImage` int(11) NOT NULL,
  `id_item` bigint(20) NOT NULL,
  `label` varchar(150) DEFAULT NULL,
  `alt` tinytext,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `system_module_has_image`
--

INSERT INTO `system_module_has_image` (`system_module_idModule`, `image_idImage`, `id_item`, `label`, `alt`, `active`) VALUES
(5, 6, 1, 'Foto do site da Procedere', 'Essa é a foto do site da Procedere', 0),
(5, 7, 1, 'Assasins Creed', 'Foto de algo do filme assassins creed', 0),
(8, 5, 1, 'Site da TekNorte', 'Site da TekNorte', 1),
(8, 7, 2, 'Some subtitle', 'some alternative text', 1);

-- --------------------------------------------------------

--
-- Table structure for table `website_article`
--

CREATE TABLE IF NOT EXISTS `website_article` (
  `idArticle` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  `title` varchar(85) NOT NULL,
  `description` varchar(200) NOT NULL,
  `author` varchar(60) NOT NULL,
  `publicationDate` datetime NOT NULL,
  `lastUpdateDate` datetime NOT NULL,
  `socialMedias` tinyint(1) NOT NULL DEFAULT '1',
  `comments` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `website_article`
--

INSERT INTO `website_article` (`idArticle`, `website_id`, `title`, `description`, `author`, `publicationDate`, `lastUpdateDate`, `socialMedias`, `comments`, `active`) VALUES
(1, 30, 'Novo site da TekSul', 'O novo site da TekSul foi lançado', 'William Zimmermann', '2016-12-21 19:30:00', '2016-12-12 20:28:22', 1, 1, 1),
(2, 30, 'Artigo de Teste', 'Artigo de teste', 'William', '2016-12-13 14:05:00', '2016-12-13 16:05:20', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `website_page`
--

CREATE TABLE IF NOT EXISTS `website_page` (
  `idPage` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  `title` varchar(85) NOT NULL,
  `description` varchar(200) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `website_page`
--

INSERT INTO `website_page` (`idPage`, `website_id`, `title`, `description`, `active`) VALUES
(1, 30, 'Home', 'Página inicial', 1),
(2, 30, 'Quem Somos', 'Página com as descrições sobre Quem Somos', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article_category`
--
ALTER TABLE `article_category`
  ADD PRIMARY KEY (`idCategory`,`website_id`),
  ADD KEY `fk_new_category_company_website1_idx` (`website_id`);

--
-- Indexes for table `article_category_has_language`
--
ALTER TABLE `article_category_has_language`
  ADD PRIMARY KEY (`idCategoryLanguage`),
  ADD UNIQUE KEY `idCategoryLangauge_UNIQUE` (`idCategoryLanguage`),
  ADD KEY `fk_new_category_has_language_new_category1_idx` (`category_id`),
  ADD KEY `fk_new_category_has_language_language1_idx` (`language_id`);

--
-- Indexes for table `article_has_category`
--
ALTER TABLE `article_has_category`
  ADD PRIMARY KEY (`language_idArticle`,`language_idCategory`),
  ADD KEY `fk_new_has_language_has_article_category_has_language_artic_idx` (`language_idCategory`),
  ADD KEY `fk_new_has_language_has_article_category_has_language_new_h_idx` (`language_idArticle`);

--
-- Indexes for table `article_has_language`
--
ALTER TABLE `article_has_language`
  ADD PRIMARY KEY (`idArticleLanguage`),
  ADD UNIQUE KEY `idNew_UNIQUE` (`idArticleLanguage`),
  ADD KEY `fk_language_has_website_page_language1_idx` (`language_id`),
  ADD KEY `fk_page_has_language_website_page1_idx` (`article_id`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`idColor`),
  ADD KEY `fk_color_language1_idx` (`language_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`idCompany`),
  ADD UNIQUE KEY `idCompany_UNIQUE` (`idCompany`);

--
-- Indexes for table `company_user`
--
ALTER TABLE `company_user`
  ADD PRIMARY KEY (`idUser`,`company_id`),
  ADD UNIQUE KEY `idUser_UNIQUE` (`idUser`),
  ADD KEY `fk_company_user_company1` (`company_id`);

--
-- Indexes for table `company_user_has_company_website`
--
ALTER TABLE `company_user_has_company_website`
  ADD KEY `fk_company_user_has_company_website_company_website1_idx` (`company_website_idWebsite`),
  ADD KEY `fk_company_user_has_company_website_company_user1_idx` (`company_user_idUser`);

--
-- Indexes for table `company_user_permissions`
--
ALTER TABLE `company_user_permissions`
  ADD PRIMARY KEY (`company_user_idUser`,`website_module_idWebsite`,`website_module_idModule`),
  ADD KEY `fk_company_user_permissions_user1_idx` (`company_user_idUser`),
  ADD KEY `fk_company_user_permissions_system_module_idx` (`website_module_idWebsite`,`website_module_idModule`);

--
-- Indexes for table `company_website`
--
ALTER TABLE `company_website`
  ADD PRIMARY KEY (`idWebsite`,`company_id`),
  ADD UNIQUE KEY `idWebsite_UNIQUE` (`idWebsite`),
  ADD KEY `fk_website_company_idx` (`company_id`);

--
-- Indexes for table `company_website_has_language`
--
ALTER TABLE `company_website_has_language`
  ADD PRIMARY KEY (`company_website_id`,`language_id`),
  ADD KEY `fk_company_website_has_language_language1_idx` (`language_id`),
  ADD KEY `fk_company_website_has_language_company_website1_idx` (`company_website_id`);

--
-- Indexes for table `company_website_has_system_module`
--
ALTER TABLE `company_website_has_system_module`
  ADD PRIMARY KEY (`company_website_idWebsite`,`system_module_idModule`),
  ADD KEY `fk_company_website_has_system_module_system_module1_idx` (`system_module_idModule`),
  ADD KEY `fk_company_website_has_system_module_company_website1_idx` (`company_website_idWebsite`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`countryId`),
  ADD UNIQUE KEY `country_id_UNIQUE` (`countryId`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`idCustomer`),
  ADD KEY `fk_customer_company1_idx` (`company_id`),
  ADD KEY `fk_customer_country1_idx` (`country_id`),
  ADD KEY `fk_customer_company_user1_idx` (`addedBy`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`idAddress`),
  ADD UNIQUE KEY `idAddress_UNIQUE` (`idAddress`),
  ADD KEY `fk_customer_address_country1_idx` (`country_id`),
  ADD KEY `fk_customer_address_customer1_idx` (`customer_id`);

--
-- Indexes for table `customer_company`
--
ALTER TABLE `customer_company`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD PRIMARY KEY (`idContact`),
  ADD UNIQUE KEY `idContact_UNIQUE` (`idContact`),
  ADD KEY `fk_customer_contacts_customer1_idx` (`customer_idCustomer`);

--
-- Indexes for table `customer_externalsystem`
--
ALTER TABLE `customer_externalsystem`
  ADD PRIMARY KEY (`idExternalSystem`),
  ADD UNIQUE KEY `idExternalSystem_UNIQUE` (`idExternalSystem`),
  ADD KEY `fk_customer_externalSystem_customer1_idx` (`customer_idCustomer`);

--
-- Indexes for table `customer_person`
--
ALTER TABLE `customer_person`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`idImage`),
  ADD UNIQUE KEY `idImage_UNIQUE` (`idImage`),
  ADD KEY `fk_image_company_website1_idx` (`website_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`idLanguage`),
  ADD UNIQUE KEY `idLanguage_UNIQUE` (`idLanguage`);

--
-- Indexes for table `order_has_product`
--
ALTER TABLE `order_has_product`
  ADD PRIMARY KEY (`product_order_id`,`product_id`),
  ADD KEY `fk_product_order_has_product_product1_idx` (`product_id`),
  ADD KEY `fk_product_order_has_product_product_order1_idx` (`product_order_id`),
  ADD KEY `fk_order_has_product_status1_idx` (`status_id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`idPackage`),
  ADD UNIQUE KEY `idPackage_UNIQUE` (`idPackage`);

--
-- Indexes for table `page_has_language`
--
ALTER TABLE `page_has_language`
  ADD UNIQUE KEY `idPageLanguage` (`language_id`,`page_id`),
  ADD UNIQUE KEY `id` (`language_id`,`page_id`),
  ADD UNIQUE KEY `idLanguagePage` (`language_id`,`page_id`),
  ADD KEY `fk_language_has_website_page_language1_idx` (`language_id`),
  ADD KEY `fk_page_has_language_website_page1_idx` (`page_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`idProduct`),
  ADD KEY `fk_product_company_website1_idx` (`company_id`,`website_id`),
  ADD KEY `fk_product_language1_idx` (`language_id`);

--
-- Indexes for table `product_item`
--
ALTER TABLE `product_item`
  ADD PRIMARY KEY (`idProductSize`),
  ADD KEY `fk_product_item_color1_idx` (`color_id`);

--
-- Indexes for table `product_order`
--
ALTER TABLE `product_order`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `fk_product_order_customer1_idx` (`customer_id`),
  ADD KEY `fk_product_order_customer_address1_idx` (`customer_address_id`),
  ADD KEY `fk_product_order_status1_idx` (`status_id`);

--
-- Indexes for table `product_relationships`
--
ALTER TABLE `product_relationships`
  ADD PRIMARY KEY (`product_idProduct`,`product_idProduct1`),
  ADD KEY `fk_product_has_product_product2_idx` (`product_idProduct1`),
  ADD KEY `fk_product_has_product_product1_idx` (`product_idProduct`);

--
-- Indexes for table `related_articles`
--
ALTER TABLE `related_articles`
  ADD PRIMARY KEY (`id1_articleLanguage`,`id2_articleLanguage`),
  ADD KEY `fk_new_has_language_has_new_has_language_new_has_language2_idx` (`id2_articleLanguage`),
  ADD KEY `fk_new_has_language_has_new_has_language_new_has_language1_idx` (`id1_articleLanguage`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`idStatus`),
  ADD KEY `fk_status_company_website1_idx` (`website_id`,`company_id`);

--
-- Indexes for table `system_module`
--
ALTER TABLE `system_module`
  ADD PRIMARY KEY (`idModule`);

--
-- Indexes for table `system_module_has_image`
--
ALTER TABLE `system_module_has_image`
  ADD PRIMARY KEY (`system_module_idModule`,`image_idImage`,`id_item`),
  ADD KEY `fk_system_module_has_image_image1_idx` (`image_idImage`),
  ADD KEY `fk_system_module_has_image_system_module1_idx` (`system_module_idModule`);

--
-- Indexes for table `website_article`
--
ALTER TABLE `website_article`
  ADD PRIMARY KEY (`idArticle`),
  ADD UNIQUE KEY `idWebsite_UNIQUE` (`idArticle`),
  ADD KEY `fk_website_page_company_website1_idx` (`website_id`);

--
-- Indexes for table `website_page`
--
ALTER TABLE `website_page`
  ADD PRIMARY KEY (`idPage`),
  ADD UNIQUE KEY `idWebsite_UNIQUE` (`idPage`),
  ADD KEY `fk_website_page_company_website1_idx` (`website_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article_category`
--
ALTER TABLE `article_category`
  MODIFY `idCategory` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `article_category_has_language`
--
ALTER TABLE `article_category_has_language`
  MODIFY `idCategoryLanguage` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `article_has_language`
--
ALTER TABLE `article_has_language`
  MODIFY `idArticleLanguage` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `idCompany` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `company_user`
--
ALTER TABLE `company_user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `company_website`
--
ALTER TABLE `company_website`
  MODIFY `idWebsite` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `idCustomer` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  MODIFY `idContact` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_externalsystem`
--
ALTER TABLE `customer_externalsystem`
  MODIFY `idExternalSystem` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `idImage` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `idLanguage` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `idPackage` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `idStatus` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `system_module`
--
ALTER TABLE `system_module`
  MODIFY `idModule` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `website_article`
--
ALTER TABLE `website_article`
  MODIFY `idArticle` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `website_page`
--
ALTER TABLE `website_page`
  MODIFY `idPage` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `article_category`
--
ALTER TABLE `article_category`
  ADD CONSTRAINT `fk_new_category_company_website1` FOREIGN KEY (`website_id`) REFERENCES `company_website` (`idWebsite`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `article_category_has_language`
--
ALTER TABLE `article_category_has_language`
  ADD CONSTRAINT `fk_new_category_has_language_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`idLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_new_category_has_language_new_category1` FOREIGN KEY (`category_id`) REFERENCES `article_category` (`idCategory`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `article_has_category`
--
ALTER TABLE `article_has_category`
  ADD CONSTRAINT `fk_new_has_language_has_article_category_has_language_article1` FOREIGN KEY (`language_idCategory`) REFERENCES `article_category_has_language` (`idCategoryLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_new_has_language_has_article_category_has_language_new_has1` FOREIGN KEY (`language_idArticle`) REFERENCES `article_has_language` (`idArticleLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `article_has_language`
--
ALTER TABLE `article_has_language`
  ADD CONSTRAINT `fk_language_has_website_page_language10` FOREIGN KEY (`language_id`) REFERENCES `language` (`idLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_page_has_language_website_page10` FOREIGN KEY (`article_id`) REFERENCES `website_article` (`idArticle`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `color`
--
ALTER TABLE `color`
  ADD CONSTRAINT `fk_color_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`idLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company_user`
--
ALTER TABLE `company_user`
  ADD CONSTRAINT `fk_company_user_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`idCompany`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company_user_has_company_website`
--
ALTER TABLE `company_user_has_company_website`
  ADD CONSTRAINT `fk_company_user_has_company_website_company_user1` FOREIGN KEY (`company_user_idUser`) REFERENCES `company_user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_company_user_has_company_website_company_website1` FOREIGN KEY (`company_website_idWebsite`) REFERENCES `company_website` (`idWebsite`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company_user_permissions`
--
ALTER TABLE `company_user_permissions`
  ADD CONSTRAINT `fk_company_user_permissions_company_user` FOREIGN KEY (`company_user_idUser`) REFERENCES `company_user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_company_user_permissions_system_module1` FOREIGN KEY (`website_module_idWebsite`, `website_module_idModule`) REFERENCES `company_website_has_system_module` (`company_website_idWebsite`, `system_module_idModule`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company_website`
--
ALTER TABLE `company_website`
  ADD CONSTRAINT `fk_website_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`idCompany`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company_website_has_language`
--
ALTER TABLE `company_website_has_language`
  ADD CONSTRAINT `fk_company_website_has_language_company_website1` FOREIGN KEY (`company_website_id`) REFERENCES `company_website` (`idWebsite`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_company_website_has_language_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`idLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company_website_has_system_module`
--
ALTER TABLE `company_website_has_system_module`
  ADD CONSTRAINT `fk_company_website_has_system_module_company_website1` FOREIGN KEY (`company_website_idWebsite`) REFERENCES `company_website` (`idWebsite`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_company_website_has_system_module_system_module1` FOREIGN KEY (`system_module_idModule`) REFERENCES `system_module` (`idModule`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`idCompany`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_customer_company_user1` FOREIGN KEY (`addedBy`) REFERENCES `company_user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_customer_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`countryId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD CONSTRAINT `fk_customer_address_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`countryId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_customer_address_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`idCustomer`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customer_company`
--
ALTER TABLE `customer_company`
  ADD CONSTRAINT `fk_customer_company_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`idCustomer`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD CONSTRAINT `fk_customer_contacts_customer1` FOREIGN KEY (`customer_idCustomer`) REFERENCES `customer` (`idCustomer`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customer_externalsystem`
--
ALTER TABLE `customer_externalsystem`
  ADD CONSTRAINT `fk_customer_externalSystem_customer1` FOREIGN KEY (`customer_idCustomer`) REFERENCES `customer` (`idCustomer`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customer_person`
--
ALTER TABLE `customer_person`
  ADD CONSTRAINT `fk_customer_person_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`idCustomer`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_company_website1` FOREIGN KEY (`website_id`) REFERENCES `company_website` (`idWebsite`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_has_product`
--
ALTER TABLE `order_has_product`
  ADD CONSTRAINT `fk_order_has_product_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`idStatus`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_order_has_product_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`idProduct`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_order_has_product_product_order1` FOREIGN KEY (`product_order_id`) REFERENCES `product_order` (`idOrder`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `page_has_language`
--
ALTER TABLE `page_has_language`
  ADD CONSTRAINT `fk_language_has_website_page_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`idLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_page_has_language_website_page1` FOREIGN KEY (`page_id`) REFERENCES `website_page` (`idPage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_company_website1` FOREIGN KEY (`company_id`, `website_id`) REFERENCES `company_website` (`idWebsite`, `company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`idLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_item`
--
ALTER TABLE `product_item`
  ADD CONSTRAINT `fk_product_item_color1` FOREIGN KEY (`color_id`) REFERENCES `color` (`idColor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_order`
--
ALTER TABLE `product_order`
  ADD CONSTRAINT `fk_product_order_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`idCustomer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_order_customer_address1` FOREIGN KEY (`customer_address_id`) REFERENCES `customer_address` (`idAddress`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_order_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`idStatus`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_relationships`
--
ALTER TABLE `product_relationships`
  ADD CONSTRAINT `fk_product_has_product_product1` FOREIGN KEY (`product_idProduct`) REFERENCES `product` (`idProduct`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_has_product_product2` FOREIGN KEY (`product_idProduct1`) REFERENCES `product` (`idProduct`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `related_articles`
--
ALTER TABLE `related_articles`
  ADD CONSTRAINT `fk_new_has_language_has_new_has_language_new_has_language1` FOREIGN KEY (`id1_articleLanguage`) REFERENCES `article_has_language` (`idArticleLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_new_has_language_has_new_has_language_new_has_language2` FOREIGN KEY (`id2_articleLanguage`) REFERENCES `article_has_language` (`idArticleLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `status`
--
ALTER TABLE `status`
  ADD CONSTRAINT `fk_status_company_website1` FOREIGN KEY (`website_id`, `company_id`) REFERENCES `company_website` (`idWebsite`, `company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `system_module_has_image`
--
ALTER TABLE `system_module_has_image`
  ADD CONSTRAINT `fk_system_module_has_image_image1` FOREIGN KEY (`image_idImage`) REFERENCES `image` (`idImage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_system_module_has_image_system_module1` FOREIGN KEY (`system_module_idModule`) REFERENCES `system_module` (`idModule`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `website_article`
--
ALTER TABLE `website_article`
  ADD CONSTRAINT `fk_website_page_company_website10` FOREIGN KEY (`website_id`) REFERENCES `company_website` (`idWebsite`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `website_page`
--
ALTER TABLE `website_page`
  ADD CONSTRAINT `fk_website_page_company_website1` FOREIGN KEY (`website_id`) REFERENCES `company_website` (`idWebsite`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
