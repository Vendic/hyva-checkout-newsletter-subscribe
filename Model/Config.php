<?php

/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

declare(strict_types=1);

namespace Vendic\HyvaCheckoutNewsletterSubscribe\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const HYVA_CHECKOUT_NEWSLETTER_SUBSCRIBE_IS_CHECKBOX_INITIALLY_ENABLED = 'hyva_checkout_newsletter_subscribe/general/enabled';

    public function __construct(
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isCheckboxInitiallyEnabled(int $store = 0): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::HYVA_CHECKOUT_NEWSLETTER_SUBSCRIBE_IS_CHECKBOX_INITIALLY_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
