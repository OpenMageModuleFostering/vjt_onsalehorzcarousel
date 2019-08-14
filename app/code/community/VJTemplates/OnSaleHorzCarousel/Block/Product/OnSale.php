<?php
/*
#------------------------------------------------------------------------
  Magento Extension - OnSale Product Horizontal Carousel
#------------------------------------------------------------------------
# Copyright (C) 2012 VJ Templates.
# @license - GNU/GPL
# Author: VJ Templates
# Websites: http://www.vjtemplates.com
#------------------------------------------------------------------------
*/
class VJTemplates_OnSaleHorzCarousel_Block_Product_OnSale extends Mage_Catalog_Block_Product_List {

    public function getOnSaleProduct(){
            
        $product = Mage::getModel('catalog/product');
        $storeId = Mage::app()->getStore()->getId();
        $collection = $product->getCollection()
			->addStoreFilter($storeId)
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addAttributeToSelect('name')
			->addAttributeToSelect('price')
			->addAttributeToSelect('small_image')
			->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
			->addAttributeToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
			->addAttributeToFilter("special_from_date", array("date" => true, "to" => date("m/d/y")))
			->addAttributeToFilter("special_to_date", array("or"=> array(
				0 => array("date" => true, "from" => mktime(0, 0, 0, date("m"), date("d")+1, date("y"))),
				1 => array("is" => new Zend_Db_Expr("null")))
				), "left");

        if($this->getCategoryId()) {
		$CategoryIds = explode(',',$this->getCategoryId());
			foreach($CategoryIds as $CategoryId) {
				$category = Mage::getModel('catalog/category')->load($CategoryId);
				$category->setIsAnchor(1);
				$collection->addCategoryFilter($category);
			}
		}  
    
		$currentCategory = Mage::getModel('catalog/layer')->getCurrentCategory()->getId();
    	$current_category = Mage::getModel('catalog/category')->load($currentCategory);
		$current_category->setIsAnchor(1);
		$collection->addCategoryFilter($current_category);
		            
        if($this->getOrderby()) $collection->getSelect()->order($this->getOrderby());
        if($this->getNumProducts()) $collection->getSelect()->limit($this->getNumProducts());
        
        return $collection;
    
    }
}

?> 