/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Area` (
  `idArea` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreArea` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idArea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AreaCaldera` (
  `idAreaCaldera` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreAreaCaldera` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idAreaCaldera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AreaPretratamiento` (
  `idAreaPretratamiento` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreAreaPretratamiento` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idAreaPretratamiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Caldera` (
  `idCaldera` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreCaldera` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idCaldera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Estado` (
  `idEstado` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreEstado` varchar(12) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idEstado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inventario` (
  `idInventario` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) NOT NULL,
  `idListadoActividadCaldera` int(11) NOT NULL,
  `CantidadExistencia` decimal(6,2) NOT NULL,
  `ProductoFlotante` decimal(6,2) DEFAULT NULL,
  `RegistradoPor` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idInventario`),
  KEY `idProducto` (`idProducto`),
  KEY `RegistradoPor` (`RegistradoPor`),
  CONSTRAINT `Inventario_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `Producto` (`idProducto`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `Inventario_ibfk_2` FOREIGN KEY (`RegistradoPor`) REFERENCES `users` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ListadoActividadCaldera` (
  `idListadoActividadCaldera` int(11) NOT NULL AUTO_INCREMENT,
  `idArea` tinyint(4) NOT NULL,
  `idCaldera` tinyint(4) NOT NULL,
  `idAreaCaldera` tinyint(4) NOT NULL,
  `idNombreActividad` tinyint(4) NOT NULL,
  `FechaCreacionActividad` datetime NOT NULL,
  `FechaConclusionActividad` datetime NOT NULL,
  `EstadoActividad` tinyint(4) NOT NULL,
  `CreadoPor` tinyint(4) NOT NULL,
  `RealizadoPor` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idListadoActividadCaldera`),
  KEY `idArea` (`idArea`),
  KEY `idCaldera` (`idCaldera`),
  KEY `idAreaCaldera` (`idAreaCaldera`),
  KEY `idNombreActividad` (`idNombreActividad`),
  KEY `EstadoActividad` (`EstadoActividad`),
  KEY `CreadoPor` (`CreadoPor`),
  KEY `RealizadoPor` (`RealizadoPor`),
  CONSTRAINT `ListadoActividadCaldera_ibfk_1` FOREIGN KEY (`idArea`) REFERENCES `Area` (`idArea`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadCaldera_ibfk_2` FOREIGN KEY (`idCaldera`) REFERENCES `Caldera` (`idCaldera`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadCaldera_ibfk_3` FOREIGN KEY (`idAreaCaldera`) REFERENCES `AreaCaldera` (`idAreaCaldera`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadCaldera_ibfk_4` FOREIGN KEY (`idNombreActividad`) REFERENCES `NombreActividad` (`idNombreActividad`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadCaldera_ibfk_5` FOREIGN KEY (`EstadoActividad`) REFERENCES `Estado` (`idEstado`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadCaldera_ibfk_6` FOREIGN KEY (`CreadoPor`) REFERENCES `users` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadCaldera_ibfk_7` FOREIGN KEY (`RealizadoPor`) REFERENCES `users` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ListadoActividadPretratamiento` (
  `idListadoActividadPretratamiento` int(11) NOT NULL AUTO_INCREMENT,
  `idArea` tinyint(4) NOT NULL,
  `idAreaPretratamiento` tinyint(4) NOT NULL,
  `idNombreActividad` tinyint(4) NOT NULL,
  `FechaCreacionActividad` datetime NOT NULL,
  `FechaConclusionActividad` datetime NOT NULL,
  `EstadoActividad` tinyint(4) NOT NULL,
  `CreadoPor` tinyint(4) DEFAULT NULL,
  `RealizadoPor` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idListadoActividadPretratamiento`),
  KEY `idArea` (`idArea`),
  KEY `idAreaPretratamiento` (`idAreaPretratamiento`),
  KEY `idNombreActividad` (`idNombreActividad`),
  KEY `EstadoActividad` (`EstadoActividad`),
  KEY `CreadoPor` (`CreadoPor`),
  KEY `RealizadoPor` (`RealizadoPor`),
  CONSTRAINT `ListadoActividadPretratamiento_ibfk_1` FOREIGN KEY (`idArea`) REFERENCES `Area` (`idArea`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadPretratamiento_ibfk_2` FOREIGN KEY (`idAreaPretratamiento`) REFERENCES `AreaPretratamiento` (`idAreaPretratamiento`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadPretratamiento_ibfk_3` FOREIGN KEY (`idNombreActividad`) REFERENCES `NombreActividad` (`idNombreActividad`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadPretratamiento_ibfk_4` FOREIGN KEY (`EstadoActividad`) REFERENCES `Estado` (`idEstado`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadPretratamiento_ibfk_5` FOREIGN KEY (`CreadoPor`) REFERENCES `users` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadPretratamiento_ibfk_6` FOREIGN KEY (`RealizadoPor`) REFERENCES `users` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ListadoActividadTorreEnfriamiento` (
  `idListadoActividadTorreEnfriamiento` int(11) NOT NULL AUTO_INCREMENT,
  `idArea` tinyint(4) NOT NULL,
  `idNombreActividad` tinyint(4) NOT NULL,
  `FechaCreacionActividad` datetime NOT NULL,
  `FechaConclusionActividad` datetime NOT NULL,
  `EstadoActividad` tinyint(4) NOT NULL,
  `CreadoPor` tinyint(4) NOT NULL,
  `RealizadoPor` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idListadoActividadTorreEnfriamiento`),
  KEY `idArea` (`idArea`),
  KEY `idNombreActividad` (`idNombreActividad`),
  KEY `EstadoActividad` (`EstadoActividad`),
  KEY `CreadoPor` (`CreadoPor`),
  KEY `RealizadoPor` (`RealizadoPor`),
  CONSTRAINT `ListadoActividadTorreEnfriamiento_ibfk_1` FOREIGN KEY (`idArea`) REFERENCES `Area` (`idArea`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadTorreEnfriamiento_ibfk_2` FOREIGN KEY (`idNombreActividad`) REFERENCES `NombreActividad` (`idNombreActividad`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadTorreEnfriamiento_ibfk_3` FOREIGN KEY (`EstadoActividad`) REFERENCES `Estado` (`idEstado`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadTorreEnfriamiento_ibfk_4` FOREIGN KEY (`CreadoPor`) REFERENCES `users` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoActividadTorreEnfriamiento_ibfk_5` FOREIGN KEY (`RealizadoPor`) REFERENCES `users` (`idUsuario`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ListadoMaterialActividadCaldera` (
  `idListadoMaterialActividadCaldera` int(11) NOT NULL AUTO_INCREMENT,
  `idListadoActividadCaldera` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `CantidadProducto` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idListadoMaterialActividadCaldera`),
  KEY `idListadoActividadCaldera` (`idListadoActividadCaldera`),
  KEY `idProducto` (`idProducto`),
  CONSTRAINT `ListadoMaterialActividadCaldera_ibfk_1` FOREIGN KEY (`idListadoActividadCaldera`) REFERENCES `ListadoActividadCaldera` (`idListadoActividadCaldera`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoMaterialActividadCaldera_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `Producto` (`idProducto`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ListadoMaterialActividadPretratamiento` (
  `idListadoMaterialActividadPretratamiento` int(11) NOT NULL AUTO_INCREMENT,
  `idListadoActividadPretratamiento` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `CantidadProducto` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idListadoMaterialActividadPretratamiento`),
  KEY `idListadoActividadPretratamiento` (`idListadoActividadPretratamiento`),
  KEY `idProducto` (`idProducto`),
  CONSTRAINT `ListadoMaterialActividadPretratamiento_ibfk_1` FOREIGN KEY (`idListadoActividadPretratamiento`) REFERENCES `ListadoActividadPretratamiento` (`idListadoActividadPretratamiento`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoMaterialActividadPretratamiento_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `Producto` (`idProducto`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ListadoMaterialActividadTorreEnfriamiento` (
  `idListadoMaterialActividadTorreEnfriamiento` int(11) NOT NULL AUTO_INCREMENT,
  `idListadoActividadTorreEnfriamiento` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `CantidadProducto` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idListadoMaterialActividadTorreEnfriamiento`),
  KEY `idListadoActividadTorreEnfriamiento` (`idListadoActividadTorreEnfriamiento`),
  KEY `idProducto` (`idProducto`),
  CONSTRAINT `ListadoMaterialActividadTorreEnfriamiento_ibfk_1` FOREIGN KEY (`idListadoActividadTorreEnfriamiento`) REFERENCES `ListadoActividadTorreEnfriamiento` (`idListadoActividadTorreEnfriamiento`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ListadoMaterialActividadTorreEnfriamiento_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `Producto` (`idProducto`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MinimosMaximos` (
  `idMinimosMaximos` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) NOT NULL,
  `CantidadMinima` decimal(6,2) NOT NULL,
  `CantidadMaxima` decimal(6,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idMinimosMaximos`),
  KEY `idProducto` (`idProducto`),
  CONSTRAINT `MinimosMaximos_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `Producto` (`idProducto`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NombreActividad` (
  `idNombreActividad` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreActividad` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idNombreActividad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Producto` (
  `idProducto` int(11) NOT NULL AUTO_INCREMENT,
  `CodigoProducto` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `NombreProducto` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `idUnidadMedida` tinyint(4) NOT NULL,
  `EstadoProducto` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idProducto`),
  KEY `idUnidadMedida` (`idUnidadMedida`),
  CONSTRAINT `Producto_ibfk_1` FOREIGN KEY (`idUnidadMedida`) REFERENCES `UnidadMedida` (`idUnidadMedida`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Puesto` (
  `idPuesto` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombrePuesto` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `EstadoPuesto` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idPuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Rol` (
  `idRol` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreRol` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UnidadMedida` (
  `idUnidadMedida` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreUnidadMedida` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `AbreviacionUnidadMedida` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idUnidadMedida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `idUsuario` tinyint(4) NOT NULL AUTO_INCREMENT,
  `NombreUsuario` varchar(35) COLLATE utf8_spanish_ci NOT NULL,
  `ApellidoUsuario` varchar(35) COLLATE utf8_spanish_ci NOT NULL,
  `idPuesto` tinyint(4) NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8_spanish_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idRol` tinyint(4) NOT NULL,
  `EstadoUsuario` tinyint(1) NOT NULL,
  `current_team_id` bigint(20) DEFAULT NULL,
  `profile_photo_path` text COLLATE utf8_spanish_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idUsuario`),
  KEY `idPuesto` (`idPuesto`),
  KEY `idRol` (`idRol`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`idPuesto`) REFERENCES `Puesto` (`idPuesto`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`idRol`) REFERENCES `Rol` (`idRol`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

