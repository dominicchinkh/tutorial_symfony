<?php

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\UX\TwigComponent\Event\PreRenderEvent;

class TwigSubscriber implements EventSubscriberInterface
{
    // PreRenderEvent:
    //   Give the ability to modify the twig template and twig variables before 
    //   components are rendered.

    // PostRenderEvent: 
    //   It is called after a component has finished rendering and contains the
    //   MountedComponent that was just rendered.

    // PreCreateForRenderEvent:
    //   Give the ability to be notified before a component object is created or 
    //   hydrated, at the very start of the rendering process. You have access to
    //   the component name, input props and can interrupt the process by setting 
    //   HTML. This event is not triggered during a re-render.

    // PreMountEvent or PostMountEvent: 
    //   To run code just before or after a component's data is mounted

    public function onPreRender(PreRenderEvent $event): void
    {
        $event->getComponent();     // The component object
        $event->getTemplate();      // The twig template name that will be rendered
        $event->getVariables();     // The variables that will be available in the template

        // Change the template used
        // $event->setTemplate('some_other_template.html.twig'); 

        // manipulate the variables:
        $variables = $event->getVariables();
        $variables['custom'] = 'value';

        // {{ custom }} will be available in template
        $event->setVariables($variables); 
    }

    public static function getSubscribedEvents(): array
    {
        return [PreRenderEvent::class => 'onPreRender'];
    }
}
