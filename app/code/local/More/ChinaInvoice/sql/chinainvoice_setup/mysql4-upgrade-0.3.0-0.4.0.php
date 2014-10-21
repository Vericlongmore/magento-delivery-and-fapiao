<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Sales_Model_Mysql4_Setup */
$installer->run("ALTER TABLE {$this->getTable('sales_flat_order')}
MODIFY COLUMN `bill_type`  enum('VAT Invoice','General Invoice') DEFAULT  NULL COMMENT 'Bill Type' AFTER `coupon_rule_name`,
MODIFY COLUMN `bill_content`  enum('Consumable','Computer Accessories','Office Supplies','Detail')  DEFAULT NULL COMMENT 'Bill Content' AFTER `bill_type`,
MODIFY COLUMN `bill_title`  enum('Units','Personal') DEFAULT NULL COMMENT 'Bill Title' AFTER `bill_content`;
");



$installer->endSetup();
