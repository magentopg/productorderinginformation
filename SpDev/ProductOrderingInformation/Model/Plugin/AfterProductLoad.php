<?php

namespace SpDev\ProductOrderingInformation\Model\Plugin;

use Magento\Catalog\Model\Product;
use SpDev\ProductOrderingInformation\Api\Data\SalesInformationInterface;

/**
 * Class AfterProductLoad
 * @package SpDev\ProductOrderingInformation\Model\Plugin
 */
class AfterProductLoad
{

    /**
     * @var SalesInformationInterface $salesInformation
     */
    private $salesInformation;

    /**
     * AfterProductLoad constructor.
     * @param SalesInformationInterface $salesInformation
     */
    public function __construct(
        SalesInformationInterface $salesInformation
    ) {
        $this->salesInformation = $salesInformation;
    }

    /**
     * Add product ordering information to the product's extension attributes
     *
     * @param Product $product
     * @return Product
     */
    public function afterLoad(Product $product)
    {
        $this->salesInformation->setProductId($product->getId());
        $productExtension = $product->getExtensionAttributes();
        $productExtension->setSalesInformation($this->salesInformation);
        $product->setExtensionAttributes($productExtension);
        return $product;
    }
}
