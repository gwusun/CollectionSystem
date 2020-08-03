-- noinspection SqlDialectInspectionForFile

-- noinspection SqlNoDataSourceInspectionForFile

-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema school_homework
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema school_homework
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `school_homework` DEFAULT CHARACTER SET utf8 ;
USE `school_homework` ;

-- -----------------------------------------------------
-- Table `school_homework`.`sh_classes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `school_homework`.`sh_classes` (
  `pk_classes` CHAR(32) NOT NULL,
  `class_name` VARCHAR(255) NULL COMMENT '班级名称\n',
  `create_time` INT NULL,
  `status` ENUM('able', 'disable', 'deleted') NULL,
  PRIMARY KEY (`pk_classes`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `school_homework`.`sh_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `school_homework`.`sh_user` (
  `pk_user` CHAR(32) NOT NULL,
  `fk_class` CHAR(32) NULL,
  `login_name` VARCHAR(45) NULL,
  `login_passwork` VARCHAR(45) NULL,
  `login_email` VARCHAR(45) NULL,
  `create_time` INT NULL,
  `update_time` INT NULL,
  `status` ENUM('able', 'disable', 'deleted') NULL,
  `name` VARCHAR(45) NULL,
  `stu_no` VARCHAR(45) NULL COMMENT '学号',
  PRIMARY KEY (`pk_user`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `school_homework`.`sh_homeworks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `school_homework`.`sh_homeworks` (
  `pk_homeworks` CHAR(32) NOT NULL,
  `fk_classes` CHAR(32) NULL,
  `title` VARCHAR(45) NULL,
  `auto_name_rule` VARCHAR(255) NULL COMMENT '命名规则\n',
  `create_time` INT NULL,
  `end_time` INT NULL COMMENT '截止日期',
  `desc` VARCHAR(255) NULL,
  `status` ENUM('able', 'disable', 'deleted') NULL,
  `update_time` INT NULL,
  PRIMARY KEY (`pk_homeworks`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `school_homework`.`sh_works_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `school_homework`.`sh_works_items` (
  `pk_works_items` CHAR(32) NOT NULL,
  `fk_homeworks` CHAR(32) NULL,
  `fk_user` CHAR(32) NULL,
  `file_path` VARCHAR(255) NULL COMMENT '文件路径',
  `create_time` ENUM('able', 'disable', 'deleted') NULL,
  `update_time` INT NULL,
  PRIMARY KEY (`pk_works_items`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `school_homework`.`sh_admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `school_homework`.`sh_admin` (
  `pk_admin` CHAR(32) NOT NULL,
  `fk_user` CHAR(45) NULL,
  `update_time` INT NULL,
  `create_time` INT NULL,
  `status` ENUM('able', 'disable', 'deleted') NULL,
  PRIMARY KEY (`pk_admin`))
ENGINE = InnoDB;
insert into school_homework.sh_classes values (1,'表格收集',0,'able');



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;




