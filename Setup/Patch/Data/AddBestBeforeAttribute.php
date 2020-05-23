<?php
declare(strict_types=1);

namespace Tarknaiev\BestBefore\Setup\Patch\Data;

use Magento\Catalog\Model\Attribute\Backend\Startdate;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddBestBeforeAttribute implements DataPatchInterface, PatchRevertableInterface
{
    const BEST_BEFORE_CUSTOM_ATTRIBUTE = 'best_before_date';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * AddProductAttributeBrand constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies ()
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases ()
    {
        return [];
    }

    /**
     * @return DataPatchInterface|void
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function apply ()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            self::BEST_BEFORE_CUSTOM_ATTRIBUTE,
            [
                'type' => 'datetime',
                'backend' => Startdate::class,
                'label' => 'Best Before Date',
                'input' => 'date',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'apply_to' => 'simple'
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Rollback all changes, done by this patch
     *
     * @return void
     */
    public function revert ()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(Product::ENTITY, self::BEST_BEFORE_CUSTOM_ATTRIBUTE);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
