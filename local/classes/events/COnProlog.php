<?php

class COnProlog
{
	public function addUrlRewriteRule() {

		if (defined('ADMIN_SECTION')) {
			return;
		}

		\Bitrix\Main\UrlRewriter::add(SITE_ID, array(
			'CONDITION' => '#^/api/catalog/([0-9]+)/#',
			'RULE' => 'REQUEST_ACTION=CATALOG_ITEM&REQUEST_OBJECT=$1&REQUEST_DATA=$2',
			'ID' => '',
			'PATH' => '/local/api/catalog/index.php',
			'SORT' => 100,
		));

		\Bitrix\Main\UrlRewriter::add(SITE_ID, array(
			'CONDITION' => '#^/api/catalog/#',
			'RULE' => 'REQUEST_ACTION=CATALOG',
			'ID' => '',
			'PATH' => '/local/api/catalog/index.php',
			'SORT' => 200,
		));
	}
}