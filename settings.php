<?php
if(is_file("./accesskey.php")) {
	include_once("accesskey.php");
} else
if(is_file("../accesskey.php")) {
	include_once("accesskey.php");
} else
if(is_file("../../accesskey.php")) {
	include_once("accesskey.php");
}
    //$host = "http://localhost:8082/wsdl/OperatorAdmin_strict.wsdl";
    $host = "https://spm-dev.appspot.com/wsdl/OperatorAdmin_strict.wsdl";


?>
