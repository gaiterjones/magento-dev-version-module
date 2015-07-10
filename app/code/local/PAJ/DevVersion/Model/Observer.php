<?php

//
// https://www.nicksays.co.uk/developers-guide-magento-cache/
//
class PAJ_DevVersion_Model_Observer
{

	public function adminhtml_cache_refresh_type($observer)
	{
		$request = Mage::app()->getRequest()->getPost('types');
			
			// $request -> Array ( [0] => config [1] => layout [2] => block_html [3] => translate [4] => collections [5] => eav [6] => config_api [7] => config_api2 ) 
			
			if(in_array('config', $request))
			{
				$cache = Mage::app()->getCache();
				$cache->remove("paj_dev_version");
			}
	}
 
}

