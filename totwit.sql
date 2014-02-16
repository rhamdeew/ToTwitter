SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `totwit` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `totwit` ;

-- -----------------------------------------------------
-- Table `totwit`.`feeds`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `totwit`.`feeds` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NULL,
  `feed_url` VARCHAR(200) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `totwit`.`posted`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `totwit`.`posted` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `feed_id` INT NULL COMMENT 'id фида\n',
  `item_link` VARCHAR(200) NULL COMMENT 'Уникальная ссылка на пост.\n',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `item_link_UNIQUE` (`item_link` ASC),
  INDEX `fk_posted_1_idx` (`feed_id` ASC),
  CONSTRAINT `fk_posted_1`
    FOREIGN KEY (`feed_id`)
    REFERENCES `totwit`.`feeds` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
