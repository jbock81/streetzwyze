/*
SQLyog Community v12.09 (64 bit)
MySQL - 10.4.14-MariaDB : Database - l3n9m1k1_stmerchant_data
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`l3n9m1k1_stmerchant_data` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `l3n9m1k1_stmerchant_data`;

/*Table structure for table `bank_cbn` */

DROP TABLE IF EXISTS `bank_cbn`;

CREATE TABLE `bank_cbn` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `qt_code` varchar(255) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `cbn_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Data for the table `bank_cbn` */

insert  into `bank_cbn`(`bid`,`qt_code`,`bank`,`cbn_code`) values (1,'ABP','Access Bank','044'),(2,'ECO','Ecobank','050'),(3,'FBP','Fidelity Bank','070'),(4,'FBN','First Bank','011'),(5,'FCMB','First City Monument Bank','214'),(6,'GTB','Guaranty Trust Bank','058'),(7,'HBP','Heritage Bank','030'),(8,'JAIZ','Jaiz Bank','301'),(9,'KSB','Keystone Bank','082'),(10,'SKYE','Polaris Bank','076'),(11,'STANBIC','Stanbic IBTC','039'),(12,'UBN','Union Bank','032'),(13,'UBA','United Bank For Africa','033'),(14,'UNITY','Unity Bank','215'),(15,'WEMA','Wema Bank','035'),(16,'ZIB','Zenith Bank','057'),(17,'UMB','Providus Bank','101'),(18,'SUN','Suntrust Bank','100');

/*Table structure for table `dashboard_tiles` */

DROP TABLE IF EXISTS `dashboard_tiles`;

CREATE TABLE `dashboard_tiles` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_flag` int(11) NOT NULL DEFAULT 0,
  `t_imgname` varchar(255) NOT NULL,
  `t_value` float(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `dashboard_tiles` */

insert  into `dashboard_tiles`(`t_id`,`t_flag`,`t_imgname`,`t_value`) values (1,0,'Penalty-fee-idle.png',0.00),(2,0,'Secured-Fund-idle.png',0.00),(3,0,'Successful-Delivery-idle.png',0.00),(4,0,'remitttance-idle.png',0.00);

/*Table structure for table `merchant` */

DROP TABLE IF EXISTS `merchant`;

CREATE TABLE `merchant` (
  `MId` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` text NOT NULL,
  `Lastname` text NOT NULL,
  `Emailaddress` text NOT NULL,
  `Mobilenumber` text NOT NULL,
  `Photo` text NOT NULL,
  `Businessname` text NOT NULL,
  `PaymentTag` text NOT NULL,
  `Mpassword` text NOT NULL,
  `Configduration` int(11) NOT NULL,
  `Configpayvalue` int(5) NOT NULL,
  `Penaltyfee` double(6,2) NOT NULL,
  `Referralcode` text NOT NULL,
  `Signupdate` datetime NOT NULL,
  `Lastlogin` datetime NOT NULL,
  `Token` text NOT NULL,
  `Approve` int(1) NOT NULL DEFAULT 0,
  `ReferralId` int(11) NOT NULL,
  PRIMARY KEY (`MId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `merchant` */

insert  into `merchant`(`MId`,`Firstname`,`Lastname`,`Emailaddress`,`Mobilenumber`,`Photo`,`Businessname`,`PaymentTag`,`Mpassword`,`Configduration`,`Configpayvalue`,`Penaltyfee`,`Referralcode`,`Signupdate`,`Lastlogin`,`Token`,`Approve`,`ReferralId`) values (1,'sdfsdf','dfgdfg','uegroup@outlook.com','34534534','Ym9vdHN0cmFwNC5wbmc=.png','okokok','1203456789032','5fa72358f0b4fb4f2c5d7de8c9a41846',12,35000,0.00,'','2020-11-20 08:27:52','2020-11-23 08:58:56','6eab6a69f2a7c123bc4c1cab9f3b4ddf',1,0),(2,'qqq','eee','uegroup921@outlook.com','856756754','','','','76d80224611fc919a5d54f0ff9fba446',0,0,0.00,'996c7dd90c10cad2972edd0d64b96e2a','2020-11-20 17:33:51','0000-00-00 00:00:00','88dc1ecfe11ea38746c4a4eabf2f01ef',0,0);

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `OId` int(11) NOT NULL AUTO_INCREMENT,
  `MId` int(11) NOT NULL,
  `Reservationid` text NOT NULL,
  `ReservationTag` text NOT NULL,
  `Orderamount` double(10,2) NOT NULL,
  `OrdStatus` text NOT NULL,
  `Reservedate` datetime NOT NULL,
  `DeliveryStatus` text NOT NULL,
  `DisburseStatus` text NOT NULL,
  `Payoutdate` datetime NOT NULL,
  `Payoutvalue` double(10,2) NOT NULL,
  `Retry5` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`OId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `orders` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
