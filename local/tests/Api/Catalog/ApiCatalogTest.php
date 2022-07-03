<?php

include_once dirname(__FILE__) . '/../../common.php';

CBitrixComponent::includeComponentClass("api:catalog");

class ApiCatalogTest extends \PHPUnit\Framework\TestCase
{
	private $component;

	protected function setUp(): void
	{
		$this->component = new CApiCatalog();
	}

	protected function tearDown(): void
	{
	}

	/**
	 * Check load module catalog
	 * @throws \Bitrix\Main\LoaderException
	 */
	public function testModuleInstalled()
	{
		$this->assertTrue(\Bitrix\Main\Loader::includeModule("catalog"));
	}

	/**
	 * CApiCatalog::processingRequestGetCatalog - Getting catalogs
	 */
	public function testProcessingRequestGetCatalog()
	{
		$this->component->arParams = array(
			"REQUEST_METHOD" => "GET",
			"REQUEST_ACTION" => "CATALOG",
		);

		ob_start();
		$this->component->executeComponent();
		$jsonOutput = ob_get_contents();
		ob_end_clean();

		$arrOutput = array_column(json_decode($jsonOutput, true), 'ID');

		$this->assertTrue(count($arrOutput) > 0);
	}

	/**
	 * CApiCatalog::processingRequestGetCatalogItem - Getting catalog by ID
	 */
	public function testProcessingRequestGetCatalogItem()
	{
		$arSelect = array("ID"=>'IBLOCK_ID', 'NAME'=>'IBLOCK.NAME');
		$res = \Bitrix\Catalog\CatalogIblockTable::getList(array('select'=>$arSelect, 'limit'=>1));
		$arResultCatalogItem = array();
		while ($arCatalog = $res->fetchRaw()) {
			if ($arCatalog['PRODUCT_IBLOCK_ID'] && $arCatalog['SKU_PROPERTY_ID']) {
				$arCatalog['IS_CATALOG_OFFERS'] = true;
			} else {
				$arCatalog['IS_CATALOG_OFFERS'] = false;
			}

			$arResultCatalogItem = $arCatalog;
		}

		if (!$arResultCatalogItem['ID']) {
			return false;
		}

		$this->component->arParams = array(
			"REQUEST_METHOD" => "GET",
			"REQUEST_ACTION" => "CATALOG_ITEM",
			"REQUEST_OBJECT" => $arResultCatalogItem['ID'],
		);

		ob_start();
		$this->component->executeComponent();
		$jsonOutput = ob_get_contents();
		ob_end_clean();

		$arrOutput = json_decode($jsonOutput, true);

		$isAssertTrue = ($arrOutput['ID'] === $arResultCatalogItem['ID']) && ($arrOutput['NAME'] === $arResultCatalogItem['NAME']) && ($arrOutput['IS_CATALOG_OFFERS'] === $arResultCatalogItem['IS_CATALOG_OFFERS']);

		$this->assertTrue($isAssertTrue);
	}

	/**
	 * CApiCatalog::processingRequestPostCatalogItem - Addition catalog item
	 */
	public function testProcessingRequestPostCatalogItem()
	{
		$res = \Bitrix\Iblock\IblockTable::getList(array('filter'=>array('NAME'=>'test'), 'select'=>array('ID', 'NAME'), 'limit'=>1));
		$arResultCatalogItem = array();
		while ($arCatalog = $res->fetchRaw()) {
			$arResultCatalogItem = $arCatalog;
		}

		if (!$arResultCatalogItem['ID']) {
			return false;
		}

		$this->component->arParams = array(
			"REQUEST_METHOD" => "POST",
			"REQUEST_ACTION" => "CATALOG_ITEM",
			"REQUEST_OBJECT" => $arResultCatalogItem['ID'],
		);

		ob_start();
		$this->component->executeComponent();
		$jsonOutput = ob_get_contents();
		ob_end_clean();

		$arrOutput = json_decode($jsonOutput, true);

		$isAssertTrue = $arrOutput['MESSAGE'] === 'success';

		$this->assertTrue($isAssertTrue);
	}

	/**
	 * CApiCatalog::processingRequestPutCatalogItem - Updating catalog item
	 */
	public function testProcessingRequestPutCatalogItem()
	{
		$arSelect = array("ID"=>'IBLOCK_ID', 'NAME'=>'IBLOCK.NAME');
		$res = \Bitrix\Catalog\CatalogIblockTable::getList(array('filter'=>array('IBLOCK.NAME'=>'test'), 'select'=>$arSelect, 'limit'=>1));
		$arResultCatalogItem = array();
		while ($arCatalog = $res->fetchRaw()) {
			$arResultCatalogItem = $arCatalog;
		}

		if (!$arResultCatalogItem['ID']) {
			return false;
		}

		$this->component->arParams = array(
			"REQUEST_METHOD" => "PUT",
			"REQUEST_ACTION" => "CATALOG_ITEM",
			"REQUEST_OBJECT" => $arResultCatalogItem['ID'],
			"REQUEST_DATA" => "PRODUCT_IBLOCK_ID=1",
		);

		ob_start();
		$this->component->executeComponent();
		$jsonOutput = ob_get_contents();
		ob_end_clean();

		$arrOutput = json_decode($jsonOutput, true);

		$isAssertTrue = $arrOutput['MESSAGE'] === 'success';

		$this->assertTrue($isAssertTrue);
	}

	/**
	 * CApiCatalog::processingRequestDeleteCatalogItem - Deleting catalog by ID
	 */
	public function testProcessingRequestDeleteCatalogItem()
	{
		$arSelect = array("ID"=>'IBLOCK_ID', 'NAME'=>'IBLOCK.NAME');
		$res = \Bitrix\Catalog\CatalogIblockTable::getList(array('filter'=>array('IBLOCK.NAME'=>'test'), 'select'=>$arSelect, 'limit'=>1));
		$arResultCatalogItem = array();
		while ($arCatalog = $res->fetchRaw()) {
			if ($arCatalog['PRODUCT_IBLOCK_ID'] && $arCatalog['SKU_PROPERTY_ID']) {
				$arCatalog['IS_CATALOG_OFFERS'] = true;
			} else {
				$arCatalog['IS_CATALOG_OFFERS'] = false;
			}

			$arResultCatalogItem = $arCatalog;
		}

		if (!$arResultCatalogItem['ID']) {
			return false;
		}

		$this->component->arParams = array(
			"REQUEST_METHOD" => "DELETE",
			"REQUEST_ACTION" => "CATALOG_ITEM",
			"REQUEST_OBJECT" => $arResultCatalogItem['ID'],
		);

		ob_start();
		$this->component->executeComponent();
		$jsonOutput = ob_get_contents();
		ob_end_clean();

		$arrOutput = json_decode($jsonOutput, true);

		$isAssertTrue = $arrOutput['MESSAGE'] === 'success';

		$this->assertTrue($isAssertTrue);
	}
}