<?php


$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();
$installer->run("
 
CREATE TABLE IF NOT EXISTS `{$this->getTable('bumble_filter/filter')}` (
  `filter_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `manufacturer` varchar(250) NOT NULL,
  `usemanufacturer` varchar(250) NOT NULL,
  `extraatt` varchar(250) NOT NULL,
  `subatt` varchar(250) NOT NULL,
  `category` varchar(250) NOT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
 
  
  PRIMARY KEY (`filter_id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;



CREATE TABLE IF NOT EXISTS `{$this->getTable('bumble_filter/filter_store')}` (
  `filter_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`filter_id`,`store_id`),
  CONSTRAINT `FK_ARMAH_ST` FOREIGN KEY (`filter_id`) REFERENCES `{$this->getTable('bumble_filter/filter')}` (`filter_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_ARMAH_VI` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Filter items to Stores';

");

 


$installer->endSetup();

