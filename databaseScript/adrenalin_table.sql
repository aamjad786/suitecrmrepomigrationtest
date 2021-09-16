CREATE TABLE `adrenalin_user_info` 
  ( `employee_code` varchar(10) NOT NULL, 
	`company_name` varchar(45) DEFAULT NULL, 
	`mail_id` varchar(45) DEFAULT NULL, 
	`reporting_manager` varchar(45) DEFAULT NULL, 
	`designation_name` varchar(45) DEFAULT NULL, 
	`modified_on` datetime DEFAULT NULL, 
	`department_name` varchar(45) DEFAULT NULL, 
	PRIMARY KEY (`employee_code`) );