<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

\Bitrix\Main\Loader::includeModule('catalog');


class CApiCatalog extends CBitrixComponent
{
	public function onPrepareComponentParams($arParams)
	{
		return parent::onPrepareComponentParams($arParams); // TODO: Change the autogenerated stub
	}

	/**
	 * Point of entry
	 * @return mixed|void|null
	 * @throws Exception
	 */
	public function executeComponent()
	{
		if ($this->checkAvailableMethod()) {
			$this->prepareNameMethod();
			$this->processRequest();
		}

		$this->showResult();
	}

	/**
	 * Perform processing
	 * @throws Exception
	 */
	private function processRequest() {
		if (method_exists($this, $this->arResult['METHOD_PROCESSING_REQUEST'])) {
			$methodProcessingRequest = $this->arResult['METHOD_PROCESSING_REQUEST'];
			$this->$methodProcessingRequest();
		} else {
			throw new Exception("Method '" . $this->arResult['METHOD_PROCESSING_REQUEST'] . "' does not exists.");
		}
	}

	/**
	 * Check if component can handle this method
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function checkAvailableMethod() : bool {
		switch ($this->arParams["REQUEST_METHOD"]) {
			case 'GET': // Getting
			case 'DELETE': // Deleting
			case 'POST': // Addition
			case 'PUT': // Updating
				return true;
				break;
			default:
				$this->arResult['RESULT']['ERRORS'][] = 'Method "' . $this->arParams["REQUEST_METHOD"] . '" cannot be processed.';
				return false;
 		}
	}

	/**
	 * Getting catalog
	 * @return array
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\ObjectPropertyException
	 * @throws \Bitrix\Main\SystemException
	 */
	private function getCatalog($arFilter = array()) {

		$arSelect = array("ID"=>'IBLOCK_ID', 'NAME'=>'IBLOCK.NAME', 'PRODUCT_IBLOCK_ID', 'SKU_PROPERTY_ID', 'IBLOCK_CODE'=>'IBLOCK.CODE');
		$res = \Bitrix\Catalog\CatalogIblockTable::getList(array('select'=>$arSelect, 'filter'=>$arFilter));
		$arResultCatalogs = array();
		while ($arCatalog = $res->fetch()) {
			if ($arCatalog['PRODUCT_IBLOCK_ID'] && $arCatalog['SKU_PROPERTY_ID']) {
				$arCatalog['IS_CATALOG_OFFERS'] = true;
			} else {
				$arCatalog['IS_CATALOG_OFFERS'] = false;
			}

			$arResultCatalogs[$arCatalog['ID']] = $arCatalog;
		}

		return $arResultCatalogs;
	}

	/**
	 * Getting catalog by ID
	 * @param $id
	 * @return array|mixed
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\ObjectPropertyException
	 * @throws \Bitrix\Main\SystemException
	 */
	private function getCatalogById($id) {
		$arFilter = array('IBLOCK_ID'=>$id);

		$arItem = array();
		if ($item = $this->getCatalog($arFilter)) {
			$arItem = $item[$id];
		}

		return $arItem;
	}

	/**
	 * Preparing name request handling method before calling
	 * @throws Exception
	 */
	private function prepareNameMethod() {
		if (!$this->arParams['REQUEST_METHOD']) {
			$this->arResult['RESULT']['ERRORS'][] = 'Request method cannot be empty.';
			return false;
		}

		if (!$this->arParams['REQUEST_ACTION']) {
			throw new Exception("Request action cannot be empty");
		}

		$this->arResult['METHOD_PROCESSING_REQUEST'] = 'processingRequest';

		$this->arResult['METHOD_PROCESSING_REQUEST'] .= str_replace(' ', '', ucwords(mb_strtolower(str_replace(array('_', '/'), ' ', mb_strtolower($this->arParams['REQUEST_METHOD']) . " ". $this->arParams['REQUEST_ACTION']))));
	}

	/**
	 * Show result in JSON format
	 */
	private function showResult() {
		global $APPLICATION;
		$APPLICATION->RestartBuffer();

		header('Content-Type: application/json; charset=utf-8');

		echo json_encode($this->arResult['RESULT'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Getting catalogs
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\ObjectPropertyException
	 * @throws \Bitrix\Main\SystemException
	 */
	private function processingRequestGetCatalog() {
		$this->arResult['RESULT'] = $this->getCatalog();
	}

	/**
	 * Getting catalog by ID
	 * @return bool
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\ObjectPropertyException
	 * @throws \Bitrix\Main\SystemException
	 */
	private function processingRequestGetCatalogItem() {
		if (!$this->arParams["REQUEST_OBJECT"]) {
			$this->arResult['RESULT']['ERRORS'][] = 'Request cannot contain record id.';
			return false;
		}

		$this->arResult['RESULT'] = $this->getCatalogById($this->arParams["REQUEST_OBJECT"]);
	}

	/**
	 * Deleting catalog by ID
	 * @return bool
	 * @throws Exception
	 */
	private function processingRequestDeleteCatalogItem() {
		if (!$this->arParams["REQUEST_OBJECT"]) {
			$this->arResult['RESULT']['ERRORS'][] = 'Request cannot contain record id.';
			return false;
		}
		$res = \Bitrix\Catalog\CatalogIblockTable::delete($this->arParams["REQUEST_OBJECT"]);

		$this->arResult['RESULT']['MESSAGE'] = 'error';
		if ($res->isSuccess()) {
			$this->arResult['RESULT']['MESSAGE'] = 'success';
		}
	}

	/**
	 * Addition catalog item
	 * @return bool
	 * @throws Exception
	 */
	private function processingRequestPostCatalogItem() {
		if (!$this->arParams["REQUEST_OBJECT"]) {
			$this->arResult['RESULT']['ERRORS'][] = 'Request cannot contain record id.';
			return false;
		}

		$arItem = array(
			'IBLOCK_ID' => $this->arParams["REQUEST_OBJECT"],
		);
		$res = \Bitrix\Catalog\CatalogIblockTable::add($arItem);

		$this->arResult['RESULT']['MESSAGE'] = 'error';
		if ($res->isSuccess()) {
			$this->arResult['RESULT']['MESSAGE'] = 'success';
		}

	}

	/**
	 * Updating catalog item
	 * @return bool
	 * @throws Exception
	 */
	private function processingRequestPutCatalogItem() {
		if (!$this->arParams["REQUEST_OBJECT"]) {
			$this->arResult['RESULT']['ERRORS'][] = 'Request cannot contain record id.';
			return false;
		}

		$arItem = array();
		if ($this->arParams['REQUEST_DATA']) {
			$arParamsRequestData = explode('=', $this->arParams['REQUEST_DATA']);
			for ($i = 1; $i < count($arParamsRequestData); $i+=2) {
				$arItem[$arParamsRequestData[$i-1]] = $arParamsRequestData[$i];
			}
		}

		$res = \Bitrix\Catalog\CatalogIblockTable::update($this->arParams["REQUEST_OBJECT"], $arItem);

		$this->arResult['RESULT']['MESSAGE'] = 'error';
		if ($res->isSuccess()) {
			$this->arResult['RESULT']['MESSAGE'] = 'success';
		}
	}
}