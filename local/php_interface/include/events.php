<?
$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler('main', 'OnProlog', array("COnProlog", "addUrlRewriteRule"), $_SERVER['DOCUMENT_ROOT'].'/local/classes/events/COnProlog.php');