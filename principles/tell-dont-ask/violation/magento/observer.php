<?php

/** @var Mage_Sales_Model_Quote $quote */
$quote = Mage::getModel('sales/quote');
$quote->addItem($item);
$quote->setTriggerRecollect(1);
$quote->save();