# README


## TODO's

### Populate :white_check_mark:
* [x] Company
* [x] Address
* [x] City
* [x] Province
* [X] Contracts
* [ ] Deadline (Trigger to delete unnecessary data once the contract is finished)
* [x] Employee
* [x] Manager
* [x] Department
* [x] LineOfBusiness
* [x] Location
* [x] WorksOn
* [x] Users

### PHP :white_check_mark:
* [x] Admin
* [x] Sales Associate
* [x] Manager
* [x] Developer
* [x] Client

### Beautify Webiste (UI) :white_check_mark:

---

## Entities
* Company (**companyId**, companyName, contactFirstName, contactLastName, contactEmail, contactNumber, streetAddress, cityId)
* Address(**streetAddress**, **cityId** ,postalCode)
* City(**cityId**, name, provinceId) 
* Province(**provinceId**, name)
* Contracts(**contractId**, companyId, typeOfService, typeOfContract, startDate, initialAmount, ACV, state, satisfaction, lineOfBusiness)
* Deadline(**contractId**, **deliverableNumber**, deliveredDate, expectedDeliveryDate)
* Employee(**employeeId**, firstName, lastName, role, insurancePlan, preferedService, locationId)
* Manager(**managerId**, departmentId, locationId)
* Department(**departmentId**, name)
* LineOfBusiness(**businessTypeName**, technicalAccountManagerId)
* Location(**locationId**, name)
* WorksOn(**employeeId**, **contractId**, task, hours)
* Users(**userId**, genericId, username, password, role)

#### Gitlab doesn't support underline on markup so the primary keys are the ones that are bold
---

## Populating Database

### Employee 

| Employee Type   | Minimum Quantity |
| --------------- | ---------------- |
| Manager         | 18               |
| T.A.M.          | 10               |
| Developer       | 50               |
| Sales Associate | 10               |

### Department
* 6 Departments: Development, QA, UI, Design, Business Intelligence, Networking.

### Line of Business
* Minimum 10 unique lines of business.

### Clients
* Minimum 20 unique clients.

### Contracts
* Minimum 30 contracts (i.e.: A client can have multiple contracts).
  
### Province
* All Canadian provinces.

### City
* Minimum 20 unique cities.

### WorksOn
* Minimum 20.
  
---

## Operations

### Admin
* Everything

### Developer
* Update hours (WorksOn)
* Update preferences (Employee)
  
### Sales Associate
* Create client (Client)
* Create contract (Contracts)
* Get provinces (Province)
* Get cities (City)
* Get list of managers of a contract (Line of Business)
  
### Manager
* Assign task of employee (WorksOn)
* Get number of hours that an employee worked on a contract (WorksOn)
* Allocating employee to a contract depending of employee's preference (WorksOn)
* Remove employee from contract
  
### Client
* Post satisfaction of a contract
* Get satisfaction scores of all contracts managed by the manager of the contract (Contracts)
* Get status of contract (Contracts)

---

## Assumptions
* TODO

---
