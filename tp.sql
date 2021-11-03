/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 8.0.12 : Database - test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`test` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `test`;

/*Table structure for table `log` */

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `param` text,
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `log` */

/*Table structure for table `test` */

DROP TABLE IF EXISTS `test`;

CREATE TABLE `test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `test` */

insert  into `test`(`id`,`name`,`time`) values (2,'hahahhahahah',0),(3,'sadf',0),(4,'gdgdgdg',0),(5,'sdgdhf',0),(6,'777',0),(7,'nihao',1632642905);

/*Table structure for table `test_es` */

DROP TABLE IF EXISTS `test_es`;

CREATE TABLE `test_es` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `profile` text NOT NULL,
  `age` int(10) unsigned NOT NULL,
  `job` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `test_es` */

insert  into `test_es`(`id`,`name`,`profile`,`age`,`job`) values (1,'张三','你是谁',20,'互联网开发者'),(2,'李四','我好牛逼啊',22,'程序员'),(3,'王五','哈哈哈哈',21,'无业游民'),(4,'小明3','你想怎样啊',25,'高级工程师'),(5,'小明2','好吃懒做',23,'外卖工作者'),(6,'小明1','我是爱国的',24,'华为高级工程师');

/*Table structure for table `think_auth_group` */

DROP TABLE IF EXISTS `think_auth_group`;

CREATE TABLE `think_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `think_auth_group` */

/*Table structure for table `think_auth_group_access` */

DROP TABLE IF EXISTS `think_auth_group_access`;

CREATE TABLE `think_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `think_auth_group_access` */

/*Table structure for table `think_auth_rule` */

DROP TABLE IF EXISTS `think_auth_rule`;

CREATE TABLE `think_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `think_auth_rule` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `score` float DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `email` varchar(100) DEFAULT '',
  `login_count` int(10) unsigned NOT NULL DEFAULT '0',
  `age` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`name`,`status`,`score`,`create_time`,`update_time`,`email`,`login_count`,`age`) values (1,'哈哈',1,90,'2021-09-27 17:19:50','2021-09-27 17:19:47','11@qq.com',0,1),(2,'thinkphp',1,80,'2021-09-27 17:37:26','2021-09-27 17:37:26','thinkphp@qq.com',0,1),(3,'thinkphp1',1,88,'2021-09-27 17:41:40','2021-09-27 17:41:40','thinkphp1@qq.com',0,1),(4,'thinkphp3',1,70,'2021-09-27 17:44:05','2021-09-27 17:44:05','thinkphp@qq.com3',0,1),(5,'thinkphp333',1,45,'2021-09-27 17:45:08','2021-09-27 17:45:08','thinkphp@qq.com',0,1),(6,'onethink333',1,66,'2021-09-27 17:45:08','2021-09-27 17:45:08','onethink@qq.com',0,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
