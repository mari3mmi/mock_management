DROP DATABASE mainproject;
CREATE DATABASE mainproject;
USE mainproject;

CREATE TABLE Users (
  userId    INTEGER UNIQUE                                                              NOT NULL AUTO_INCREMENT,
  genericId INTEGER                                                                     NOT NULL,
  username  VARCHAR(20)                                                                 NOT NULL,
  password  VARCHAR(28)                                                                 NOT NULL,
  role      ENUM ('company', 'developer', 'sales associate', 'tam', 'manager', 'admin') NOT NULL,
  PRIMARY KEY (userId)
);

CREATE TABLE Department (
  departmentId INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  name         VARCHAR(40)    NOT NULL,
  PRIMARY KEY (departmentId)
);

CREATE TABLE Province (
  provinceId INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  name       VARCHAR(50)    NOT NULL,
  PRIMARY KEY (provinceId)
);

CREATE TABLE City (
  cityId     INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  name       VARCHAR(50)    NOT NULL,
  provinceId INTEGER REFERENCES Province (provinceId),
  PRIMARY KEY (cityId)
);

CREATE TABLE Location (
  locationId INTEGER UNIQUE                            NOT NULL AUTO_INCREMENT,
  name       ENUM ('vancouver', 'montreal', 'toronto') NOT NULL,
  PRIMARY KEY (locationID)
);

CREATE TABLE Address (
  streetAddress VARCHAR(100)   NOT NULL,
  cityId        INTEGER REFERENCES City(cityId),
  postalCode    VARCHAR(6)     NOT NULL,
  PRIMARY KEY (streetAddress, cityId)
);

CREATE TABLE Employee (
  employeeId      INTEGER UNIQUE                                                   NOT NULL AUTO_INCREMENT,
  firstName       VARCHAR(50)                                                      NOT NULL,
  lastName        VARCHAR(50)                                                      NOT NULL,
  role            ENUM ('developer', 'sales associate', 'tam', 'manager', 'admin') NOT NULL,
  locationId      INTEGER REFERENCES Location (locationId),
  insurancePlan   ENUM ('premium', 'silver', 'normal')                             NOT NULL,
  preferedService ENUM ('gold', 'premium', 'silver', 'diamond', 'none')            NOT NULL,
  PRIMARY KEY (employeeId)
);

CREATE TABLE Manager (
  managerId    INTEGER REFERENCES Employee (employeeId),
  departmentId INTEGER REFERENCES Department (departmentId),
  locationId   INTEGER REFERENCES Location (locationId),
  PRIMARY KEY (managerId)
);


CREATE TABLE LineOfBusiness (
  lineOfBusinessId INTEGER UNIQUE                                                   NOT NULL AUTO_INCREMENT,
  businessTypeName VARCHAR(50)                                                      NOT NULL,
  tamId            INTEGER REFERENCES Employee (employeeId),
  PRIMARY KEY (lineOfBusinessId)
);

CREATE TABLE Company (
  companyId        INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  companyName      VARCHAR(50)    NOT NULL,
  contactfirstName VARCHAR(50)    NOT NULL,
  contactLastName  VARCHAR(50)    NOT NULL,
  contactEmail     VARCHAR(50)    NOT NULL,
  contactNumber    BIGINT         NOT NULL,
  streetAddress    VARCHAR(50)    REFERENCES Address(streetAddress),
  cityId           INTEGER(6)    REFERENCES Address(postalCode),
  PRIMARY KEY (companyId)
);

