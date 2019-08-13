<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS `{$this->getTable('bumble_filter/group')}`(
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_id` varchar(225),
  `title` varchar(225),
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL, 
  `file` varchar(255) NOT NULL,
  `tabs` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `{$this->getTable('bumble_filter/filter')}` ADD COLUMN `group_filter_id` int(11) DEFAULT '1' AFTER `filter_id`;
");

$installer->endSetup();