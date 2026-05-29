/* Database for use with COMP4039-DIS Coursework 2
 *
 * This version is tailored to the PHP application in html/cw2 so a fresh
 * Docker/Codespaces environment can be rebuilt from source without relying on
 * an existing mariadb-data volume.
 */

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `cw2-database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cw2-database`;

DROP TABLE IF EXISTS `Modification`;
DROP TABLE IF EXISTS `Fines`;
DROP TABLE IF EXISTS `Incident`;
DROP TABLE IF EXISTS `Ownership`;
DROP TABLE IF EXISTS `Vehicle`;
DROP TABLE IF EXISTS `People`;
DROP TABLE IF EXISTS `Offence`;
DROP TABLE IF EXISTS `User`;

CREATE TABLE `User` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Authority` varchar(30) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uk_user_username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `User` (`ID`, `Username`, `Password`, `Authority`) VALUES
(1, 'admin', 'admin123', 'Administrator'),
(2, 'officer', 'officer123', 'PoliceOfficer');

CREATE TABLE `People` (
  `People_ID` int(11) NOT NULL AUTO_INCREMENT,
  `People_name` varchar(50) NOT NULL,
  `People_address` varchar(100) DEFAULT NULL,
  `People_licence` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`People_ID`),
  UNIQUE KEY `uk_people_licence` (`People_licence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `People` (`People_ID`, `People_name`, `People_address`, `People_licence`) VALUES
(1, 'James Smith', '23 Barnsdale Road, Leicester', 'SMITH92LDOFJJ829'),
(2, 'Jennifer Allen', '46 Bramcote Drive, Nottingham', 'ALLEN88K23KLR9B3'),
(3, 'John Myers', '323 Derby Road, Nottingham', 'MYERS99JDW8REWL3'),
(4, 'James Smith', '26 Devonshire Avenue, Nottingham', 'SMITHR004JFS20TR'),
(5, 'Terry Brown', '7 Clarke Rd, Nottingham', 'BROWND3PJJ39DLFG'),
(6, 'Mary Adams', '38 Thurman St, Nottingham', 'ADAMSH9O3JRHH107'),
(7, 'Neil Becker', '6 Fairfax Close, Nottingham', 'BECKE88UPR840F9R'),
(8, 'Angela Smith', '30 Avenue Road, Grantham', 'SMITH222LE9FJ5DS'),
(9, 'Xene Medora', '22 House Drive, West Bridgford', 'MEDORH914ANBB223');

