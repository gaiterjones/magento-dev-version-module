<?php

class PAJ_DevVersion_Model_Version extends Mage_Core_Model_Abstract
{
	public $devVersion;
	private $versionFile;

	protected function _construct()
	{
		$this->_loadVersionGit();
	}
	
	public function getVersion()
	{
		return $this->devVersion;
	}	

	// get version info from github file
	//
	protected function _loadVersionGit()
	{
		$cache = Mage::app()->getCache();
		
		$this->devVersion=$cache->load("paj_dev_version");
		
		if (!$this->devVersion)
		{
			$_host= gethostname();
			$_ip = md5(gethostbyname($_host));
			$_gitHubUser='gaiterjones';
			$_base=parse_url(Mage::getBaseUrl(), PHP_URL_HOST);
			$_git='https://raw.githubusercontent.com/'. $_gitHubUser. '/magento-dev-version/master/'. $_host. '-'. $_ip. '/'. $_base. '/devVersion.txt';
			
			if($this->get_http_response_code($_git) != "200"){
				$this->devVersion='<br><span title="'. $_git. '">Development ver. ('. $_host. '/'. $_base. ') Not found</span>';
				return;
			}
			
			$this->devVersion = '<br>Development ver. <a target="_blank" href="https://raw.githubusercontent.com/'. $_gitHubUser. '/magento-dev-version/master/'. $_host. '-'. $_ip. '/'. $_base. '/devChange.txt">'. file_get_contents($_git). '</a>';
			
			$cache->save($this->devVersion, "paj_dev_version", array("paj_dev_version"), 86400);
		}
	}
	
	// get version info from filesystem file
	//
	protected function _loadVersionFile()
	{
		$cache = Mage::app()->getCache();
		
		$this->devVersion=$cache->load("paj_dev_version");
		
		if (!$this->devVersion)
		{
			$this->versionFile = '/home/www/devVersion.txt';
					
			$ioAdapter = new Varien_Io_File();
			
			if (!$ioAdapter->fileExists($this->versionFile)) {
				return;
			}

			$ioAdapter->open(array('path' => $ioAdapter->dirname($this->versionFile)));
			$ioAdapter->streamOpen($this->versionFile, 'r');

			while ($buffer = $ioAdapter->streamRead()) {
					$this->devVersion = $this->devVersion.$buffer . ' '. date ("F d Y H:i:s.", filemtime($this->versionFile));
			}
			$ioAdapter->streamClose();
			
			$cache->save($this->devVersion, "paj_dev_version", array("paj_dev_version"), 3600);
			
		}
	}

	private function get_http_response_code($url) {
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}	

}