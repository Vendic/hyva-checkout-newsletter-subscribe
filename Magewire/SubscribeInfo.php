<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutNewsletterSubscribe\Magewire;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Newsletter\Model\GuestSubscriptionChecker;
use Magewirephp\Magewire\Component;

class SubscribeInfo extends Component
{
    public const HAS_SUBSCRIPTION = 'has_subscription';

    /**
     * @var bool
     */
    public $hasSubscription = false;

    /**
     * @var array
     */
    public $listeners = ['subscribe_info_email_address_updated' => 'hideIfHasSubscription'];

    public function __construct(
        private CheckoutSession $checkoutSession,
        private GuestSubscriptionChecker $guestSubscriptionChecker
    ) {
    }

    public function mount(): void
    {
        $this->hasSubscription = $this->checkoutSession->getData(self::HAS_SUBSCRIPTION) ?? false;
    }

    public function hideIfHasSubscription(string $email): void
    {
        $value = $this->guestSubscriptionChecker->isSubscribed($email);
        $this->checkoutSession->setData(self::HAS_SUBSCRIPTION, $value);
        $this->hasSubscription = $value;
    }
}
