<?php

/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

declare(strict_types=1);

namespace Vendic\HyvaCheckoutNewsletterSubscribe\Magewire;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magewirephp\Magewire\Component;
use Vendic\HyvaCheckoutNewsletterSubscribe\Model\Config;

class SubscribeInput extends Component
{
    public const IS_SUBSCRIBED_KEY = 'is_subscribed';

    /**
     * @var bool|null
     */
    public $subscribed = null;

    public function __construct(
        private CheckoutSession $checkoutSession,
        private Config $config
    ) {
    }

    public function mount(): void
    {
        $this->subscribed
            = $this->checkoutSession->getData(self::IS_SUBSCRIBED_KEY) ?? $this->config->isCheckboxInitiallyEnabled();

        $this->checkoutSession->setData(self::IS_SUBSCRIBED_KEY, $this->subscribed);
    }

    public function updatedSubscribed(mixed $value) : mixed
    {
        $this->checkoutSession->setData(self::IS_SUBSCRIBED_KEY, (bool) $value);

        return $value;
    }
}
