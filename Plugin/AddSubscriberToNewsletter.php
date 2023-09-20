<?php
/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

namespace Vendic\HyvaCheckoutNewsletterSubscribe\Plugin;

use Hyva\Checkout\Model\Magewire\Payment\PlaceOrderServiceInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Psr\Log\LoggerInterface;
use Vendic\HyvaCheckoutNewsletterSubscribe\Magewire\SubscribeInput;

class AddSubscriberToNewsletter
{
    public function __construct(
        private SubscriptionManagerInterface $subscriptionManager,
        private CheckoutSession $checkoutSession,
        private LoggerInterface $logger,
    ) {
    }

    public function afterPlaceOrder(PlaceOrderServiceInterface $subject, int $result): int
    {
        if ($this->checkoutSession->getData(SubscribeInput::IS_SUBSCRIBED_KEY)) {
            $this->subscribe();
            $this->checkoutSession->unsetData(SubscribeInput::IS_SUBSCRIBED_KEY);
        }

        return $result;
    }

    private function subscribe(): void
    {
        $email = $this->checkoutSession->getQuote()->getCustomerEmail();
        $storeId = $this->checkoutSession->getQuote()->getStoreId();

        try {
            $this->isLoggedInCustomer() ?
                $this->subscriptionManager->subscribeCustomer(
                    $this->checkoutSession->getQuote()->getCustomerId(),
                    $storeId
                ) :
                $this->subscriptionManager->subscribe($email, $storeId);
        } catch (\Exception $e) {
            $this->logger->error(
                sprintf('Could not subscribe %s to newsletter %s', $email, $e->getMessage())
            );
        }
    }

    private function isLoggedInCustomer(): bool
    {
        return $this->checkoutSession->getQuote()->getCustomerGroupId() !== GroupInterface::NOT_LOGGED_IN_ID;
    }
}
