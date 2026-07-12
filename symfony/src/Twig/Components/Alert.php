<?php

namespace App\Twig\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\FromMethod;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent(
    // It is possible to reference a component method by passing FromMethod to the template option
    template: new FromMethod('getTemplate')
)]

// You can disable exposing public properties for a component. When disabled, 
// `this.property` must be used
// #[AsTwigComponent(exposePublicProps: false)]

final class Alert
{
    use DefaultActionTrait;

    public string $type = 'success';
    public string $message;

    public function getTemplate(): string
    {
        return 'components/Alert.html.twig';
    }

    // If you need to modify/validate data before it's mounted on the component use a 
    // PreMount hook

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        // In its default configuration, the OptionsResolver treats all props. However, 
        // if more props are passed than the options defined in the OptionsResolver, an 
        // error will be prompted, indicating that one or more options do not exist. To 
        // avoid this, use the ignoreUndefined() method with true. 
        $resolver->setIgnoreUndefined(true);

        $resolver->setDefaults(['type' => 'success']);
        $resolver->setAllowedValues('type', ['success', 'danger']);
        $resolver->setRequired('message');
        $resolver->setAllowedTypes('message', 'string');

        // The major drawback of this configuration is that the OptionsResolver will remove 
        // every non-defined option when resolving data. To maintain props that have not been 
        // defined within the OptionsResolver, combine the data from the hook with the resolved 
        // data.
        return $resolver->resolve($data) + $data;
    }

    // `mount` is called once, immediately after the component is instantiated, 
    // but before the component system assigns the props you passed when rendering.

    // Note: mount() can also receive props even when no matching public property exists.

    public function mount(string $type): void
    {
        // ❌ This won't work: at this point $type still has its default value.
        // Passed values are not yet available in props.
        if ('error' === $this->type) {
        }

        // ✅ this works as expected: the $type argument in PHP has the value
        // passed to the 'type' prop in the Twig template
        if ('error' === $type) {
        }
    }

    // After a component is instantiated and its data mounted, you can run extra code 
    // via the PostMount hook

    // A PostMount method can also receive an array $data argument, which will contain 
    // any props passed to the component that have not yet been processed, (i.e. they 
    // don't correspond to any property and weren't an argument to the mount() method). 
    // You can handle these props, remove them from the $data and return the array.

    #[PostMount]
    public function postMount(array $data): array
    {
        if ($data['autoChooseType'] ?? false) {
            if (str_contains($this->message, 'danger')) {
                $this->type = 'danger';
            }

            // remove the autoChooseType prop from the data array
            unset($data['autoChooseType']);
        }

        // Any remaining data will become attributes on the component
        return $data;
    }
}
