-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: db_taxi
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `det_viaje`
--

DROP TABLE IF EXISTS `det_viaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `det_viaje` (
  `id_det_viaje` int NOT NULL AUTO_INCREMENT,
  `viaje_id` int DEFAULT NULL,
  `origen_id` varchar(500) DEFAULT NULL,
  `destino_id` varchar(500) DEFAULT NULL,
  `factura_id` int DEFAULT '0',
  `vehiculo` varchar(150) DEFAULT NULL,
  `no_maletas` int DEFAULT NULL,
  `no_pasajeros` int DEFAULT NULL,
  `nombre` varchar(500) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `tipo_pago` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_det_viaje`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_menu_usuario`
--

DROP TABLE IF EXISTS `rel_menu_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_menu_usuario` (
  `id_menu_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `menu_id` int DEFAULT NULL,
  `dtCreacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_menu_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_usuario_caja`
--

DROP TABLE IF EXISTS `rel_usuario_caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_usuario_caja` (
  `id_usuario_caja` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `caja_id` int NOT NULL,
  PRIMARY KEY (`id_usuario_caja`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_vehiculo_operador`
--

DROP TABLE IF EXISTS `rel_vehiculo_operador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_vehiculo_operador` (
  `id_vehiculo_operador` int NOT NULL AUTO_INCREMENT,
  `operador_id` int DEFAULT NULL,
  `vehiculo_id` int DEFAULT NULL,
  `dtCreacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_vehiculo_operador`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_viaje_vehiculo_operador`
--

DROP TABLE IF EXISTS `rel_viaje_vehiculo_operador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_viaje_vehiculo_operador` (
  `id_viaje_vehiculo_operador` int NOT NULL AUTO_INCREMENT,
  `viaje_id` int DEFAULT NULL,
  `vehiculo_operador_id` int DEFAULT NULL,
  `dtCreacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_viaje_vehiculo_operador`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_destinos`
--

DROP TABLE IF EXISTS `tbl_destinos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_destinos` (
  `id_destino` int NOT NULL AUTO_INCREMENT,
  `direccion_id` int DEFAULT NULL,
  `destino` varchar(500) DEFAULT NULL,
  `precio` varchar(10) DEFAULT NULL,
  `distancia` varchar(10) DEFAULT NULL,
  `duracion` varchar(5) DEFAULT NULL,
  `date_creacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_destino`)
) ENGINE=InnoDB AUTO_INCREMENT=507 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_direcciones`
--

DROP TABLE IF EXISTS `tbl_direcciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_direcciones` (
  `id_direccion` int NOT NULL AUTO_INCREMENT,
  `calle` varchar(150) DEFAULT NULL,
  `num_int` varchar(150) DEFAULT NULL,
  `num_ext` varchar(150) DEFAULT NULL,
  `cp` int DEFAULT NULL,
  `colonia` varchar(350) DEFAULT NULL,
  `ciudad` varchar(350) DEFAULT NULL,
  PRIMARY KEY (`id_direccion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_direcciones_webhook`
--

DROP TABLE IF EXISTS `tbl_direcciones_webhook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_direcciones_webhook` (
  `id_direccion` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `nombre` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `direccion` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `duracion` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `distancia` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `precio` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_direccion`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_empresas`
--

DROP TABLE IF EXISTS `tbl_empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_empresas` (
  `id_empresa` int NOT NULL AUTO_INCREMENT,
  `empresa` varchar(150) DEFAULT NULL,
  `logo_path` varchar(350) DEFAULT NULL,
  `webhook` int DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_facturas`
--

DROP TABLE IF EXISTS `tbl_facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_facturas` (
  `id_factura` int NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(400) DEFAULT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `calle` varchar(400) DEFAULT NULL,
  `no_calle` varchar(200) DEFAULT NULL,
  `ciudad` varchar(400) DEFAULT NULL,
  `estado` varchar(500) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `pais` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id_factura`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_fotografias`
--

DROP TABLE IF EXISTS `tbl_fotografias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_fotografias` (
  `id_fotografia` int NOT NULL AUTO_INCREMENT,
  `namespace` varchar(150) DEFAULT NULL,
  `path` varchar(350) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `dtCreacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_fotografia`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_menu`
--

DROP TABLE IF EXISTS `tbl_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_menu` (
  `id_menu` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `ruta` varchar(150) DEFAULT NULL,
  `dtCreacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_operadores`
--

DROP TABLE IF EXISTS `tbl_operadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_operadores` (
  `id_operador` int NOT NULL AUTO_INCREMENT,
  `fotografia_id` int DEFAULT NULL,
  `nombres` varchar(150) DEFAULT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `no_licencia` varchar(50) DEFAULT NULL,
  `dtVigencia` date DEFAULT NULL,
  `curp` varchar(18) DEFAULT NULL,
  `edad` varchar(2) DEFAULT NULL,
  `dtNacimiento` date DEFAULT NULL,
  `dtIngreso` date DEFAULT NULL,
  `dtBaja` date DEFAULT NULL,
  `status` int DEFAULT NULL,
  `direccion` varchar(350) DEFAULT NULL,
  `dtCreacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_operador`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_origenes`
--

DROP TABLE IF EXISTS `tbl_origenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_origenes` (
  `id_origen` int NOT NULL AUTO_INCREMENT,
  `origen` varchar(500) DEFAULT NULL,
  `direccion_id` int DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_origen`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_perfiles`
--

DROP TABLE IF EXISTS `tbl_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_perfiles` (
  `id_perfil` int NOT NULL AUTO_INCREMENT,
  `perfil` varchar(250) DEFAULT NULL,
  `lectura` int DEFAULT NULL,
  `escritura` int DEFAULT NULL,
  `edicion` int DEFAULT NULL,
  `depuracion` int DEFAULT NULL,
  `dtCreacion` date DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_turnos`
--

DROP TABLE IF EXISTS `tbl_turnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_turnos` (
  `id_turno` int NOT NULL AUTO_INCREMENT,
  `vehiculo_operador_id` int DEFAULT NULL,
  `empresa_id` int DEFAULT NULL,
  `dtCreacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_turno`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_turnos_caja`
--

DROP TABLE IF EXISTS `tbl_turnos_caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_turnos_caja` (
  `id_det_caja` int NOT NULL AUTO_INCREMENT,
  `caja_id` int NOT NULL,
  `b_status` int DEFAULT NULL,
  `dt_inicio_operacion` datetime DEFAULT NULL,
  `dt_fin_operacion` datetime DEFAULT NULL,
  `total_venta` float DEFAULT NULL,
  `dt_create` date DEFAULT NULL,
  PRIMARY KEY (`id_det_caja`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_usuarios`
--

DROP TABLE IF EXISTS `tbl_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `perfil_id` int DEFAULT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `usuario` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `empresa_id` int DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_vehiculos`
--

DROP TABLE IF EXISTS `tbl_vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_vehiculos` (
  `id_vehiculo` int NOT NULL AUTO_INCREMENT,
  `fotografia_id` int DEFAULT NULL,
  `vehiculo` varchar(500) DEFAULT NULL,
  `marca` varchar(150) DEFAULT NULL,
  `modelo` varchar(150) DEFAULT NULL,
  `placa` varchar(150) DEFAULT NULL,
  `no_serie` varchar(100) DEFAULT NULL,
  `aseguradora` varchar(100) DEFAULT NULL,
  `poliza` varchar(100) DEFAULT NULL,
  `dtFinPoliza` date DEFAULT NULL,
  `notas` varchar(500) DEFAULT NULL,
  `dtCreacion` datetime DEFAULT NULL,
  `activo` int DEFAULT NULL,
  PRIMARY KEY (`id_vehiculo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_viajes`
--

DROP TABLE IF EXISTS `tbl_viajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_viajes` (
  `id_viaje` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `caja_id` int NOT NULL DEFAULT '0',
  `folio` varchar(20) DEFAULT NULL,
  `nombre_viaje` varchar(300) DEFAULT NULL,
  `status` varchar(500) DEFAULT NULL,
  `tipo_servicio` varchar(150) DEFAULT NULL,
  `tipo_viaje` varchar(150) DEFAULT NULL,
  `comentarios` varchar(500) DEFAULT NULL,
  `date_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_viaje`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-17 14:16:52
