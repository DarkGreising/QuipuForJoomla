SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `#__quipu_bank_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` char(10) NOT NULL,
  `account_no` char(23) NOT NULL,
  `balance` float(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_bank_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bank_account_id` int(11) unsigned NOT NULL,
  `activity_date` date NOT NULL,
  `value_date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `balance` float(10,2) NOT NULL,
  `ref` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_account_id` (`bank_account_id`),
  KEY `bank_account_id_2` (`bank_account_id`,`value_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` char(20) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL,
  `vatno` varchar(20) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(255) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
ALTER TABLE  `#__quipu_config` ADD  `sync` TEXT NOT NULL;
ALTER TABLE  `#__quipu_config` ADD  `currency_symbol` CHAR( 10 ) NOT NULL;

CREATE TABLE `#__quipu_customer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `memo` text NOT NULL,
  `vatno` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT '',
  `default_due_days` int(11) NOT NULL DEFAULT '30',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_detail_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `item_id` int(10) unsigned NOT NULL,
  `invoice_id` int(11) unsigned NOT NULL DEFAULT '0',
  `description` varchar(50) NOT NULL,
  `units` int(11) NOT NULL,
  `unit_price` float(10,2) NOT NULL,
  `tax` float(10,2) NOT NULL,
  `discount` float(10,2) NOT NULL,
  `base` float(10,2) NOT NULL,
  `total` float(10,2) NOT NULL DEFAULT '0.00',
  `cost` float(10,2) NOT NULL,
  `profit_wotax` float(10,2) NOT NULL,
  `memo` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_invoice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL,
  `customer_id` int(11) unsigned NOT NULL,
  `bankaccount_id` int(10) unsigned NOT NULL DEFAULT '0',
  `customer` varchar(100) NOT NULL,
  `vatno` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `payment_method` char(20) NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `customer_reference` varchar(20) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `payment_date` date NOT NULL,
  `base` float(10,2) NOT NULL,
  `total_tax` float(10,2) NOT NULL,
  `retentions` float(10,2) NOT NULL,
  `total_retentions` float(10,2) NOT NULL,
  `total` float(10,2) NOT NULL,
  `status` char(10) NOT NULL,
  `memo` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `customer_id` (`customer_id`),
  KEY `bankaccount_id` (`bankaccount_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_invoice_bank_activity_xref` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) unsigned NOT NULL,
  `activity_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `activity_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `tax_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `cost_price_wotax` float NOT NULL,
  `last_sell_price_wotax` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `tax_id` (`tax_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='sold items';

CREATE TABLE `#__quipu_item_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='categories of sold items';

CREATE TABLE `#__quipu_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) unsigned NOT NULL DEFAULT '0',
  `invoice_id` int(11) unsigned NOT NULL DEFAULT '0',
  `order_date` date NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `customer_reference` varchar(20) NOT NULL,
  `external_reference` varchar(20) NOT NULL,
  `base` float(10,2) NOT NULL,
  `total_tax` float(10,2) NOT NULL,
  `total` float(10,2) NOT NULL,
  `status` char(10) NOT NULL DEFAULT '1001',
  `memo` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `invoce_id` (`invoice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_tax` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `factor` float(3,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `#__quipu_sequence` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL,
  `next_value` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`next_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `#__quipu_bank_activity`
  ADD CONSTRAINT `#__quipu_bank_activity_ibfk_1` FOREIGN KEY (`bank_account_id`) REFERENCES `#__quipu_bank_account` (`id`) ON DELETE CASCADE;

ALTER TABLE `#__quipu_invoice`
  ADD CONSTRAINT `#__quipu_invoice_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `#__quipu_order` (`id`),
  ADD CONSTRAINT `#__quipu_invoice_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `#__quipu_customer` (`id`);

ALTER TABLE `#__quipu_invoice_bank_activity_xref`
  ADD CONSTRAINT `#__quipu_invoice_bank_activity_xref_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `#__quipu_bank_activity` (`id`),
  ADD CONSTRAINT `#__quipu_invoice_bank_activity_xref_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `#__quipu_invoice` (`id`);

ALTER TABLE `#__quipu_item`
  ADD CONSTRAINT `#__quipu_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `#__quipu_item_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `#__quipu_item_ibfk_2` FOREIGN KEY (`tax_id`) REFERENCES `#__quipu_tax` (`id`);

ALTER TABLE `#__quipu_order`
  ADD CONSTRAINT `#__quipu_order_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `#__quipu_customer` (`id`);


-- 1.0.9
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


SET FOREIGN_KEY_CHECKS=1;
