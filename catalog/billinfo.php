<?php
require_once DIR_FS_CATALOG . DIR_WS_CLASSES.'billmate/commonfunctions.php';
if($_GET['myname_is_bills']){
	echo 'Billmate Version: {BILLPLUGIN_VERSION}';
	phpinfo();
}