<?php

class VJTemplates_OnSaleHorzCarousel_Block_Category_View extends Mage_Catalog_Block_Category_View {

	public function getOnSaleProductHtml() {
		return $this->getBlockHtml('product_onsalehorz');
	}

}
?>