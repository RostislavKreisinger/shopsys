<?php

namespace SS6\ShopBundle\Model\Product;

class ProductListOrderingSetting {

	const ORDER_BY_NAME_ASC = 'name_asc';
	const ORDER_BY_NAME_DESC = 'name_desc';

	/**
	 * @var string
	 */
	private $orderingMode;

	/**
	 * @param string $orderingMode
	 */
	public function __construct($orderingMode) {
		$this->setOrderingMode($orderingMode);
	}

	/**
	 * @param string $orderingMode
	 * @throws \SS6\ShopBundle\Model\ProductException\InvalidOrderingModeException
	 */
	private function setOrderingMode($orderingMode) {
		if (!in_array($orderingMode, self::getOrderingModes())) {
			$message = 'Product list ordering mod "' . $orderingMode  .'" is not valid.';
			throw new \SS6\ShopBundle\Model\ProductException\InvalidOrderingModeException($message);
		}

		$this->orderingMode = $orderingMode;
	}

	/**
	 * @return string
	 */
	public function getOrderingMode() {
		return $this->orderingMode;
	}

	/**
	 * @return array
	 */
	public static function getOrderingModes() {
		return array(
			self::ORDER_BY_NAME_ASC,
			self::ORDER_BY_NAME_DESC,
		);
	}

}
