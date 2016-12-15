<?php

// Inside your observer:
Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet()->__toString();
// is violation, but O.K. for Magento.