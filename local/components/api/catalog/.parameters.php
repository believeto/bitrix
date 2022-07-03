<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)  {
	die();
}

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"REQUEST_METHOD" => Array(
			"NAME"=> 'Метод запроса',
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES"	=> "Y",
			"PARENT" => "BASE",
		),
		"REQUEST_OBJECT" => Array(
			"NAME"=> 'Объект запроса',
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES"	=> "Y",
			"PARENT" => "BASE",
		),
		"REQUEST_ACTION" => Array(
			"NAME"=> 'Метод обработки',
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES"	=> "Y",
			"PARENT" => "BASE",
		),
		"REQUEST_DATA" => Array(
			"NAME"=> 'Дополнительные параметры',
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES"	=> "Y",
			"PARENT" => "BASE",
		),
	)
);


