

-- -----------------------------------------------------
-- Table `CompanyContacts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CompanyContacts` ;

CREATE  TABLE IF NOT EXISTS `CompanyContacts` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(245) NOT NULL ,
  `createddate` DATETIME NULL  ,
  `createdby` INT NULL ,
  `status` INT NULL ,
  `companyid` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CompanyBrands`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CompanyBrands` ;

CREATE  TABLE IF NOT EXISTS `CompanyBrands` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(245) NULL ,
  `createdby` INT NULL ,
  `createddate` DATETIME NULL  ,
  `status` INT NULL ,
  `companyid` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ContactBrandsMapping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ContactBrandsMapping` ;

CREATE  TABLE IF NOT EXISTS `ContactBrandsMapping` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `contactid` INT NULL ,
  `brandid` INT NULL ,
  `createddate` DATETIME NULL ,
  `status` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `companybrands_brand_fk1`
    FOREIGN KEY (`brandid` )
    REFERENCES `CompanyBrands` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `companybrands_contact_fk1`
    FOREIGN KEY (`contactid` )
    REFERENCES `CompanyContacts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `companybrands_brand_fk1_idx` ON `ContactBrandsMapping` (`brandid` ASC) ;

CREATE INDEX `companybrands_contact_fk1_idx` ON `ContactBrandsMapping` (`contactid` ASC) ;


-- -----------------------------------------------------
-- Table `CompanyStatuses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CompanyStatuses` ;

CREATE  TABLE IF NOT EXISTS `CompanyStatuses` (
  `id` INT NOT NULL ,
  `name` VARCHAR(245) NULL ,
  `createddate` DATETIME NULL ,
  `createdby` INT NULL ,
  `status` INT NULL ,
  `companyid` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CompanyLeads`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CompanyLeads` ;

CREATE  TABLE IF NOT EXISTS `CompanyLeads` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `contactid` INT NOT NULL ,
  `brandid` INT NOT NULL ,
  `countries` VARCHAR(545) NULL ,
  `cities` VARCHAR(545) NULL ,
  `tags` VARCHAR(545) NULL ,
  `category` VARCHAR(245) NULL ,
  `assignedto` INT NULL ,
  `status` INT NULL ,
  `description` MEDIUMTEXT NULL ,
  `companyid` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `lead_brand_fk1`
    FOREIGN KEY (`brandid` )
    REFERENCES `CompanyBrands` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `lead_contact_fk1`
    FOREIGN KEY (`contactid` )
    REFERENCES `CompanyContacts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `status`
    FOREIGN KEY (`status` )
    REFERENCES `CompanyStatuses` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `lead_brand_fk1_idx` ON `CompanyLeads` (`brandid` ASC) ;

CREATE INDEX `lead_contact_fk1_idx` ON `CompanyLeads` (`contactid` ASC) ;

CREATE INDEX `status_idx` ON `CompanyLeads` (`status` ASC) ;


-- -----------------------------------------------------
-- Table `LeadCountryMapping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LeadCountryMapping` ;

CREATE  TABLE IF NOT EXISTS `LeadCountryMapping` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `leadid` INT NOT NULL ,
  `countryid` INT NULL ,
  `createddate` DATETIME NULL ,
  `createdby` INT NULL ,
  `status` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `leadcountry_lead_fk1`
    FOREIGN KEY (`leadid` )
    REFERENCES `CompanyLeads` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `leadcountry_lead_fk1_idx` ON `LeadCountryMapping` (`leadid` ASC) ;


-- -----------------------------------------------------
-- Table `LeadCityMapping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LeadCityMapping` ;

CREATE  TABLE IF NOT EXISTS `LeadCityMapping` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `leadid` INT NULL ,
  `cityid` INT NULL ,
  `createddate` DATETIME NULL ,
  `createdby` INT NULL ,
  `status` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `leadcity_lead_fk1`
    FOREIGN KEY (`leadid` )
    REFERENCES `CompanyLeads` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `leadcity_lead_fk1_idx` ON `LeadCityMapping` (`leadid` ASC) ;


-- -----------------------------------------------------
-- Table `LeadCountryBugdetMapping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LeadCountryBugdetMapping` ;

CREATE  TABLE IF NOT EXISTS `LeadCountryBugdetMapping` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `leadcountryid` INT NULL ,
  `currencycode` VARCHAR(45) NULL ,
  `amount` DOUBLE NULL ,
  `createddate` DATETIME NULL ,
  `createdby` INT NULL ,
  `status` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `leadbudget_leadcountry_fk1`
    FOREIGN KEY (`leadcountryid` )
    REFERENCES `LeadCountryMapping` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `leadbudget_leadcountry_fk1_idx` ON `LeadCountryBugdetMapping` (`leadcountryid` ASC) ;


-- -----------------------------------------------------
-- Table `CompanyTags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CompanyTags` ;

CREATE  TABLE IF NOT EXISTS `CompanyTags` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(245) NULL ,
  `createdby` INT NULL ,
  `createddate` DATETIME NULL ,
  `status` INT NULL ,
  `companyid` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LeadTagMapping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LeadTagMapping` ;

CREATE  TABLE IF NOT EXISTS `LeadTagMapping` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `leadid` INT NULL ,
  `tagid` INT NULL ,
  `createddate` DATETIME NULL ,
  `createdby` INT NULL ,
  `status` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `leadtag_tag_fk1`
    FOREIGN KEY (`tagid` )
    REFERENCES `CompanyTags` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `leadtag_lead_fk1`
    FOREIGN KEY (`leadid` )
    REFERENCES `CompanyLeads` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `leadtag_tag_fk1_idx` ON `LeadTagMapping` (`tagid` ASC) ;

CREATE INDEX `leadtag_lead_fk1_idx` ON `LeadTagMapping` (`leadid` ASC) ;


-- -----------------------------------------------------
-- Table `LeadAttachments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LeadAttachments` ;

CREATE  TABLE IF NOT EXISTS `LeadAttachments` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `leadid` INT NULL ,
  `attachmenturl` MEDIUMTEXT NULL ,
  `status` INT NULL ,
  `createddate` DATETIME NULL ,
  `createdby` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `leadattachment_lead_fk1`
    FOREIGN KEY (`leadid` )
    REFERENCES `CompanyLeads` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `leadattachment_lead_fk1_idx` ON `LeadAttachments` (`leadid` ASC) ;


-- -----------------------------------------------------
-- Table `LeadUpdateLog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `LeadUpdateLog` ;

CREATE  TABLE IF NOT EXISTS `LeadUpdateLog` (
  `id` INT NOT NULL ,
  `leadid` INT NULL ,
  `oldvalue` MEDIUMTEXT NULL ,
  `newvalue` MEDIUMTEXT NULL ,
  `actionid` INT NULL ,
  `createdby` INT NULL ,
  `createddate` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `updatelog_lead_fk1`
    FOREIGN KEY (`leadid` )
    REFERENCES `CompanyLeads` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `updatelog_lead_fk1_idx` ON `LeadUpdateLog` (`leadid` ASC) ;


-- -----------------------------------------------------
-- Table `CompanyCategories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CompanyCategories` ;

CREATE  TABLE IF NOT EXISTS `CompanyCategories` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(245) NULL ,
  `createddate` DATETIME NULL ,
  `createdby` INT NULL ,
  `status` INT NULL ,
  `companyid` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;
