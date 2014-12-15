ALTER TABLE  `#__quipu_customer` CHANGE  `user_id`  `user_id` INT( 10 ) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE  `#__quipu_detail_item` CHANGE  `units`  `units` INT( 11 ) NOT NULL;
ALTER TABLE  `#__quipu_customer` CHANGE  `payment_method`  `payment_method` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  ' ';
ALTER TABLE `#__quipu_customer` DROP `zip`,DROP `town`,DROP `country`;
ALTER TABLE `#__quipu_order` DROP `delivery`;
ALTER TABLE `#__quipu_invoice` DROP `delivery`;