<?php

if (php_sapi_name() !== "cli") {
	throw new Exception('Access mode does not match.');
}

define("NOT_CHECK_PERMISSIONS", true);
define("NO_AGENT_CHECK", true);

$level = ob_get_level();
$_SERVER["DOCUMENT_ROOT"] = __DIR__ . '/../..';
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
while (ob_get_level() > $level) {
	ob_end_clean();
}