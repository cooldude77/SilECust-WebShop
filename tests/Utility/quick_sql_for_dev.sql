-- Adminer 4.8.1 MySQL 10.6.18-MariaDB-0ubuntu0.22.04.1 dump
-- You can use this to populate dev master data for quickly starting the work
-- This is to be run when database has been created and migrations done
-- This is sample data and should never be run on production
-- You can modify  this data for your own needs

SET NAMES utf8mb4;

INSERT INTO `country` (`id`, `code`, `name`)
VALUES (1, 'IN', 'India');

INSERT INTO `state` (`id`, `country_id`, `code`, `name`)
VALUES (1, 1, 'KA', 'Karnataka');

INSERT INTO `city` (`id`, `state_id`, `code`, `name`)
VALUES (1, 1, 'BLR', 'Bangalore');

INSERT INTO `postal_code` (`id`, `city_id`, `postal_code`, `name`)
VALUES (1, 1, '560001', 'Bangalore G.p.o.');

INSERT INTO `tax_slab` (`id`, `country_id`, `name`, `description`, `rate_of_tax`)
VALUES (1, 1, 'Slab A', 'Tax Slab A', 10);

INSERT INTO `currency` (`id`, `country_id`, `description`, `code`, `symbol`)
VALUES (1, 1, 'Indian Rupees', 'INR', '₹');


INSERT INTO `category` (`id`, `parent_id`, `name`, `description`, `path`)
VALUES (1, NULL, 'CLOTHES', 'Clothes And Apparels', '/1');
INSERT INTO `category` (`id`, `parent_id`, `name`, `description`, `path`)
VALUES (2, NULL, 'ELECTRONICS', 'Electronics', '/2');
INSERT INTO `category` (`id`, `parent_id`, `name`, `description`, `path`)
VALUES (3, 1, 'SHIRTS', 'shirts', '/1/3');
INSERT INTO `category` (`id`, `parent_id`, `name`, `description`, `path`)
VALUES (4, 2, 'LAPTOPS', 'Laptops', '/2/4');
INSERT INTO `category` (`id`, `parent_id`, `name`, `description`, `path`)
VALUES (5, 4, 'LAPTOP', 'Laptop', '/2/4/5');
INSERT INTO `category` (`id`, `parent_id`, `name`, `description`, `path`)
VALUES (6, 1, 'TROUSER', 'Trouser', '/1/6');

INSERT INTO `product` (`id`, `name`, `description`, `category_id`, `type_id`, `is_active`, `long_description`)
VALUES (1, 'T_SHIRT_PLAIN', 'T Shirt Plain', 3, NULL, 1, 'Plain T Shirt');
INSERT INTO `product` (`id`, `name`, `description`, `category_id`, `type_id`, `is_active`, `long_description`)
VALUES (2, 'T_SHIRT_CHECKS', 'T Shirt Checks', 3, NULL, 1, 'Check T Shirt');
INSERT INTO `product` (`id`, `name`, `description`, `category_id`, `type_id`, `is_active`, `long_description`)
VALUES (3, 'TROUSER', 'Trouser Top Quality', 6, NULL, 1, 'Trouser');

INSERT INTO `price_product_base` (`id`, `product_id`, `currency_id`, `price`)
VALUES (1, 1, 1, 100);
INSERT INTO `price_product_discount` (`id`, `product_id`, `currency_id`, `value`)
VALUES (1, 1, 1, 10);
INSERT INTO `price_product_tax` (`id`, `product_id`, `tax_slab_id`)
VALUES (1, 1, 1);

INSERT INTO `price_product_base` (`id`, `product_id`, `currency_id`, `price`)
VALUES (2, 2, 1, 500);
INSERT INTO `price_product_discount` (`id`, `product_id`, `currency_id`, `value`)
VALUES (2, 2, 1, 5);
INSERT INTO `price_product_tax` (`id`, `product_id`, `tax_slab_id`)
VALUES (2, 2, 1);

INSERT INTO `price_product_base` (`id`, `product_id`, `currency_id`, `price`)
VALUES (3, 3, 1, 500);
INSERT INTO `price_product_discount` (`id`, `product_id`, `currency_id`, `value`)
VALUES (3, 3, 1, 5);
INSERT INTO `price_product_tax` (`id`, `product_id`, `tax_slab_id`)
VALUES (3, 3, 1);

-- 2024-07-18 08:02:08
