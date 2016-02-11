/*
SQLyog Ultimate v11.11 (32 bit)
MySQL - 5.6.12-log : Database - task_manager
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`task_manager` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `task_manager`;

/*Table structure for table `tasks` */

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

/*Data for the table `tasks` */

insert  into `tasks`(`id`,`name`,`created_at`,`updated_at`,`status`) values (47,'Download CodeIgniter','2014-02-26 21:22:06','2014-02-26 23:35:17',1),(48,'Setup CodeIgniter\'s environment by going over through the config.php, autoload.php, database.php and routes.php','2014-02-26 21:22:35','2014-02-26 23:35:17',1),(49,'Create the view\'s intial Mock up','2014-02-26 21:55:47','2014-02-26 23:35:17',1),(50,'Create the appropriate Controller for the view and load the view file','2014-02-26 21:55:57','2014-02-26 23:35:18',1),(51,'Check if CodeIgniter is properly setup by going to your browser and type in the url: localhost/project_name','2014-02-26 21:56:34','2014-02-26 23:35:19',1),(52,'Create the appropriate database for your CodeIgniter project','2014-02-26 22:29:18','2014-02-26 23:33:48',0),(53,'Create a model and load it in your controller','2014-02-26 22:45:21','2014-02-26 23:33:58',0),(54,'Display information from database table called \"users\" in the controller.','2014-02-26 23:03:05','2014-02-26 23:34:45',0),(55,'Display information from database table called \"users\" in the view.','2014-02-26 23:04:01','2014-02-26 23:35:00',0),(56,'Insert something to database','2014-02-26 23:25:43','2014-02-26 23:35:14',0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
