<?php 
$installer = $this; 
$installer->startSetup();
$installer->run("
 ALTER TABLE ".$this->getTable('sales_flat_order')." ADD COLUMN (
        `delivery_time` varchar(25) NULL default '',
        `delivery_content` varchar(255) NULL default ''
 );
");
$installer->endSetup();