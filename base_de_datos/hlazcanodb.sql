-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: hlazcano_db
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alertas_stock`
--

DROP TABLE IF EXISTS `alertas_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alertas_stock` (
  `id_alerta` int NOT NULL AUTO_INCREMENT,
  `id_producto` int NOT NULL,
  `id_tienda` int NOT NULL,
  `tipo_alerta` varchar(150) NOT NULL,
  `atendida` tinyint NOT NULL DEFAULT '0',
  `accion_tomada` varchar(150) DEFAULT NULL,
  `id_usuario_aten` int DEFAULT NULL,
  `notas_atencion` varchar(150) DEFAULT NULL,
  `fecha_alerta` date NOT NULL,
  `fecha_atencion` date DEFAULT NULL,
  PRIMARY KEY (`id_alerta`),
  KEY `fk_alertas_producto` (`id_producto`),
  KEY `fk_alertas_tienda` (`id_tienda`),
  KEY `fk_alertas_usuario` (`id_usuario_aten`),
  CONSTRAINT `fk_alertas_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  CONSTRAINT `fk_alertas_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  CONSTRAINT `fk_alertas_usuario` FOREIGN KEY (`id_usuario_aten`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alertas_stock`
--

LOCK TABLES `alertas_stock` WRITE;
/*!40000 ALTER TABLE `alertas_stock` DISABLE KEYS */;
INSERT INTO `alertas_stock` VALUES (1,4,1,'stock_bajo',0,NULL,NULL,NULL,'2026-04-21',NULL),(2,3,1,'stock_bajo',0,NULL,NULL,NULL,'2026-04-21',NULL),(3,7,1,'stock_bajo',0,NULL,NULL,NULL,'2026-04-21',NULL),(4,8,1,'sin_stock',0,NULL,NULL,NULL,'2026-04-21',NULL);
/*!40000 ALTER TABLE `alertas_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anaqueles`
--

DROP TABLE IF EXISTS `anaqueles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anaqueles` (
  `id_anaquel` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `id_tienda` int NOT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_anaquel`),
  KEY `fk_anaqueles_tienda` (`id_tienda`),
  CONSTRAINT `fk_anaqueles_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anaqueles`
--

LOCK TABLES `anaqueles` WRITE;
/*!40000 ALTER TABLE `anaqueles` DISABLE KEYS */;
INSERT INTO `anaqueles` VALUES (1,'A-1','Anaquel A fila 1 ? Hilos naturales',1,1),(2,'A-2','Anaquel A fila 2 ? Hilos naturales',1,1),(3,'B-1','Anaquel B fila 1 ? Hilos sintéticos',1,1),(4,'B-2','Anaquel B fila 2 ? Hilos sintéticos',1,1),(5,'B-3','Anaquel B fila 3 ? Hilos especiales',1,1),(6,'C-1','Anaquel C fila 1 ? Poliéster/mezclas',1,1),(7,'C-2','Anaquel C fila 2 ? Poliéster/mezclas',1,1);
/*!40000 ALTER TABLE `anaqueles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_productos`
--

DROP TABLE IF EXISTS `catalogo_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_productos` (
  `id_catalogo` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(160) NOT NULL,
  `id_tipo` int NOT NULL,
  `id_color` int NOT NULL,
  `id_unidad` int NOT NULL,
  `presentacion` varchar(40) DEFAULT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado_en` date NOT NULL,
  PRIMARY KEY (`id_catalogo`),
  KEY `fk_catalogo_tipo` (`id_tipo`),
  KEY `fk_catalogo_color` (`id_color`),
  KEY `fk_catalogo_unidad` (`id_unidad`),
  CONSTRAINT `fk_catalogo_color` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id_color`),
  CONSTRAINT `fk_catalogo_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_hilo` (`id_tipo`),
  CONSTRAINT `fk_catalogo_unidad` FOREIGN KEY (`id_unidad`) REFERENCES `unidades` (`id_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_productos`
--

LOCK TABLES `catalogo_productos` WRITE;
/*!40000 ALTER TABLE `catalogo_productos` DISABLE KEYS */;
INSERT INTO `catalogo_productos` VALUES (1,'Hilo Acrílico Blanco 500g',1,1,1,'500g','Hilo acrílico blanco en presentación 500g',1,'2026-04-21'),(2,'Hilo Acrílico Negro 500g',1,2,1,'500g','Hilo acrílico negro en presentación 500g',1,'2026-04-21'),(3,'Hilo Algodón Rojo 250g',3,3,1,'250g','Hilo algodón rojo en presentación 250g',1,'2026-04-21'),(4,'Hilo Nylon Negro 1kg',2,2,1,'1kg','Hilo nylon negro en presentación 1kg',1,'2026-04-21'),(5,'Hilo Poliéster Azul 500g',4,4,1,'500g','Hilo poliéster azul en presentación 500g',1,'2026-04-21'),(6,'Hilo Lana Verde 100g',5,5,2,'100g','Hilo lana verde en presentación 100g',1,'2026-04-21'),(7,'Hilo Mercerizado Rosa 100g',6,7,2,'100g','Hilo mercerizado rosa en presentación 100g',1,'2026-04-21'),(8,'Hilo Elastano Café 50g',7,8,2,'50g','Hilo elastano café en presentación 50g',1,'2026-04-21'),(9,'Hilo Seda Lavanda 50g',8,9,2,'50g','Hilo seda lavanda en presentación 50g',1,'2026-04-21');
/*!40000 ALTER TABLE `catalogo_productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `tipo_cliente` varchar(150) DEFAULT NULL,
  `id_tienda` int NOT NULL,
  `notas` varchar(150) DEFAULT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado_en` date NOT NULL,
  `actualizado_en` date DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  KEY `fk_clientes_tienda` (`id_tienda`),
  CONSTRAINT `fk_clientes_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'M. González','222-111-0001',NULL,NULL,NULL,'Comprador individual',1,NULL,1,'2026-04-21',NULL),(2,'J. Pérez','222-111-0002',NULL,NULL,NULL,'Comprador individual',1,NULL,1,'2026-04-21',NULL),(3,'Artesanías del Valle','222-333-0010','contacto@artval.mx',NULL,NULL,'Taller / Negocio',1,NULL,1,'2026-04-21',NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colores`
--

DROP TABLE IF EXISTS `colores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colores` (
  `id_color` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `codigo_hex` char(7) DEFAULT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado_en` date NOT NULL,
  PRIMARY KEY (`id_color`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colores`
--

LOCK TABLES `colores` WRITE;
/*!40000 ALTER TABLE `colores` DISABLE KEYS */;
INSERT INTO `colores` VALUES (1,'Blanco','#FFFFFF',1,'2026-04-21'),(2,'Negro','#111111',1,'2026-04-21'),(3,'Rojo','#C0392B',1,'2026-04-21'),(4,'Azul','#1976D2',1,'2026-04-21'),(5,'Verde','#388E3C',1,'2026-04-21'),(6,'Amarillo','#F9A825',1,'2026-04-21'),(7,'Rosa','#E91E8C',1,'2026-04-21'),(8,'Café','#795548',1,'2026-04-21'),(9,'Lavanda','#9C27B0',1,'2026-04-21'),(10,'Gris','#9E9E9E',1,'2026-04-21');
/*!40000 ALTER TABLE `colores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras`
--

DROP TABLE IF EXISTS `compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras` (
  `id_compra` int NOT NULL AUTO_INCREMENT,
  `folio` varchar(30) DEFAULT NULL,
  `id_proveedor` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_tienda` int NOT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `notas` varchar(150) DEFAULT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_compra`),
  KEY `fk_compras_proveedor` (`id_proveedor`),
  KEY `fk_compras_usuario` (`id_usuario`),
  KEY `fk_compras_tienda` (`id_tienda`),
  CONSTRAINT `fk_compras_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`),
  CONSTRAINT `fk_compras_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  CONSTRAINT `fk_compras_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras`
--

LOCK TABLES `compras` WRITE;
/*!40000 ALTER TABLE `compras` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_compra`
--

DROP TABLE IF EXISTS `detalle_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_compra` (
  `id_detalle` int NOT NULL AUTO_INCREMENT,
  `id_compra` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unit` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `fk_dcompra_compra` (`id_compra`),
  KEY `fk_dcompra_producto` (`id_producto`),
  CONSTRAINT `fk_dcompra_compra` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`),
  CONSTRAINT `fk_dcompra_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_compra`
--

LOCK TABLES `detalle_compra` WRITE;
/*!40000 ALTER TABLE `detalle_compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_venta` (
  `id_detalle` int NOT NULL AUTO_INCREMENT,
  `id_venta` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unit` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `fk_dventa_venta` (`id_venta`),
  KEY `fk_dventa_producto` (`id_producto`),
  CONSTRAINT `fk_dventa_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  CONSTRAINT `fk_dventa_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gastos`
--

DROP TABLE IF EXISTS `gastos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gastos` (
  `id_gastos` int NOT NULL AUTO_INCREMENT,
  `concepto` varchar(150) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `id_tienda` int NOT NULL,
  `id_usuario` int NOT NULL,
  `notas` varchar(150) DEFAULT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_gastos`),
  KEY `fk_gastos_tienda` (`id_tienda`),
  KEY `fk_gastos_usuario` (`id_usuario`),
  CONSTRAINT `fk_gastos_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  CONSTRAINT `fk_gastos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gastos`
--

LOCK TABLES `gastos` WRITE;
/*!40000 ALTER TABLE `gastos` DISABLE KEYS */;
/*!40000 ALTER TABLE `gastos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_inventario`
--

DROP TABLE IF EXISTS `movimientos_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos_inventario` (
  `id_movimiento` int NOT NULL AUTO_INCREMENT,
  `id_producto` int NOT NULL,
  `tipo_movimiento` varchar(150) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `stock_anterior` decimal(10,2) NOT NULL,
  `stock_nuevo` decimal(10,2) NOT NULL,
  `referencia_id` int DEFAULT NULL,
  `referencia_tipo` varchar(150) DEFAULT NULL,
  `id_usuario` int NOT NULL,
  `notas` varchar(150) DEFAULT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_movimiento`),
  KEY `fk_movimientos_producto` (`id_producto`),
  KEY `fk_movimientos_usuario` (`id_usuario`),
  CONSTRAINT `fk_movimientos_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  CONSTRAINT `fk_movimientos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_inventario`
--

LOCK TABLES `movimientos_inventario` WRITE;
/*!40000 ALTER TABLE `movimientos_inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id_notif` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `tipo` varchar(150) DEFAULT NULL,
  `mensaje` varchar(250) NOT NULL,
  `referencia_id` int DEFAULT NULL,
  `referencia_tipo` varchar(30) DEFAULT NULL,
  `leida` tinyint NOT NULL DEFAULT '0',
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_notif`),
  KEY `fk_notif_usuario` (`id_usuario`),
  CONSTRAINT `fk_notif_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `id_catalogo` int NOT NULL,
  `id_tipo` int NOT NULL,
  `id_color` int NOT NULL,
  `id_unidad` int NOT NULL,
  `id_proveedor` int DEFAULT NULL,
  `id_anaquel` int DEFAULT NULL,
  `id_tienda` int NOT NULL,
  `presentacion` varchar(40) DEFAULT NULL,
  `posicion_anaquel` varchar(50) DEFAULT NULL,
  `stock_actual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado_en` date NOT NULL,
  `actualizado_en` date DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `fk_productos_catalogo` (`id_catalogo`),
  KEY `fk_productos_tipo` (`id_tipo`),
  KEY `fk_productos_color` (`id_color`),
  KEY `fk_productos_unidad` (`id_unidad`),
  KEY `fk_productos_proveedor` (`id_proveedor`),
  KEY `fk_productos_anaquel` (`id_anaquel`),
  KEY `fk_productos_tienda` (`id_tienda`),
  CONSTRAINT `fk_productos_anaquel` FOREIGN KEY (`id_anaquel`) REFERENCES `anaqueles` (`id_anaquel`),
  CONSTRAINT `fk_productos_catalogo` FOREIGN KEY (`id_catalogo`) REFERENCES `catalogo_productos` (`id_catalogo`),
  CONSTRAINT `fk_productos_color` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id_color`),
  CONSTRAINT `fk_productos_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`),
  CONSTRAINT `fk_productos_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  CONSTRAINT `fk_productos_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_hilo` (`id_tipo`),
  CONSTRAINT `fk_productos_unidad` FOREIGN KEY (`id_unidad`) REFERENCES `unidades` (`id_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,1,1,1,1,1,1,1,'500g','Fila 1, Col 1',12.00,5.00,40.00,65.00,1,'2026-04-21',NULL),(2,2,1,2,1,1,4,1,'500g','Fila 1, Col 2',8.00,5.00,40.00,65.00,1,'2026-04-21',NULL),(3,3,3,3,1,3,1,1,'250g','Fila 2, Col 1',4.00,5.00,50.00,78.00,1,'2026-04-21',NULL),(4,4,2,2,1,1,4,1,'1kg','Fila 1, Col 3',2.00,5.00,70.00,110.00,1,'2026-04-21',NULL),(5,5,4,4,1,3,6,1,'500g','Fila 1, Col 1',18.00,5.00,35.00,55.00,1,'2026-04-21',NULL),(6,6,5,5,2,2,1,1,'100g','Fila 2, Col 2',9.00,4.00,85.00,130.00,1,'2026-04-21',NULL),(7,7,6,7,2,2,1,1,'100g','Fila 2, Col 3',1.00,5.00,60.00,90.00,1,'2026-04-21',NULL),(8,8,7,8,2,1,5,1,'50g','Fila 3, Col 1',0.00,3.00,45.00,70.00,1,'2026-04-21',NULL),(9,9,8,9,2,2,1,1,'50g','Fila 2, Col 4',6.00,3.00,95.00,145.00,1,'2026-04-21',NULL);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id_proveedor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `notas` varchar(150) DEFAULT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado_en` date NOT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'Textiles del Norte S.A.','222-100-2000','ventas@textilesnorte.mx','Blvd. Industrial 45, Puebla','Proveedor principal hilos sintéticos',1,'2026-04-21'),(2,'Hilos Premium MX','222-300-4000','contacto@hilospremium.mx','Calle Textil 88, Tlaxcala','Especialistas en lana y seda',1,'2026-04-21'),(3,'Distribuidora Central','800-555-1234','pedidos@distcentral.com.mx','Av. Reforma 200, CDMX','Distribución a nivel nacional',1,'2026-04-21');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sesiones`
--

DROP TABLE IF EXISTS `sesiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesiones` (
  `id_sesion` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `ip` varchar(45) NOT NULL,
  `dispositivo` varchar(200) DEFAULT NULL,
  `inicio` date NOT NULL,
  `fin` date DEFAULT NULL,
  PRIMARY KEY (`id_sesion`),
  KEY `fk_sesiones_usuario` (`id_usuario`),
  CONSTRAINT `fk_sesiones_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesiones`
--

LOCK TABLES `sesiones` WRITE;
/*!40000 ALTER TABLE `sesiones` DISABLE KEYS */;
/*!40000 ALTER TABLE `sesiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id_tickets` int NOT NULL AUTO_INCREMENT,
  `id_venta` int NOT NULL,
  `contenido` varchar(250) DEFAULT NULL,
  `canal_envio` varchar(150) DEFAULT NULL,
  `enviado` tinyint NOT NULL DEFAULT '0',
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_tickets`),
  KEY `fk_tickets_venta` (`id_venta`),
  CONSTRAINT `fk_tickets_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiendas`
--

DROP TABLE IF EXISTS `tiendas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tiendas` (
  `id_tienda` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado_en` date NOT NULL,
  PRIMARY KEY (`id_tienda`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tiendas`
--

LOCK TABLES `tiendas` WRITE;
/*!40000 ALTER TABLE `tiendas` DISABLE KEYS */;
INSERT INTO `tiendas` VALUES (1,'Sucursal 1 ? Santa María Texmelucan','Av. Principal 100, Santa María Texmelucan','222-555-0001',1,'2026-04-21'),(2,'Sucursal 2 ? Por definir','Por definir',NULL,1,'2026-04-21');
/*!40000 ALTER TABLE `tiendas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_hilo`
--

DROP TABLE IF EXISTS `tipos_hilo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_hilo` (
  `id_tipo` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado_en` date NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_hilo`
--

LOCK TABLES `tipos_hilo` WRITE;
/*!40000 ALTER TABLE `tipos_hilo` DISABLE KEYS */;
INSERT INTO `tipos_hilo` VALUES (1,'Acrílico','Fibra sintética, lavable y resistente',1,'2026-04-21'),(2,'Nylon','Alta resistencia y elasticidad',1,'2026-04-21'),(3,'Algodón','Fibra natural, hipoalergénica',1,'2026-04-21'),(4,'Poliéster','Resistente a la humedad y desgaste',1,'2026-04-21'),(5,'Lana','Fibra animal, aislante térmica',1,'2026-04-21'),(6,'Mercerizado','Algodón tratado con brillo sedoso',1,'2026-04-21'),(7,'Elastano','Alta elasticidad, mezcla sintética',1,'2026-04-21'),(8,'Seda','Fibra natural de alta calidad y brillo',1,'2026-04-21');
/*!40000 ALTER TABLE `tipos_hilo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidades`
--

DROP TABLE IF EXISTS `unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidades` (
  `id_unidad` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  PRIMARY KEY (`id_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidades`
--

LOCK TABLES `unidades` WRITE;
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;
INSERT INTO `unidades` VALUES (1,'kg','Kilogramo'),(2,'g','Gramo'),(3,'pieza','Pieza'),(4,'rollo','Rollo'),(5,'cono','Cono');
/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(20) NOT NULL,
  `rol` varchar(100) NOT NULL,
  `id_tienda` int NOT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `ultimo_acceso` date DEFAULT NULL,
  `ip_ultimo` varchar(45) DEFAULT NULL,
  `creado_en` date NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`),
  KEY `fk_usuarios_tienda` (`id_tienda`),
  CONSTRAINT `fk_usuarios_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Hernán Meneses','superadmin@hlazcano.com','12345678','superadmin',1,1,NULL,NULL,'2026-04-21'),(2,'Gerente S1','admin1@hlazcano.com','12345678','admin',1,1,NULL,NULL,'2026-04-21'),(3,'Gerente S2','admin2@hlazcano.com','12345678','admin',2,1,NULL,NULL,'2026-04-21'),(4,'Ana Ramírez','emp1@hlazcano.com','12345678','empleado',1,1,NULL,NULL,'2026-04-21'),(5,'Carlos Mendoza','emp2@hlazcano.com','12345678','empleado',2,1,NULL,NULL,'2026-04-21');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `folio` varchar(20) NOT NULL,
  `id_usuario` int NOT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_tienda` int NOT NULL,
  `descuento` decimal(5,2) DEFAULT '0.00',
  `subtotal` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `notas` varchar(150) DEFAULT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `fk_ventas_usuario` (`id_usuario`),
  KEY `fk_ventas_cliente` (`id_cliente`),
  KEY `fk_ventas_tienda` (`id_tienda`),
  CONSTRAINT `fk_ventas_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `fk_ventas_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  CONSTRAINT `fk_ventas_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'hlazcano_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-04 14:08:34
