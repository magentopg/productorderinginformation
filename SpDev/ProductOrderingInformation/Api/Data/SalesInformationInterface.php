<?php
namespace SpDev\ProductOrderingInformation\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface SalesInformationInterface
 * @package SpDev\ProductOrderingInformation\Api\Data
 */
interface SalesInformationInterface extends ExtensibleDataInterface
{

    /**
     * Product ID
     */
    const PRODUCT_ID = 'product_id';

    /**
     * Retrieve total qty of product ordering
     *
     * @return int
     */
    public function getQty();

    /**
     * Retrieve date of last product ordering
     *
     * @return string
     */
    public function getLastOrder();

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \SpDev\ProductOrderingInformation\Api\Data\SalesInformationExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \SpDev\ProductOrderingInformation\Api\Data\SalesInformationExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \SpDev\ProductOrderingInformation\Api\Data\SalesInformationExtensionInterface $extensionAttributes
    );
}
