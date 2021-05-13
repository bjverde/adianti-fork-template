-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema log
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `log` ;

-- -----------------------------------------------------
-- Schema log
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `log` DEFAULT CHARACTER SET utf8 ;
USE `log` ;

-- -----------------------------------------------------
-- Table `system_access_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `system_access_log` ;

CREATE TABLE IF NOT EXISTS `system_access_log` (
	`id`	INTEGER NOT NULL,
	`sessionid`	TEXT NOT NULL,
	`login`	TEXT NOT NULL,
	`login_time` timestamp,
	`login_year`  VARCHAR ( 4 ) NULL DEFAULT NULL,
	`login_month` VARCHAR ( 2 ) NULL DEFAULT NULL,
	`login_day`	VARCHAR ( 2 ) NULL DEFAULT NULL,
	`logout_time`	timestamp,
	`impersonated`	char ( 1 ),
	`access_ip`	VARCHAR ( 45 ) NULL DEFAULT NULL,
	PRIMARY KEY(`id`)
);
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `system_change_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `system_change_log` ;

CREATE TABLE `system_change_log` (
	`id`	     INTEGER NOT NULL,
	`logdate`	 timestamp,
	`login`	     TEXT,
	`tablename`	 TEXT,
	`primarykey` TEXT,
	`pkvalue`	 TEXT,
	`operation`	 TEXT,
	`columnname` TEXT,
	`oldvalue`	 TEXT,
	`newvalue`	 TEXT,
	`access_ip`	 TEXT,
	`transaction_id`	TEXT,
	`log_trace`	 TEXT,
	`session_id` TEXT,
	`class_name` TEXT,
	`php_sapi`	 TEXT,
	`log_year`	 varchar ( 4 ) NULL DEFAULT NULL,
	`log_month`	 VARCHAR ( 2 ) NULL DEFAULT NULL,
	`log_day`	 VARCHAR ( 2 ) NULL DEFAULT NULL,
	PRIMARY KEY(`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