CREATE TABLE `Vehicle` (
  `Vehicle_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Vehicle_make` varchar(50) NOT NULL,
  `Vehicle_model` varchar(50) NOT NULL,
  `Vehicle_plate` varchar(10) DEFAULT NULL,
  `Vehicle_colour` varchar(20) NOT NULL,
  PRIMARY KEY (`Vehicle_ID`),
  UNIQUE KEY `uk_vehicle_plate` (`Vehicle_plate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Vehicle` (`Vehicle_ID`, `Vehicle_make`, `Vehicle_model`, `Vehicle_plate`, `Vehicle_colour`) VALUES
(12, 'Ford', 'Fiesta', 'LB15AJL', 'Blue'),
(13, 'Ferrari', '458', 'MY64PRE', 'Red'),
(14, 'Vauxhall', 'Astra', 'FD65WPQ', 'Silver'),
(15, 'Honda', 'Civic', 'FJ17AUG', 'Green'),
(16, 'Toyota', 'Prius', 'FP16KKE', 'Silver'),
(17, 'Ford', 'Mondeo', 'FP66KLM', 'Black'),
(18, 'Ford', 'Focus', 'DJ14SLE', 'White'),
(20, 'Nissan', 'Pulsar', 'NY64KWD', 'Red'),
(21, 'Renault', 'Scenic', 'BC16OEA', 'Silver'),
(22, 'Hyundai', 'i30', 'AD223NG', 'Grey');

CREATE TABLE `Offence` (
  `Offence_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Offence_description` varchar(50) NOT NULL,
  `Offence_maxFine` int(11) NOT NULL,
  `Offence_maxPoints` int(11) NOT NULL,
  PRIMARY KEY (`Offence_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Offence` (`Offence_ID`, `Offence_description`, `Offence_maxFine`, `Offence_maxPoints`) VALUES
(1, 'Speeding', 1000, 3),
(2, 'Speeding on a motorway', 2500, 6),
(3, 'Seat belt offence', 500, 0),
(4, 'Illegal parking', 500, 0),
(5, 'Drink driving', 10000, 11),
(6, 'Driving without a licence', 10000, 0),
(7, 'Traffic light offences', 1000, 3),
(8, 'Cycling on pavement', 500, 0),
(9, 'Failure to have control of vehicle', 1000, 3),
(10, 'Dangerous driving', 1000, 11),
(11, 'Careless driving', 5000, 6),
(12, 'Dangerous cycling', 2500, 0);

CREATE TABLE `Ownership` (
  `People_ID` int(11) NOT NULL,
  `Vehicle_ID` int(11) NOT NULL,
  PRIMARY KEY (`People_ID`, `Vehicle_ID`),
  KEY `fk_ownership_vehicle` (`Vehicle_ID`),
  CONSTRAINT `fk_ownership_people` FOREIGN KEY (`People_ID`) REFERENCES `People` (`People_ID`),
  CONSTRAINT `fk_ownership_vehicle` FOREIGN KEY (`Vehicle_ID`) REFERENCES `Vehicle` (`Vehicle_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Ownership` (`People_ID`, `Vehicle_ID`) VALUES
(3, 12),
(8, 20),
(4, 15),
(4, 13),
(1, 16),
(2, 14),
(5, 17),
(6, 18),
(7, 21);

CREATE TABLE `Incident` (
  `Incident_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Vehicle_ID` int(11) DEFAULT NULL,
  `People_ID` int(11) DEFAULT NULL,
  `Incident_Date` date NOT NULL,
  `Incident_Report` varchar(500) NOT NULL,
  `Offence_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`Incident_ID`),
  KEY `fk_incident_offence` (`Offence_ID`),
  KEY `fk_incident_people` (`People_ID`),
  KEY `fk_incident_vehicle` (`Vehicle_ID`),
  KEY `fk_incident_user` (`User_ID`),
  CONSTRAINT `fk_incident_offence` FOREIGN KEY (`Offence_ID`) REFERENCES `Offence` (`Offence_ID`),
  CONSTRAINT `fk_incident_people` FOREIGN KEY (`People_ID`) REFERENCES `People` (`People_ID`),
  CONSTRAINT `fk_incident_vehicle` FOREIGN KEY (`Vehicle_ID`) REFERENCES `Vehicle` (`Vehicle_ID`),
  CONSTRAINT `fk_incident_user` FOREIGN KEY (`User_ID`) REFERENCES `User` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Incident` (`Incident_ID`, `Vehicle_ID`, `People_ID`, `Incident_Date`, `Incident_Report`, `Offence_ID`, `User_ID`) VALUES
(1, 15, 4, '2017-12-01', '40mph in a 30 limit', 1, 2),
(2, 20, 8, '2017-11-01', 'Double parked', 4, 2),
(3, 13, 4, '2017-09-17', '110mph on motorway', 1, 1),
(4, 14, 2, '2017-08-22', 'Failure to stop at a red light - travelling 25mph', 8, 1),
(5, 13, 4, '2017-10-17', 'Not wearing a seatbelt on the M1', 3, 2);

CREATE TABLE `Fines` (
  `Fine_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fine_Amount` int(11) NOT NULL,
  `Fine_Points` int(11) NOT NULL,
  `Incident_ID` int(11) NOT NULL,
  PRIMARY KEY (`Fine_ID`),
  KEY `fk_fines_incident` (`Incident_ID`),
  CONSTRAINT `fk_fines_incident` FOREIGN KEY (`Incident_ID`) REFERENCES `Incident` (`Incident_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Fines` (`Fine_ID`, `Fine_Amount`, `Fine_Points`, `Incident_ID`) VALUES
(1, 1000, 3, 3),
(2, 50, 0, 2),
(3, 500, 0, 4);

CREATE TABLE `Modification` (
  `Modification_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) DEFAULT NULL,
  `Modification_type` varchar(20) NOT NULL,
  `Modification_datetime` datetime NOT NULL,
  `Modification_table` varchar(50) NOT NULL,
  `Modification_description` varchar(500) NOT NULL,
  `Modification_ref_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`Modification_ID`),
  KEY `fk_modification_user` (`User_ID`),
  CONSTRAINT `fk_modification_user` FOREIGN KEY (`User_ID`) REFERENCES `User` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Modification` (`Modification_ID`, `User_ID`, `Modification_type`, `Modification_datetime`, `Modification_table`, `Modification_description`, `Modification_ref_ID`) VALUES
(1, 1, 'Insert', '2024-01-01 09:00:00', 'Incident', 'Seeded incident records for the demo database', 1),
(2, 2, 'Other', '2024-01-01 09:05:00', 'People', 'Seeded people records for the demo database', NULL);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
