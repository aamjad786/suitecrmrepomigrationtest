CREATE TABLE `neo_customers_app` (
  `app_id` int(10) NOT NULL,
  `customer_bean_id` char(36) DEFAULT NULL,
  `is_crm_renewal` int(1) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `as_sub_stage_id` int(3) DEFAULT NULL,
  `as_stage_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1







