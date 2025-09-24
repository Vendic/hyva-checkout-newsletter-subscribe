<?php

/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

declare(strict_types=1);

namespace  Vendic\HyvaCheckoutNewsletterSubscribe\Service;

use Magento\Framework\App\ResourceConnection;
use Magento\Newsletter\Model\Subscriber;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */
class NewsletterSubscriptionChecker
{
    public function __construct(
        private ResourceConnection $resourceConnection,
        private StoreManagerInterface $storeManager
    ) {
    }

    /**
     * Check is subscribed by email
     */
    public function isSubscribed(string $subscriberEmail): bool
    {
        if (!empty($subscriberEmail)) {
            $storeIds = $this->storeManager->getWebsite()->getStoreIds();
            $connection = $this->resourceConnection->getConnection();
            $select = $connection
                ->select()
                ->from($this->resourceConnection->getTableName('newsletter_subscriber'))
                ->where('subscriber_email = ?', $subscriberEmail)
                ->where('subscriber_status = ?', Subscriber::STATUS_SUBSCRIBED)
                ->where('store_id IN (?)', $storeIds)
                ->limit(1);

            return (bool)$connection->fetchOne($select);
        }

        return false;
    }
}
