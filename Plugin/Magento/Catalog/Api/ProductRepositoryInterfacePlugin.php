<?php
declare(strict_types=1);

namespace Tarknaiev\BestBefore\Plugin\Magento\Catalog\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Tarknaiev\BestBefore\Helper\Dates;
use Tarknaiev\BestBefore\Setup\Patch\Data\AddBestBeforeAttribute;

/**
 * Class ProductRepositoryInterfacePlugin
 * @package Tarknaiev\BestBefore\Plugin\Magento\Catalog\Api
 */
class ProductRepositoryInterfacePlugin
{
    /**
     * @var Dates
     */
    protected $helper;

    /**
     * ProductRepositoryInterfacePlugin constructor.
     * @param Dates $helper
     */
    public function __construct (
        Dates $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param ProductInterface $entity
     * @return int|null
     */
    protected function countBestBeforeData(ProductInterface $entity) :? int
    {
        $bestBeforeDate = $entity->getCustomAttribute(AddBestBeforeAttribute::BEST_BEFORE_CUSTOM_ATTRIBUTE);
        if ($bestBeforeDate && $bestBeforeDate->getValue()) {
            return $this->helper->getDaysForNextDate($bestBeforeDate->getValue());
        }
        return null;
    }

    /**
     * @param ProductInterface $entity
     * @return ProductInterface
     */
    protected function setBestBeforeData(ProductInterface $entity) : ProductInterface
    {
        $bestBeforeData = $this->countBestBeforeData($entity);

        $extensionAttributes = $entity->getExtensionAttributes(); /** get current extension attributes from entity **/
        $extensionAttributes->setBestBefore($bestBeforeData);

        $entity->setExtensionAttributes($extensionAttributes);
        $entity->setBestBefore($bestBeforeData);

        return $entity;
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $entity
     * @return ProductInterface
     */
    public function afterGet (ProductRepositoryInterface $subject, ProductInterface $entity) : ProductInterface
    {
        $productEntity = $this->setBestBeforeData($entity);
        return $productEntity;
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $entity
     * @return ProductInterface
     */
    public function afterGetById (ProductRepositoryInterface $subject, ProductInterface $entity) : ProductInterface
    {
        $productEntity = $this->setBestBeforeData($entity);
        return $productEntity;
    }
}
