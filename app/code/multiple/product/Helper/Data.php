<?php
 
namespace [Vendor]\[namespace]\Helper;
 
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\ProductFactory;
 
class Data extends AbstractHelper
{
    private $cart;
    private $productFactory;
    
    public function __construct(Context $context, Cart $cart, ProductFactory $productFactory)
    {
 
        $this->productFactory = $productFactory;
        $this->cart = $cart;
        parent::__construct($context);
 
    }
 
    public function getAddCustomProduct($productId)
    {
        $product = $this->productFactory->load($productId);
 
        $cart = $this->cart;
 
        $params = array();
        $options = array();
        $params['qty'] = 1;
        $params['product'] = $productId;
 
        foreach ($product->getOptions() as $o) {
            foreach ($o->getValues() as $value) {
                $options[$value['option_id']] = $value['option_type_id'];
 
            }
        }
        $params['options'] = $options;
        $cart->addProduct($product, $params);
        $cart->save();
 
 
    }
}
