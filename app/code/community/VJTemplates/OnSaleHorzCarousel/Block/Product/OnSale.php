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
        if($this->getCategoryId())
            $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
        
        $collection = $product->getCollection()
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addAttributeToSelect('name')
			->addAttributeToSelect('price')
			->addAttributeToSelect('small_image')
			->addAttributeToSelect('status')
			->addAttributeToFilter("special_from_date", array("date" => true, "to" => date("m/d/y")))
			->addAttributeToFilter("special_to_date", array("or"=> array(
				0 => array("date" => true, "from" => mktime(0, 0, 0, date("m"), date("d")+1, date("y"))),
				1 => array("is" => new Zend_Db_Expr("null")))
				), "left");

        if(isset($category)) {
            $collection->addCategoryFilter($category);
        }
    
        $collection ->addStoreFilter();        
		$current_categoryId = Mage::getModel('catalog/layer')->getCurrentCategory()->getId(); 
    	$current_category = Mage::getModel('catalog/category')->load($current_categoryId);
		$collection->addCategoryFilter($current_category);
		            
        if($this->getOrderby())    
            $collection->getSelect()->order($this->getOrderby());
        if($this->getNumProducts())
            $collection->getSelect()->limit($this->getNumProducts());
        
        return $collection;
    
    }
}

?> 