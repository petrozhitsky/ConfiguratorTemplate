<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netzexpert\ConfiguratorTemplate\Setup\Patch\Schema;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

class CreateAttrib
    implements DataPatchInterface,
    PatchRevertableInterface
{
    /** @var EavSetupFactory  */
    private $eavSetupFactory;

    /** @var ModuleDataSetupInterface  */
    private $setup;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * CreateAttrib constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $setup
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $setup
    ) {
        $this->eavSetupFactory  = $eavSetupFactory;
        $this->setup            = $setup;
    }

    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->setup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'configurator_template',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'configurator_template',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'option' => [
                    'values' => [],
                ]
            ]
        );

        $this->setup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
    public function revert()
    {
        $this->setup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);
        $eavSetup -> removeAttribute(\Magento\Catalog\Model\Product::ENTITY,'configurator_template');

        $this->setup->getConnection()->endSetup();
    }

}