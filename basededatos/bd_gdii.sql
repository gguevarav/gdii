DROP DATABASE IF EXISTS db_gdii;

CREATE DATABASE db_gdii;

USE db_gdii;

CREATE TABLE Estado(
	idEstado				TINYINT				NOT NULL				PRIMARY KEY             AUTO_INCREMENT,
	NombreEstado                            VARCHAR(12)			NOT NULL,
	created_at				TIMESTAMP			NULL,
	updated_at				TIMESTAMP			NULL
)ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;

CREATE TABLE Puesto(
	idPuesto				TINYINT 			NOT NULL				PRIMARY KEY             AUTO_INCREMENT,
	NombrePuesto			        VARCHAR(50)			NOT NULL,
	EstadoPuesto			        BOOLEAN				NOT NULL,
	created_at				TIMESTAMP			NULL,
	updated_at				TIMESTAMP			NULL
)ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;

CREATE TABLE Rol(
	idRol					TINYINT 			NOT NULL				PRIMARY KEY             AUTO_INCREMENT,
	NombreRol				VARCHAR(25)			NOT NULL,
	created_at				TIMESTAMP			NULL,
	updated_at				TIMESTAMP			NULL
)ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;

CREATE TABLE users(
	idUsuario				TINYINT 			NOT NULL				PRIMARY KEY             AUTO_INCREMENT,
	NombreUsuario			        VARCHAR(35)			NOT NULL,
	ApellidoUsuario			        VARCHAR(35)			NOT NULL,
	idPuesto				TINYINT 			NOT NULL,
	email					VARCHAR(100)		        NOT NULL,
	email_verified_at		        TIMESTAMP			NULL,
	password 				VARCHAR(191)		        NOT NULL,
	remember_token			        VARCHAR(100)		        NULL,
	idRol					TINYINT 			NOT NULL,
	EstadoUsuario			        BOOLEAN				NOT NULL,
	current_team_id			        BIGINT(20)			NULL,
	profile_photo_path		        TEXT				NULL,
	created_at				TIMESTAMP			NULL,
	updated_at				TIMESTAMP			NULL,
	INDEX (idPuesto),
    FOREIGN KEY (idPuesto)
            REFERENCES Puesto(idPuesto)
            ON DELETE CASCADE
            ON UPDATE NO ACTION,
    INDEX (idRol),
    FOREIGN KEY (idRol)
            REFERENCES Rol(idRol)
            ON DELETE CASCADE
            ON UPDATE NO ACTION
)ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_spanish_ci;

