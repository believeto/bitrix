<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>

<?$APPLICATION->IncludeComponent(
	"api:catalog",
	"",
	Array(
		"REQUEST_METHOD" => $_SERVER['REQUEST_METHOD'],
		"REQUEST_OBJECT" => $_REQUEST['REQUEST_OBJECT'],
		"REQUEST_ACTION" => $_REQUEST['REQUEST_ACTION'],
		"REQUEST_DATA" => $_REQUEST['REQUEST_DATA'],
	)
);?>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>

