<?php


namespace Netzexpert\ConfiguratorTemplate\Setup\Patch\Schema;


use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory;
use Magento\Eav\Model\AttributeRepository;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;

class AddAttribOptions implements DataPatchInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;
    /**
     * @var AttributeOptionManagementInterface
     */
    protected $attributeOptionManagement;
    /**
     * @var AttributeOptionInterfaceFactory
     */
    protected $optionFactory;
    /**
     * @var AttributeOptionLabelInterfaceFactory
     */
    protected $attributeOptionLabelFactory;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;

    public function __construct(
        LoggerInterface $logger,
        AttributeRepository $attributeRepository,
        AttributeOptionManagementInterface $attributeOptionManagement,
        AttributeOptionLabelInterfaceFactory $attributeOptionLabelFactory,
        AttributeOptionInterfaceFactory $attributeOptionInterfaceFactory,
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $setup
    )
    {
        $this->logger                      = $logger;
        $this->attributeRepository         = $attributeRepository;
        $this->attributeOptionManagement   = $attributeOptionManagement;
        $this->attributeOptionLabelFactory = $attributeOptionLabelFactory;
        $this->optionFactory               = $attributeOptionInterfaceFactory;
        $this->eavSetupFactory             = $eavSetupFactory;
        $this->setup                       = $setup;
    }

    public static function getDependencies()
    {
        return [
            CreateAttrib::class
        ];
    }

    public function getAliases()
    {
        return [];
    }


    public function apply()
    {
        $this->setup->getConnection()->startsetup();

        $attribute_id = $this->attributeRepository->get('catalog_product', 'configurator_template')->getAttributeId();

        $options = [
            ['label' => '2_colums_custom', 'value' => '2_colums_custom', 'order' => '0'],
            ['label' => '3_colums_custom', 'value' => '3_colums_custom', 'order' => '1']
        ];
        foreach ($options as $opt) {
            $option = $this->optionFactory->create();
            $label = $this->attributeOptionLabelFactory->create();
            $label->setStoreId(0);
            $label->setLabel($opt['label']);
            $option->setStoreLabels([$label]);
            $option->setValue($opt['value']);
            $option->setSortOrder($opt['order']);
            $option->setIsDefault(false);
            $this->attributeOptionManagement->add('catalog_product', $attribute_id, $option);
        }
    }

}