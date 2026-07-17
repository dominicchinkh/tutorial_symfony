import { Controller } from '@hotwired/stimulus';

import { getComponent } from '@symfony/ux-live-component';

export default class extends Controller {
    async initialize() {
        this.component = await getComponent(this.element);

        /*
            The JavaScript Component object has a number of hooks that you can use to run code during the 
            lifecycle of your component.

            | Hook                   | Arguments
            |------------------------|-----------------------------------------------------------------------------
            | connect                | component: Component
            | disconnect             | component: Component
            | render:started         | html: string, response: BackendResponse, controls: { shouldRender: boolean }
            | render:finished        | component: Component
            | response:error         | backendResponse: BackendResponse, controls: { displayError: boolean }
            | loading.state:started  | element: HTMLElement, request: BackendRequest
            | loading.state:finished | element: HTMLElement
            | model:set              | model: string, value: any, component: Component
        */

        this.component.on('render:finished', (component) => {
            // Do something after the component re-renders
        });
    }

    connect() {
        this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/alert_controller.js';
    }

    // Stimulus action triggered, for example, on user click
    toggleMode() {
        // Set some live property called "mode" on your component
        this.component.set('mode', 'editing');

        // Then, trigger a re-render to get the fresh HTML
        this.component.render();

        // Call an action
        this.component.action('save', { arg1: 'value1' });
    }
}