CREATE TABLE Contract (
  contractId       INTEGER UNIQUE                                NOT NULL    AUTO_INCREMENT,
  companyId        INTEGER REFERENCES Company (companyId),
  initialAmount    REAL                                          NOT NULL,
  ACV              REAL                                          NOT NULL,
  typeOfService    ENUM ('on-premises', 'cloud')                 NOT NULL,
  typeOfContract   ENUM ('gold', 'premium', 'silver', 'diamond') NOT NULL,
  LineOfBusinessId INTEGER REFERENCES LineOfBusiness (lineOfBusinessId),
  startDate        VARCHAR(40)                                   NOT NULL,
  state            ENUM ('active', 'expired')                                DEFAULT 'active',
  satisfaction     TINYINT                                                   DEFAULT 0,
  PRIMARY KEY (contractId)
);

CREATE TABLE WorksOn (
  employeeId INTEGER REFERENCES Employee (employeeId),
  contractId INTEGER REFERENCES Contract (contractId),
  task       ENUM ('Set up infrastructure for client', 'Provisioning of resources', 'Assigning tasks to resources', 'Allocating a dedicated point of contact', 'Development'),
  hours      REAL NOT NULL DEFAULT 0,
  PRIMARY KEY (employeeId, contractId)
);

CREATE TABLE Deadline (
  contractId           INTEGER REFERENCES Contract (contractId),
  deliverableNumber    ENUM ('first', 'second', 'third', 'final'),
  expectedDeliveryDate VARCHAR(40) NOT NULL,
  deliveredDate        VARCHAR(40),
  PRIMARY KEY (contractId, deliverableNumber)
);

SHOW DATABASES;
SHOW TABLES;

-- Triggers

CREATE TRIGGER employee_add_user
  AFTER INSERT
  ON Employee
  FOR EACH ROW
  BEGIN
    INSERT INTO Users (genericId, username, password, role)
    VALUE (NEW.employeeId, CONCAT(left(NEW.firstName, 1), '_', New.lastName), '1234', NEW.role);
  END;

CREATE TRIGGER company_add_user
  AFTER INSERT
  ON Company
  FOR EACH ROW
  BEGIN
    INSERT INTO Users (genericId, username, password, role) VALUE (NEW.companyId, NEW.companyName, '1234', 'company');
  END;

CREATE TRIGGER project_bookkeeping
  AFTER INSERT
  ON Contract
  FOR EACH ROW
  BEGIN
    IF NEW.TypeOfContract = 'silver'
    THEN
      INSERT INTO Deadline (contractId, deliverableNumber, expectedDeliveryDate)
      VALUES (NEW.contractId, 'first', ADDDATE(NEW.startDate, 6)),
             (NEW.contractId, 'second', ADDDATE(NEW.startDate, 15)),
             (NEW.contractId, 'third', ADDDATE(NEW.startDate, 20)),
             (NEW.contractId, 'final', ADDDATE(NEW.startDate, 28));
    ELSEIF NEW.TypeOfContract = 'gold'
      THEN
        INSERT INTO Deadline (contractId, deliverableNumber, expectedDeliveryDate)
        VALUES (NEW.contractId, 'first', ADDDATE(NEW.startDate, 9)),
               (NEW.contractId, 'second', ADDDATE(NEW.startDate, 14)),
               (NEW.contractId, 'final', ADDDATE(NEW.startDate, 20));
    ELSEIF NEW.TypeOfContract = 'diamond'
      THEN
        INSERT INTO Deadline (contractId, deliverableNumber, expectedDeliveryDate)
        VALUES (NEW.contractId, 'first', ADDDATE(NEW.startDate, 7)),
               (NEW.contractId, 'second', ADDDATE(NEW.startDate, 11)),
               (NEW.contractId, 'final', ADDDATE(NEW.startDate, 18));
    ELSEIF NEW.TypeOfContract = 'premium'
      THEN
        INSERT INTO Deadline (contractId, deliverableNumber, expectedDeliveryDate)
        VALUES (NEW.contractId, 'first', ADDDATE(NEW.startDate, 4)),
               (NEW.contractId, 'second', ADDDATE(NEW.startDate, 5)),
               (NEW.contractId, 'final', ADDDATE(NEW.startDate, 10));
    END IF;
  END;