<?php

/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */

declare(strict_types=1);

namespace Vendic\HyvaCheckoutNewsletterSubscribe\Model\Form\Field;

use Hyva\Checkout\Model\Form\AbstractEntityForm;
use Hyva\Checkout\Model\Form\EntityField\AbstractEntityField;
use Hyva\Checkout\Model\Form\EntityForm\GuestDetailsForm;
use Hyva\Checkout\Model\Form\EntityFormInterface;
use Hyva\Checkout\Model\Form\EntityFormModifierInterface;
use Magewirephp\Magewire\Component;

/**
 * Hides the newsletter option when the email in the Guest Details component is already subscribed.
 */
class HideSubscribeInfoModifier implements EntityFormModifierInterface
{
    private const string EMAIL_PROPERTY = 'data.' . GuestDetailsForm::FIELD_EMAIL;

    public function apply(EntityFormInterface $form): EntityFormInterface
    {
        // Hyvä Checkout 3 binds the email deferred, send it as soon as it changes. Hyvä Checkout 1.x binds it with
        // wire:model.defer and auto-saves it, so there is nothing to replace.
        $form->registerModificationListener(
            'subscribe_info_save_guest_email_on_change',
            'form:init',
            fn (AbstractEntityForm $form) => $form->modifyField(
                GuestDetailsForm::FIELD_EMAIL,
                fn (AbstractEntityField $field) => $field->replaceAttribute('wire:model', 'wire:model.lazy')
            )
        );

        $form->registerModificationListener(
            'hide_subscribe_info_if_has_subscription',
            'form:updated:magewire',
            function (EntityFormInterface $form, Component $component, string $property, mixed $value) {
                if ($property === self::EMAIL_PROPERTY) {
                    // Magewire 1 and 3 both pass a single non-array argument on as is.
                    // @phpstan-ignore argument.type
                    $component->emit('subscribe_info_email_address_updated', (string) $value);
                }

                return $form;
            }
        );

        return $form;
    }
}
