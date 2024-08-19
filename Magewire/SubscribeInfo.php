<?php declare(strict_types=1);
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutNewsletterSubscribe\Magewire;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Newsletter\Model\GuestSubscriptionChecker;
use Magewirephp\Magewire\Component;
use Vendic\HyvaCheckoutNewsletterSubscribe\Service\NewsletterSubscriptionChecker;

class SubscribeInfo extends Component
{
    public const HAS_SUBSCRIPTION = 'has_subscription';

    /**
     * @var bool
     */
    public $hidden = false;

    /**
     * @var array
     */
    public $listeners = ['subscribe_info_email_address_updated' => 'hideIfHasSubscription'];

    public function __construct(
        private CheckoutSession $checkoutSession,
        private NewsletterSubscriptionChecker $newsletterSubscriptionChecker
    ) {
    }

    public function mount(): void
    {
        if (!$this->checkoutSession->getQuote()->getCustomer()->getId()) {
            return;
        }

        // Hide for logged in customers that are already subscribed
        $email = $this->checkoutSession->getQuote()->getCustomer()->getEmail();
        $this->hidden = $this->newsletterSubscriptionChecker->isSubscribed($email);
    }

    public function hideIfHasSubscription(?string $email): void
    {
        if (!$email) {
            $this->hidden = false;
            return;
        }

        if (!$this->newsletterSubscriptionChecker->isSubscribed($email)) {
            $this->hidden = false;
            return;
        }
        $this->hidden = true;
    }
}
