CREATE TABLE `#__quipu_supplier` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `memo` text NOT NULL,
  `vatno` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,  
  `default_due_days` int(11) NOT NULL DEFAULT '30',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_purchase_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) unsigned NOT NULL DEFAULT '0',
  `bankaccount_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_date` date NOT NULL,
  `payment_date` date NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `supplier_reference` varchar(20) NOT NULL,
  `base` float(10,2) NOT NULL,
  `total_tax` float(10,2) NOT NULL,
  `total` float(10,2) NOT NULL,
  `status` char(10) NOT NULL DEFAULT '1001',
  `memo` text NOT NULL,
  `invoice_file` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `#__quipu_detail_item` ADD `purchaseorder_id` int(11) unsigned NOT NULL DEFAULT '0' AFTER `invoice_id`;

CREATE TABLE `#__quipu_purchaseorder_bank_activity_xref` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchaseorder_id` int(10) unsigned NOT NULL,
  `activity_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchaseorder_id` (`purchaseorder_id`),
  KEY `activity_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `#__quipu_invoice` ADD  `rectification_of_number` VARCHAR( 100 ) NOT NULL AFTER  `invoice_number`;