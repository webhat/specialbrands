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

require_once(_PS_MODULE_DIR_ .'specialbrands/settings.php');
require_once(_PS_MODULE_DIR_ .'specialbrands/WSDLConnect.php');

ini_set('soap.wsdl_cache_ttl', 1);

class specialbrands extends Module {
	private static $__conn = NULL; 
	private $errors = array();

 	public function __construct() {
		$this->name = 'specialbrands';
		$this->tab = 'Payment';

		parent::__construct();

		$this->displayName = $this->l('eFide CreditModule');
		$this->description = $this->l('Adds communication between SPM and PrestaShop');
		$this->confirmUninstall = $this->l('Are you sure you want remove this module?');

		$this->version = '0.9';
		$this->error = false;
		$this->valid = false;
		$this->active = true;

		//if(!is_array(self::errors))
		//	self::errors = array();

		if (strlen(Configuration::get('EFIDE_ACCESS_KEY')) == 0)
			$this->warning = $this->l('You must set the Access Key to access the SPM');
		// Configuration::deleteByName('GCHECKOUT_MERCHANT_ID')
		// Configuration::updateValue('GCHECKOUT_MERCHANT_ID', $merchant_id);

		/*
		if(sizeof($this->$errors))
			$this->error = implode("<br/>",$this->$errors);
			*/

		if(!isset(self::$__conn) AND !empty(self::$__conn) AND (self::$__conn != NULL)) {
			echo "Reconnected WSDL<br/>";
			$this->__conn = new WSDLConnect();
		}
	}

	public function __destruct() {
		//parent::__destruct();
	}

