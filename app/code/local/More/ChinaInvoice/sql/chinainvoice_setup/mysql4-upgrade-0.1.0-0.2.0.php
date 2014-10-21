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

$installer->addAttribute('order', 'bill_type', array('type'=>'int'));
$installer->addAttribute('order', 'bill_content', array('type'=>'int'));
$installer->addAttribute('order', 'bill_title', array('type'=>'varchar'));
$installer->addAttribute('order', 'bill_taxpayer_id', array('type'=>'varchar'));
$installer->addAttribute('order', 'bill_phone', array('type'=>'varchar'));
$installer->addAttribute('order', 'bill_bank', array('type'=>'varchar'));
$installer->addAttribute('order', 'bill_account', array('type'=>'varchar'));
$installer->addAttribute('order', 'bill_address', array('type'=>'varchar'));
$installer->addAttribute('order', 'bill_company', array('type'=>'varchar'));

$installer->endSetup();