CREATE TABLE Rir(
        idRIR                                   TINYINT                         NOT NULL				PRIMARY KEY             AUTO_INCREMENT,
        identificador                           VARCHAR(10)                     NOT NULL,
        NombreRir                               VARCHAR(10)                     NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Agregado(
        idAgregado                              INTEGER 			NOT NULL				PRIMARY KEY             AUTO_INCREMENT,
        Prefijo                                 VARCHAR(18)                     NOT NULL,
        idRir                                   TINYINT                         NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL,
        INDEX (idRir),
        FOREIGN KEY (idRir)
                REFERENCES Rir(idRir)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Prefijo(
        idPrefijo                               INTEGER 			NOT NULL				PRIMARY KEY             AUTO_INCREMENT,
        Prefijo                                 VARCHAR(18)                     NOT NULL,
        idAgregado                              INTEGER                         NOT NULL,
        idEstado                                TINYINT                         NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL,
        INDEX (idAgregado),
        FOREIGN KEY (idAgregado)
                REFERENCES Agregado(idAgregado)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idEstado),
        FOREIGN KEY (idEstado)
                REFERENCES Estado(idEstado)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE RangoIP(
        idRangoIp                               INTEGER 			NOT NULL				PRIMARY KEY             AUTO_INCREMENT,
        DireccionInicio                         VARCHAR(18)                     NOT NULL,
        DireccionFin                            VARCHAR(18)                     NOT NULL,
        idPrefijo                               INTEGER                         NOT NULL,
        idEstado                                TINYINT                         NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL,
        INDEX (idPrefijo),
        FOREIGN KEY (idPrefijo)
                REFERENCES Prefijo(idPrefijo)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idEstado),
        FOREIGN KEY (idEstado)
                REFERENCES Estado(idEstado)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE DireccionIP(
        idDireccionIP                           BIGINT                          NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        Direccion                               VARCHAR(18)                     NOT NULL,
        Nombre                                  VARCHAR(35)                     NOT NULL,
        idRangoIp                               INTEGER                         NOT NULL,
        idRol                                   TINYINT                         NOT NULL,
        idEstado                                TINYINT                         NOT NULL,
        identificador                           VARCHAR(35)                     NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL,
        INDEX (idRangoIp),
        FOREIGN KEY (idRangoIp)
                REFERENCES RangoIP(idRangoIp)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idRol),
        FOREIGN KEY (idRol)
                REFERENCES Rol(idRol)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idEstado),
        FOREIGN KEY (idEstado)
                REFERENCES Estado(idEstado)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE VLAN(
        idVLAN                                  INTEGER                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        Nombre                                  VARCHAR(25)                     NOT NULL,
        idEstado                                TINYINT                         NOT NULL,
        Descripcion                             VARCHAR(59)                     NOT NULL,
        INDEX (idEstado),
        FOREIGN KEY (idEstado)
                REFERENCES Estado(idEstado)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Region(
        idRegion                                INTEGER                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        Nombre                                  VARCHAR(50)                     NOT NULL,
        identificador                           VARCHAR(50)                     NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Sitio(
        idSitio                                 INTEGER                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        Nombre                                  VARCHAR(35)                     NOT NULL,
        identificador                           VARCHAR(35)                     NOT NULL,
        idEstado                                TINYINT                         NOT NULL,
        idRegion                                INTEGER                         NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL,
        INDEX (idEstado),
        FOREIGN KEY (idEstado)
                REFERENCES Estado(idEstado)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idRegion),
        FOREIGN KEY (idRegion)
                REFERENCES Region(idRegion)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Ubicacion(
        idUbicacion                             INTEGER                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        idSitio                                 INTEGER                         NOT NULL,
        idUbicacionPadre                             INTEGER                         NULL,
        Nombre                                  VARCHAR(35)                     NOT NULL,
        identificador                           VARCHAR(35)                     NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL,
        INDEX (idSitio),
        FOREIGN KEY (idSitio)
                REFERENCES Sitio(idSitio)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idUbicacionPadre),
        FOREIGN KEY (idUbicacionPadre)
                REFERENCES Ubicacion(idUbicacion)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE TipoGabinete(
        idTipoGabinete                          TINYINT                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        Nombre                                  VARCHAR(35)                     NOT NULL,
        identificador                           VARCHAR(35)                     NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Gabinete(
        idGabinete                              INTEGER                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        idSitio                                 INTEGER                         NOT NULL,
        idUbicacion                             INTEGER                         NOT NULL,
        Nombre                                  VARCHAR(40)                     NOT NULL,
        idEstado                                TINYINT                         NOT NULL,
        idTipoGabinete                          TINYINT                         NOT NULL,
        Ancho                                   VARCHAR(10)                     NOT NULL,
        Alto                                    VARCHAR(10)                     NOT NULL,
        INDEX (idSitio),
        FOREIGN KEY (idSitio)
                REFERENCES Sitio(idSitio)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idUbicacion),
        FOREIGN KEY (idUbicacion)
                REFERENCES Ubicacion(idUbicacion)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idTipoGabinete),
        FOREIGN KEY (idTipoGabinete)
                REFERENCES TipoGabinete(idTipoGabinete)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE RolDispositivo(
        idRolDispositivo                        TINYINT                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        Nombre                                  VARCHAR(20)                     NOT NULL,
        identificador                           VARCHAR(20)                     NOT NULL,
        Color                                   VARCHAR(25)                     NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Marca(
        idMarca                                 TINYINT                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        Nombre                                  VARCHAR(25)                     NOT NULL,
        identificador                           VARCHAR(25)                     NOT NULL,
        Descripcion                             VARCHAR(50)                     NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE TipoDispositivo(
        idTipoDispositivo                       TINYINT                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        idMarca                                 TINYINT                         NOT NULL,
        Modelo                                  VARCHAR(25)                     NOT NULL,
        identificador                           VARCHAR(25)                     NOT NULL,
        TamanioU                                TINYINT                         NOT NULL,
        INDEX (idMarca),
        FOREIGN KEY (idMarca)
                REFERENCES Marca(idMarca)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Plataforma(
        idPlataforma                            TINYINT                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        NombrePlataforma                        VARCHAR(25)                     NOT NULL,
        identificador                           VARCHAR(25)                     NOT NULL,
        idMarca                                 TINYINT                         NOT NULL,
        INDEX (idMarca),
        FOREIGN KEY (idMarca)
                REFERENCES Marca(idMarca)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Dispositivo(
        idDispositivo                           INTEGER                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        Nombre                                  VARCHAR(25)                     NOT NULL,
        idRolDispositivo                        TINYINT                         NOT NULL,
        idTipoDispositivo                       TINYINT                         NOT NULL,
        NumeroSerie                             VARCHAR(25)                     NOT NULL,
        idSitio                                 INTEGER                         NOT NULL,
        idUbicacion                             INTEGER                         NOT NULL,
        idGabinete                              INTEGER                         NOT NULL,
        CaraGabinete                            TINYINT                         NOT NULL,
        PosicionEnGabinete                      TINYINT                         NOT NULL,
        idEstado                                TINYINT                         NOT NULL,
        idPlataforma                            TINYINT                         NOT NULL,
        INDEX (idRolDispositivo),
        FOREIGN KEY (idRolDispositivo)
                REFERENCES RolDispositivo(idRolDispositivo)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idTipoDispositivo),
        FOREIGN KEY (idTipoDispositivo)
                REFERENCES TipoDispositivo(idTipoDispositivo)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idSitio),
        FOREIGN KEY (idSitio)
                REFERENCES Sitio(idSitio)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idUbicacion),
        FOREIGN KEY (idUbicacion)
                REFERENCES Ubicacion(idUbicacion)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idGabinete),
        FOREIGN KEY (idGabinete)
                REFERENCES Gabinete(idGabinete)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idEstado),
        FOREIGN KEY (idEstado)
                REFERENCES Estado(idEstado)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
        INDEX (idPlataforma),
        FOREIGN KEY (idPlataforma)
                REFERENCES Plataforma(idPlataforma)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Diagrama(
        idDiagrama                              INTEGER                         NOT NULL                                PRIMARY KEY             AUTO_INCREMENT,
        NombreDiagrama                          VARCHAR(20)                     NOT NULL,
        identificador                           VARCHAR(20)                     NOT NULL,
        json_diagrama                           TEXT                            NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `migrations` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                              `batch` int(11) NOT NULL,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `oauth_personal_access_clients` (
                                                 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                                 `client_id` bigint(20) unsigned NOT NULL,
                                                 `created_at` timestamp NULL DEFAULT NULL,
                                                 `updated_at` timestamp NULL DEFAULT NULL,
                                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `oauth_refresh_tokens` (
                                        `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                                        `access_token_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                                        `revoked` tinyint(1) NOT NULL,
                                        `expires_at` datetime DEFAULT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `password_resets` (
                                   `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                   `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Estados
INSERT INTO Estado (idEstado, NombreEstado, created_at, updated_at)
			VALUES (NULL, 'Creado', NULL, NULL),
			       (NULL, 'Activo', NULL, NULL),
			       (NULL, 'Inactivo', NULL, NULL),
                   (NULL, 'En proceso', NULL, NULL),
                   (NULL, 'Cerrado', NULL, NULL),
                   (NULL, 'Cancelado', NULL, NULL),
                   (NULL, 'Inventariado', NULL, NULL);

-- Puesto
INSERT INTO Puesto (idPuesto, NombrePuesto, EstadoPuesto,
                      created_at, updated_at)
            VALUES (NULL, 'Administrador', 2, NULL, NULL);

-- Rol
INSERT INTO Rol (idRol, NombreRol, created_at, updated_at)
         VALUES (NULL, 'Administrador', NULL, NULL),
                (NULL, 'Gerente', NULL, NULL),
                (NULL, 'Supervisor', NULL, NULL),
                (NULL, 'Auxiliar', NULL, NULL),
                (NULL, 'Operador', NULL, NULL);

-- Usuario
INSERT INTO users (idUsuario, NombreUsuario, ApellidoUsuario,
                   idPuesto, email, email_verified_at, password,
                   remember_token, idRol, EstadoUsuario, current_team_id,
                   profile_photo_path, created_at, updated_at)
           VALUES (NULL, 'administrador', 'administrador', 1, 'admin@admin.com',
                   NULL, '$2y$10$2vEWqXrBS8Qwp/KBW7fdwuxXzXB0YDL92M99OBhkSNLn4z3IHT1Va',
                   NULL, 1, 2, NULL, NULL, NULL, NULL);
