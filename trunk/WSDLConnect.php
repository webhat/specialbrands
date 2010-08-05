<?php
require_once(_PS_MODULE_DIR_ .'specialbrands/settings.php');
  
ini_set('soap.wsdl_cache_ttl', 1);

class WSDLConnect {
	private static $client;

	public function __construct() {
		global $host;
		$client = new SoapClient( SP_Settings::$host, 
				array(
						'soap_version' => SOAP_1_1,
						'style' => SOAP_DOCUMENT, 
						'use' => SOAP_LITERAL,
						'trace' => true,
						'exceptions' => true,
						'cache_wsdl' => WSDL_CACHE_NONE));
	}

	public function ShopAdminAccount($c,$fn,$ln,$mail,$account_id,$resource_id,$enable,$reset) {
    $params = array(
        'access_key' => $accessKey,
        'company' => $c,
        'first_name' => $fn,
        'last_name' => $ln,
        'email' => $mail,
        'resource_id' => $resource_id,
        'enabled' => $enable,
        'reset_key' => $reset );

		if($account_id == NULL) {
			return $this->__CreateShopAdminAccount( $params);
		} else {
			$params['account_id'] = $account_id;
			return $this->__ModifyShopAdminAccount( $params);
		}
	}

	private function __CreateShopAdminAccount( $params) {
    return $this->client->CreateShopAdminAccount( $params);
	}

	private function __ModifyShopAdminAccount( $params) {
    return $this->client->ModifyShopAdminAccount( $params);
	}
}
/*


    $params = array(
        'access_key' => $accessKey,
        'account_id' => $shop->account_id,
        'company' => 'Kallen Kala2',
        'first_name' => 'Kalle2',
        'last_name' => 'Kalamies2',
        'email' => 'se@siel.la',
        'resource_id' => 'joku_id',
        'enabled' => true,
        'reset_access_key' => true );

    print_r( $client->ModifyShopAdminAccount( $params ));


    $params = array(
        'access_key' => $accessKey );

    print_r( $client->ListShopAdminAccounts( $params ) );
    
    
    $params = array(
        'access_key' => $accessKey,
        'account_id' => $shop->account_id  );

    print_r( $client->GetShopAdminAccountById( $params ) );

    $params = array(
        'access_key' => $accessKey,
        'resource_id' => 'joku_id' );

    print_r( $client->GetShopAdminAccountByResourceId( $params ) );


    $params = array(
        'access_key' => $accessKey,
        'account_id' => $shop->account_id );

    print_r( $client->RemoveShopAdminAccount( $params ) );
*/
?>
