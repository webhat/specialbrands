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

include_once(dirname(__FILE__).'/settings.php');

ini_set('soap.wsdl_cache_ttl', 1);

class specialbrands extends Module {
 	public function __construct() {
		$this->name = 'specialbrands';
		$this->tab = 'Tools';

		parent::__construct();

		$this->displayName = $this->l('eFide CreditModule');
		$this->description = $this->l('Adds communication between SPM and PrestaShop');
		$this->confirmUninstall = $this->l('Are you sure you want remove this module?');

		$this->version = '0.9';
		$this->error = false;
		$this->valid = false;

	}

 	public function install() {
		if (parent::install() == false OR $this->registerHook('hookFooter') == false)
			return false;

// TODO: Check Registered Access Key

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

	public function displayShopAdminForm($shop_account_id) {
		global $smarty;
		$error = false;
		$confirm = false;

		if(isset($_POST["submit"])) {
			/* Fields verifications */
			if (
					empty($_POST['c'])
					OR empty($_POST['fn'])
					OR empty($_POST['ln'])
					OR empty($_POST['mail'])
					OR empty($_POST['resource_id'])
					OR empty($_POST['enable'])
					OR empty($_POST['reset'])
					)
				$error = $this->l('You must fill all fields.');
			elseif (!Validate::isEmail($_POST['email']))
				$error = $this->l('Your email is invalid.');
			else {
				$c						= $_POST['c'];
				$fn						= $_POST['fn'];
				$ln						= $_POST['ln'];
				$mail					= $_POST['mail'];
				$resource_id	= $_POST['resource_id'];
				$account_id		= $_POST['account_id'];
				$enable				= $_POST['enable'];
				$reset				= $_POST['reset'];
			}
		}

		if(!isset($_POST["account_id"])
				OR empty($_POST["account_id"])) {
		} else {
		}

		$smarty->assign(array(
			'c' => $c,
			'fn' => $fn,
			'ln' => $ln,
			'mail' => $mail,
			'resource_id' => $resource_id,
			'account_id' => $account_id,
			'enable' => $enable,
			'reset' => $reset,
			'errors' => $error,
			'shop' => $shop
		));


		return $this->display(__FILE__, 'specialbrands.tpl');
	}
}

?>
