<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
--------------------------------------------------------------------------*/
class Bumble_Filter_Model_Config_Source_Template
{
    const _DEFAULT       = 'default';
    //const CONTENT_BOTTOM    = 'CONTENT_BOTTOM';
	 /**
     * Design packages list getter
     * @return array
     */
    public function getPackageList()
    {
        $directory = Mage::getBaseDir('design') . DS . 'frontend';
        return $this->_listDirectories($directory);
    }

    /**
     * Design package (optional) themes list getter
     * @param string $package
     * @return string
     */
    public function getThemeList($package = null)
    {
        $result = array();

        if (is_null($package)){
            foreach ($this->getPackageList() as $package){
                $result[$package] = $this->getThemeList($package);
            }
        } else {
            $directory = Mage::getBaseDir('design') . DS . 'frontend' . DS . $package;
            $result = $this->_listDirectories($directory);
        }

        return $result;
    }
	
	private function _listDirectories($path, $fullPath = false)
    {
        $result = array();
        $dir = opendir($path);
        if ($dir) {
            while ($entry = readdir($dir)) {
                if (substr($entry, 0, 1) == '.' || !is_dir($path . DS . $entry)){
                    continue;
                }
                if ($fullPath) {
                    $entry = $path . DS . $entry;
                }
                $result[] = $entry;
            }
            unset($entry);
            closedir($dir);
        }

        return $result;
    }
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
		//$directory = Mage::getBaseDir('design') . DS . 'frontend' . DS . $package;
		$directory = Mage::getBaseDir('design') . DS . 'frontend' . DS . 'default' . DS . 'default' .  DS . 'template'.  DS . 'bumble_filter'. DS . 'content';
        $directories = $this->_listDirectories($directory);
        $templates =  array(
			array('value' => self::_DEFAULT, 'label'=>Mage::helper('adminhtml')->__('default'))            
        );
		foreach($directories as $key => $template){
			$templates[] = array('value' => $template, 'label'=>$template);
		}
		
		return $templates;
    }
}
