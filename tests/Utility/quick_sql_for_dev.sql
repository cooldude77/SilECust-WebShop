-- Adminer 4.8.1 MySQL 10.6.18-MariaDB-0ubuntu0.22.04.1 dump
-- You can use this to populate dev master data for quickly starting the work
-- This is to be run when database has been created and migrations done
-- This is sample data and should never be run on production
-- You can modify  this data for your own needs

SET NAMES utf8mb4;

TRUNCATE `category`;
INSERT INTO `category` (`id`, `parent_id`, `name`, `description`) VALUES
    (1,	NULL,	'Clothes',	'Clothes And Apparels');


TRUNCATE `city`;
INSERT INTO `city` (`id`, `state_id`, `code`, `name`) VALUES
    (1,	1,	'BLR',	'Bangalore');

TRUNCATE `country`;
INSERT INTO `country` (`id`, `code`, `name`) VALUES
    (1,	'IN',	'India');

TRUNCATE `currency`;
INSERT INTO `currency` (`id`, `country_id`, `description`, `code`, `symbol`) VALUES
    (1,	1,	'Indian Rupees',	'INR',	'₹');

TRUNCATE `pin_code`;
INSERT INTO `pin_code` (`id`, `city_id`, `pin_code`) VALUES
    (1,	1,	'560001');

TRUNCATE `price_product_base`;
INSERT INTO `price_product_base` (`id`, `product_id`, `currency_id`, `price`) VALUES
    (1,	1,	1,	100);

TRUNCATE `price_product_discount`;
INSERT INTO `price_product_discount` (`id`, `product_id`, `currency_id`, `value`) VALUES
    (1,	1,	1,	10);

TRUNCATE `price_product_tax`;
INSERT INTO `price_product_tax` (`id`, `product_id`, `tax_slab_id`) VALUES
    (1,	1,	1);

TRUNCATE `product`;
INSERT INTO `product` (`id`, `name`, `description`, `category_id`, `type_id`, `is_active`, `long_description`) VALUES
    (1,	'T_SHIRT_PLAIN',	'T Shirt Plain',	1,	NULL,	1,	'Plain T Shirt');


TRUNCATE `state`;
INSERT INTO `state` (`id`, `country_id`, `code`, `name`) VALUES
    (1,	1,	'KA',	'Karnataka');

TRUNCATE `tax_slab`;
INSERT INTO `tax_slab` (`id`, `name`, `description`, `rate_of_tax`) VALUES
    (1,	'Slab A',	'Tax Slab A',	10);


INSERT INTO `price_product_tax` (`id`, `product_id`, `tax_slab_id`) VALUES
    (1,	1,	1);
-- 2024-07-18 08:02:08
