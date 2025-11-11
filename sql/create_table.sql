CREATE TABLE `glpi_assetcheckout` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `checkout_number` VARCHAR(50) NOT NULL,
  `checkout_date` TIMESTAMP NOT NULL,
  `checkout_user_id` INT UNSIGNED DEFAULT NULL,
  `receiver` VARCHAR(100) DEFAULT NULL,
  `department` VARCHAR(100) DEFAULT NULL,
  `remark` TEXT,
  `assets_json` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `checkout_number` (`checkout_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='资产出库签收记录表';
