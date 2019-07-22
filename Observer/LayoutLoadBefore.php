<?php
namespace Netzexpert\ConfiguratorTemplate\Observer;
class LayoutLoadBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    public function __construct(
        \Magento\Framework\Registry $registry
    ) {
        $this->_registry = $registry;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
{
    $product = $this->_registry->registry('current_product');
    if (!$product){
        return $this;
    }
    if ($product->getAttributeText('configurator_template')) {
        $layout = $observer->getData('layout');
        $layout->getUpdate()->addHandle("catalog_product_view_configurator_template_" . $product->getAttributeText('configurator_template'));
    }
    return $this;
}
}
