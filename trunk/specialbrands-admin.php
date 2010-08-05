<?
/* SSL Management */
$useSSL = true;
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');

if (!$cookie->isLogged())
	Tools::redirect('authentication.php?back=modules/specialbrands/specialbrands-admin.php');

include_once(dirname(__FILE__).'/specialbrands.php');

include(dirname(__FILE__).'/../../header.php');

echo specialbrands::displayShopAdminForm(NULL);

include(dirname(__FILE__).'/../../footer.php');

?>

