CREATE TABLE IF NOT EXISTS `efide_customer_credit` (
  `id_credit` int(10) unsigned NOT NULL auto_increment,
  `id_customer` int(10) unsigned NOT NULL,
  `credits` int(11) NULL DEFAULT NULL,
  `id_shop` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_credit`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `efide_shopadmin` (
  `id_shopadmin` int(10) unsigned NOT NULL auto_increment,
  `id_customer` int(10) unsigned NOT NULL,
  `companyname` varchar(64) NOT NULL,
  `resource_id` varchar(64) NOT NULL,
  `access_key` varchar(32) NOT NULL,
  `account_id` varchar(32) NOT NULL,
  `enabled` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id_shopadmin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `efide_shops` (
  `id_shop` int(10) unsigned NOT NULL auto_increment,
  `id_shopadmin` int(10) unsigned NOT NULL,
  `shop_name` varchar(32) NOT NULL,
  `shop_id` varchar(32) NOT NULL,
  `access_key` varchar(32) NOT NULL,
  `enabled` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id_shop`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
