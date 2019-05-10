<?php

namespace SpDev\ProductOrderingInformation\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Stdlib\DateTime\Timezone;
use SpDev\ProductOrderingInformation\Api\Data\SalesInformationInterface;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory;

/**
 * Class SalesInformation
 *
 * @package SpDev\ProductOrderingInformation\Model
 */
class SalesInformation extends AbstractExtensibleModel implements SalesInformationInterface
{

    /**
     * @var string
     */
    private $orderStatus;

    /**
     * @var CollectionFactory
     */
    private $ordersCollection;

    /**
     * @var Timezone
     */
    private $dateFormater;

    /**
     * SalesInformation constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param CollectionFactory $ordersCollection
     * @param Timezone $timezone
     * @param $orderStatus
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        CollectionFactory $ordersCollection,
        Timezone $timezone,
        $orderStatus,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
        $this->orderStatus = $orderStatus;
        $this->ordersCollection = $ordersCollection;
        $this->dateFormater = $timezone;
    }

    /**
     * Retrieve Product Id
     *
     * @return int
     */
    public function getProductId()
    {
        return (int) $this->_getData(static::PRODUCT_ID);
    }

    /**
     * @param $productId
     * @return SalesInformation
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Retrieve total qty of product ordering
     *
     * @return int
     */
    public function getQty()
    {
        $itemCollection = $this->ordersCollection->create()
            ->addFieldToFilter('product_id', $this->getProductId());

        $itemCollection->getSelect()
            ->columns('SUM(qty_ordered) as total_qty')
            ->group('product_id');

        $totalOrdered = $itemCollection->getFirstItem()->getTotalQty();

        return (int) $totalOrdered;
    }

    /**
     * Retrieve date of last product ordering
     *
     * @return string
     */
    public function getLastOrder()
    {
        $itemCollection = $this->ordersCollection->create()
            ->addFieldToFilter('product_id', $this->getProductId());

        $itemCollection->getSelect()
            ->columns('MAX(created_at) as last_order');

        $lastOrderDate = $itemCollection->getFirstItem()->getLastOrder();

        $lastOrderDate = $this->dateFormater->formatDate($lastOrderDate, \IntlDateFormatter::MEDIUM, true);

        return $lastOrderDate;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \SpDev\ProductOrderingInformation\Api\Data\SalesInformationExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param \SpDev\ProductOrderingInformation\Api\Data\SalesInformationExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \SpDev\ProductOrderingInformation\Api\Data\SalesInformationExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
