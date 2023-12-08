<?php declare(strict_types=1);

namespace Vendic\HyvaCheckoutNewsletterSubscribe\Model\Form\Field;

use Hyva\Checkout\Magewire\Checkout\AddressView\MagewireAddressFormInterface;
use Hyva\Checkout\Model\Form\EntityFieldInterface;
use Hyva\Checkout\Model\Form\EntityFormInterface;
use Hyva\Checkout\Model\Form\EntityFormModifierInterface;
use Magewirephp\Magewire\Component;

/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */
class HideSubscribeInfoModifier implements EntityFormModifierInterface
{
    public function apply(EntityFormInterface $form): EntityFormInterface
    {
        $form->registerModificationListener(
            'hide_subscribe_info_if_has_subscription',
            'form:shipping:email:updated',
            function (
                EntityFormInterface $form,
                EntityFieldInterface $field,
                MagewireAddressFormInterface $addressComponent
            ) {
                /** @var Component $addressComponent */
                $addressComponent->emit('subscribe_info_email_address_updated', $field->getValue());
                return $form;
            }
        );

        return $form;
    }
}
