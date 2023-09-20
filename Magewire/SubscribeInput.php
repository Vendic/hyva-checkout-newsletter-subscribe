<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutNewsletterSubscribe\Magewire;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magewirephp\Magewire\Component;

class SubscribeInput extends Component
{
    public const IS_SUBSCRIBED_KEY = 'is_subscribed';

    /**
     * @var bool
     */
    public $subscribed;

    public function __construct(private CheckoutSession $checkoutSession)
    {
    }

    public function mount(): void
    {
        $this->subscribed = $this->checkoutSession->getData(self::IS_SUBSCRIBED_KEY) ?: false;
    }

    public function updatedSubscribed(mixed $value) : mixed
    {
        $this->checkoutSession->setData(self::IS_SUBSCRIBED_KEY, (bool) $value);

        return $value;
    }
}
