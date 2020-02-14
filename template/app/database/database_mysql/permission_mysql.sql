-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema permission
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `form_exemplo` ;

-- -----------------------------------------------------
-- Schema permission
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `permission` DEFAULT CHARACTER SET utf8 ;
USE `permission` ;

-- -----------------------------------------------------
-- Table `permission`.`system_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `system_group` ;

CREATE TABLE IF NOT EXISTS `system_group` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `permission`.`system_program`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`.`system_program` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  `controller` VARCHAR(100) NULL DEFAULT NULL,
  `system_programcol` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `permission`.`system_group_program`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`.`system_group_program` (
  `id` INT(11) NOT NULL,
  `system_group_id` INT(11) NOT NULL,
  `system_program_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_group_program_program_idx` (),
  INDEX `fk_system_group_program_system_group1_idx` (`system_group_id` ASC),
  INDEX `fk_system_group_program_system_program1_idx` (`system_program_id` ASC),
  CONSTRAINT `fk_system_group_program_system_group1`
    FOREIGN KEY (`system_group_id`)
    REFERENCES `permission`.`system_group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_system_group_program_system_program1`
    FOREIGN KEY (`system_program_id`)
    REFERENCES `permission`.`system_program` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `permission`.`system_preference`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`.`system_preference` (
  `id` VARCHAR(100) NOT NULL,
  `value` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `permission`.`system_unit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`.`system_unit` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  `connection_name` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `permission`.`system_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`.`system_user` (
  `id` INT(11) NOT NULL,
  `login` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `name` VARCHAR(100) NULL,
  `email` VARCHAR(100) NULL,
  `active` CHAR(1) NULL,
  `frontpage_id` INT(11) NOT NULL,
  `system_unit_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_program_idx` (),
  INDEX `fk_system_user_system_unit_idx` (`system_unit_id` ASC),
  INDEX `fk_system_user_system_program1_idx` (`frontpage_id` ASC),
  CONSTRAINT `fk_system_user_system_unit`
    FOREIGN KEY (`system_unit_id`)
    REFERENCES `permission`.`system_unit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_system_user_system_program1`
    FOREIGN KEY (`frontpage_id`)
    REFERENCES `permission`.`system_program` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `permission`.`system_user_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`.`system_user_group` (
  `id` INT(11) NOT NULL,
  `system_user_id` INT(11) NOT NULL,
  `system_group_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_group_group_idx` (),
  INDEX `fk_system_user_group_system_user1_idx` (`system_user_id` ASC),
  INDEX `fk_system_user_group_system_group1_idx` (`system_group_id` ASC),
  CONSTRAINT `fk_system_user_group_system_user1`
    FOREIGN KEY (`system_user_id`)
    REFERENCES `permission`.`system_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_system_user_group_system_group1`
    FOREIGN KEY (`system_group_id`)
    REFERENCES `permission`.`system_group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `permission`.`system_user_program`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`.`system_user_program` (
  `id` INT(11) NOT NULL,
  `system_user_id` INT(11) NOT NULL,
  `system_program_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_program_program_idx` (),
  INDEX `fk_system_user_program_system_user1_idx` (`system_user_id` ASC),
  INDEX `fk_system_user_program_system_program1_idx` (`system_program_id` ASC),
  CONSTRAINT `fk_system_user_program_system_user1`
    FOREIGN KEY (`system_user_id`)
    REFERENCES `permission`.`system_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_system_user_program_system_program1`
    FOREIGN KEY (`system_program_id`)
    REFERENCES `permission`.`system_program` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `permission`.`system_user_unit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permission`.`system_user_unit` (
  `id` INT(11) NOT NULL,
  `system_user_id` INT(11) NOT NULL,
  `system_unit_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_system_user_unit_system_unit1_idx` (`system_unit_id` ASC),
  INDEX `fk_system_user_unit_system_user1_idx` (`system_user_id` ASC),
  CONSTRAINT `fk_system_user_unit_system_unit1`
    FOREIGN KEY (`system_unit_id`)
    REFERENCES `permission`.`system_unit` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_system_user_unit_system_user1`
    FOREIGN KEY (`system_user_id`)
    REFERENCES `permission`.`system_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
