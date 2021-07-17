<?php

namespace Namespace\Module\Observer;

class MultipleProduct implements \Magento\Framework\Event\ObserverInterface {

    public function __construct(
    \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Checkout\Model\Cart $cart, \Magento\Catalog\Model\Product $product, \Magento\Quote\Model\QuoteFactory $quote
    ) {
        $this->_storeManager = $storeManager;
        $this->_cart = $cart;
        $this->_product = $product;
        $this->quote = $quote;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {


        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            for ($i = 0; $i < sizeof($_POST['prod_id']); $i++) {

                $qty = $_POST['prod_qty'][$i];

                $product = $this->_product->load($_POST['prod_id'][$i]);

                $product_type = $product->getTypeID();

                if($product_type == 'configurable') {
                    $simple_products = $product->getTypeInstance()->getUsedProducts($product); //get all simple products
                    $attributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product); //get all possible attributes

                    foreach ($simple_products as $simple_product) {
                        $super_attributes = array();
                        foreach ($attributes as $attribute) {
                            $text = $simple_product->getAttributeText('size');
                            //echo $text;
                            $t1 = 'Black';
                            $t2 = 'M';
                            foreach ($attribute['values'] as $value) {
                            $super_attributes[$attribute['attribute_id']] = $value['value_index'];
                                if ($value['label'] == $t1) {
                                    $super_attributes[$attribute['attribute_id']] = $value['value_index'];
                                }
                                if ($value['label'] == $t2) {
                                    $super_attributes[$attribute['attribute_id']] = $value['value_index'];
                                }
                            }
                        }
                    }
                    $this->_cart->addProduct($product, array(
                        'qty' => $_POST['prod_qty'][$i],
                        'super_attribute' => $super_attributes
                    ));
                    $this->_cart->save();
                }
            }
        }
    }

}