 	public function install() {
		if (parent::install() == false)
			return false;

			if(!$this->registerHook('myAccountBlock')) {
				echo "$this->registerHook('myAccountBlock');";
				return false;
			}
			if(!$this->registerHook('customerAccount')) {
				echo "$this->registerHook('customerAccount');";
				return false;
			}
			if(!$this->registerHook('payment')) {
				echo "$this->registerHook('payment');";
				return false;
			}
			if(!$this->registerHook('backBeforePayment')) {
				echo "$this->registerHook('backBeforePayment');";
				return false;
			}
			if(!$this->registerHook('paymentConfirm')) {
				echo "$this->registerHook('paymentConfirm');";
				return false;
			}
			if(!$this->registerHook('authentication')) {
				echo "$this->registerHook('authentication');";
				return false;
			}
			if(!$this->registerHook('orderConfirmation')) {
				echo "$this->registerHook('orderConfirmation');";
				return false;
			}
			if(!$this->registerHook('createAccount')) {
				echo "$this->registerHook('createAccount');";
				return false;
			}
			if(!$this->registerHook('paymentReturn')) {
				echo "$this->registerHook('paymentReturn');";
				return false;
			}

// TODO: Check Registered Access Key

		if(!Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'efide_customer_credit` (
		  `id_credit` int(10) unsigned NOT NULL auto_increment,
		  `id_customer` int(10) unsigned NOT NULL,
		  `credits` int(11) NULL DEFAULT NULL,
		  `id_shop` int(11) NULL DEFAULT NULL,
		  PRIMARY KEY (`id_credit`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		')) {
			$this->errors[] = $this->l("First CREATE failed");
		 	return false;
		}

		if(!Db::getInstance()->Execute('
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
		')) {
			$this->errors[] = $this->l("Second CREATE failed");
		 	return false;
		}

		if(!Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'efide_shops` (
		  `id_shop` int(10) unsigned NOT NULL auto_increment,
		  `id_shopadmin` int(10) unsigned NOT NULL,
		  `shop_name` varchar(32) NOT NULL,
		  `shop_id` varchar(32) NOT NULL,
		  `access_key` varchar(32) NOT NULL,
		  `enabled` int(10) NULL DEFAULT NULL,
		  PRIMARY KEY (`id_shop`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		')) {
			$this->errors[] = $this->l("Third CREATE failed");
		 	return false;
		}

		if(!Db::getInstance()->Execute('
		CREATE TABLE `'._DB_PREFIX_.'efide_carts` (
				  `secure_key` varchar(64) NOT NULL,
					  UNIQUE KEY `secure_key` (`secure_key`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		')) {
			$this->errors[] = $this->l("Fourth CREATE failed");
		 	return false;
		}

		return true;
	}

 	public function uninstall() {
		if (!parent::uninstall())
			return false;


		if(!Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'efide_customer_credit')) {
			$this->errors[] = $this->l("First DROP failed");
		 	return false;
		}
		if(!Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'efide_shopadmin')) {
			$this->errors[] = $this->l("Second DROP failed");
		 	return false;
		}
		if(!Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'efide_shops')) {
			$this->errors[] = $this->l("Third DROP failed");
		 	return false;
		}
		if(!Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'efide_carts')) {
			$this->errors[] = $this->l("Fourth DROP failed");
		 	return false;
		}
		return true;
	}

	public function getContent() {
		$this->_html = '<h2>'.$this->displayName.'</h2>';

		if (!empty($_POST))
			$this->_postProcess();

		return $this->_displayForm();
	}

	private function _postProcess() {
		return (Configuration::updateValue('EFIDE_ACCESS_KEY', pSQL(Tools::getValue('efide_access_key'))));
	}

	private function _displayForm() {
		$conf = Configuration::getMultiple(array("EFIDE_ACCESS_KEY"));

		$this->_html .=
			'<br /><fieldset><legend>'.$this->l('Configuration').'
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<label for="serial">'.$this->l('Access Key').' :</label>
			<div class="margin-form">
			<input type="text" name="efide_access_key" value="'.$conf['EFIDE_ACCESS_KEY'].'" />
			</div>
			<input type="submit" name="submit" class="button" value="'.$this->l('Update').'" />
			</form></fieldset>';

		return $this->_html;
	}

	public static function displayShopAdminForm($shop_account_id) {
		global $smarty, $cookie;
		$error = false;
		$confirm = false;

		// TODO: Add login check
		// if($cookie->isLogged())

		// if (Tools::isSubmit('submitGoogleCheckout'))

		if(isset($_POST["submit"])) {
			/* Fields verifications */
			if ( empty($_POST['c'])
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
				$account_id		= empty($_POST['account_id'])?$_POST['account_id']:$shop_account_id;
				$enable				= $_POST['enable'];
				$reset				= $_POST['reset'];

				$this->__conn->ShopAdminAccount($c,$fn,$ln,$mail,$account_id,$resource_id,$enable,$reset);

				$smarty->assign(array(
					'c' => $c,
					'fn' => $fn,
					'ln' => $ln,
					'mail' => $mail,
					'resource_id' => $resource_id,
					'account_id' => $account_id,
					'enable' => $enable,
					'reset' => $reset,
					'error' => $error,
					'shop' => $shop
				));

			}
		}



		return Module::display(__FILE__, 'specialbrands.tpl');
	}

	/* Hook display on customer account page */
	public function hookCustomerAccount($params) {
		global $smarty;
//		echo "hookCustomerAccount". var_export($params,true);
// return "hookCustomerAccount";//
		return $this->display(__FILE__, 'my-account.tpl');
	}

	public function hookMyAccountBlock($params) {
		global $smarty;
//		echo "hookMyAccountBlock". var_export($params,true);
		$id_customer = $params['cookie']->id_customer;


		if($result = Db::getInstance()->getRow("SELECT * FROM "._DB_PREFIX_."efide_customer_credit WHERE id_customer='$id_customer'")) {
			$smarty->assign(array( 'credits' => $result['credits']));
			return $this->hookCustomerAccount($params).$this->display(__FILE__, 'credits.tpl');
		}
//		return $this->hookCustomerAccount($params)." >> hookMyAccountBlock";
		return $this->hookCustomerAccount($params);
	}

	public function hookPayment($params) {
//		echo "hookPayment". var_export($params,true);
		if (!$this->active)
			return;

		//return "hookPayment";
		return $this->display(__FILE__, 'payment.tpl');
	}

	public function hookPaymentConfirm($params) {
//		echo "hookPaymentConfirm". var_export($params,true);
		if (!$this->active)
			return;

		return "hookPaymentConfirm";//$this->display(__FILE__, 'confirmation.tpl');
	}

	public function hookPaymentReturn($params) {
//		echo "hookPaymentConfirm". var_export($params,true);
		if (!$this->active)
			return;

		return "hookPaymentReturn";//$this->display(__FILE__, 'confirmation.tpl');
	}

	public function hookAuthentication($params) {
//		echo "hookAuthentication". var_export($params,true);
		if (!$this->active)
			return;

		return "hookAuthentication";
	}

	public function hookCreateAccount($params) {
//		echo "hookCreateAccount". var_export($params,true);
		if (!$this->active)
			return;

		return "hookCreateAccount";
	}

	public function hookOrderConfirmation($params) {
	//	echo "hookOrderConfirmations: ". var_export($params,true);
		if (!$this->active)
			return;

		$credits = 0;

//		echo $params['objOrder']->id_cart ."<br/>";
//		echo $params['cookie']->id_customer ."<br/>";

		// TODO: Verify $params['objOrder']->secure_key

		if(Db::getInstance()->getRow("SELECT * FROM "._DB_PREFIX_."efide_carts WHERE secure_key='".$params['objOrder']->secure_key."'")) {
			Tools::displayError('This order has already been processed: '. $params['objOrder']->secure_key);
			return "<p>". $this->l('This order has already been processed, if you believe this is an error please contact us.') ."</p>";
		}

		$id_cart = $params['objOrder']->id_cart;
		$id_customer = $params['cookie']->id_customer;
		$products = array();

		$result = Db::getInstance()->getRow("SELECT * FROM "._DB_PREFIX_."cart_product WHERE id_cart='$id_cart'");
		$products[$result['id_product']] = $result['quantity'];

		while($result = Db::getInstance()->nextRow()) {
			$products[$result['id_product']] = $result['quantity'];
		}

		foreach( $products as $product => $quantity) {
			$result = Db::getInstance()->getRow("SELECT * FROM "._DB_PREFIX_."product WHERE id_product='$product'");
			$credits += ($result['price'] * $quantity);
		}	

//		echo "Credits: ". $credits ."<br/>";

		if(!Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."efide_carts (`secure_key`) VALUES('". $params['objOrder']->secure_key ."')")) {
			Tools::displayError('This order has already been processed: '. $params['objOrder']->secure_key);
			return "<p>". $this->l('This order has already been processed, if you believe this is an error please contact us.') ."</p>";
		}
		/* */

		if(Db::getInstance()->getRow("SELECT * FROM "._DB_PREFIX_."efide_customer_credit WHERE id_customer='$id_customer'")) {
			$sql = "UPDATE "._DB_PREFIX_."efide_customer_credit SET credits = credits + $credits WHERE id_customer='$id_customer'";
		} else {
			$sql = "INSERT INTO "._DB_PREFIX_."efide_customer_credit (`credits`,`id_customer`) VALUES('$credits','$id_customer')";
		}

		if(!Db::getInstance()->Execute($sql)) {
			Tools::displayError('This order failed to process '. $params['objOrder']->secure_key);
			return $this->l('This order failed to process, if you believe this is an error please contact us. '. $sql);
		}

		return ;//"hookOrderConfirmation";
	}

	public function hookBackBeforePayment($params) {
//		echo "hookBackBeforePayment". var_export($params,true);
		if (!$this->active)
			return;

		return "hookBackBeforePayment";
	}
}
?>
