<?php

class PAJ_DevVersion_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getVersion()
	{
		return Mage::getModel('devversion/version')->getVersion();
	}

}