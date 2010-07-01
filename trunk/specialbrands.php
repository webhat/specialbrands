<?php
/*
include(dirname(__FILE__).'/config/config.inc.php');

include_once(dirname(__FILE__).'/init.php');
                        
//will be initialized bellow...
if(intval(Configuration::get('PS_REWRITING_SETTINGS')) === 1)                 
        $rewrited_url = null;  

Db::getInstance()->getRow('');
Db::getInstance()->Execute('');
*/

class efide extends Module {
 	public function __construct() {
		$this->name = 'efide-spm';
		$this->tab = 'Tools';

		parent::__construct();

		$this->displayName = $this->l('eFide CreditModule');
		$this->description = $this->l('Adds communication between SPM and PrestaShop');
		$this->confirmUninstall = $this->l('Are you sure you want remove this module?');

		$this->version = '1.4';
		$this->error = false;
		$this->valid = false;

	}

 	public function install() {
		if (parent::install() == false OR $this->registerHook('hookFooter') == false)
			return false;

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'efide_customer_credit` (
		  `id_credit` int(10) unsigned NOT NULL auto_increment,
		  `id_customer` int(10) unsigned NOT NULL,
		  `credits` int(11) NULL DEFAULT NULL,
		  `id_shop` int(11) NULL DEFAULT NULL,
		  PRIMARY KEY (`id_credit`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'efide_shopadmin` (
		  `id_shopadmin` int(10) unsigned NOT NULL auto_increment,
		  `id_customer` int(10) unsigned NOT NULL,
		  `companyname` varchar(64) NOT NULL,
		  `resource_id` varchar(64) NOT NULL,
		  `access_key` varchar(32) NOT NULL,
		  `account_id` varchar(32) NOT NULL,
		  `enabled` int(10) NULL DEFAULT NULL,
		  PRIMARY KEY (`id_shopadmin`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'efide_shops` (
		  `id_shop` int(10) unsigned NOT NULL auto_increment,
		  `id_shopadmin` int(10) unsigned NOT NULL,
		  `shop_name` varchar(32) NOT NULL,
		  `shop_id` varchar(32) NOT NULL,
		  `access_key` varchar(32) NOT NULL,
		  `enabled` int(10) NULL DEFAULT NULL,
		  PRIMARY KEY (`id_shop`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		');

	}

 	public function uninstall() {
		if (!parent::uninstall())
			return false;
		Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'efide_customer_credit');
		Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'efide_shopadmin');
		return Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'efide_shops');

	}

	public function getContent() {
		$this->_html = '<h2>'.$this->displayName.'</h2>';
	}

	private function _displayForm() {
	}
}

?>
