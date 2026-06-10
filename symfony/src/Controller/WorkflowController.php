<?php
namespace App\Controller;

use App\Entity\PullRequest;
use App\Enum\PullRequestState;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Workflow\WorkflowInterface;

class WorkflowController extends AbstractController
{
    #[Route('/workflow', methods: ['GET'])]
    public function workflow(
        // Use the #[Target] attribute to inject a specific workflow in any service or controller. 
        // Symfony creates a target with the same name as each workflow.
        #[Target('pull_request')] WorkflowInterface $pullRequestStateMachine,

        //---------------------------------------------
        // Inject multiple workflows or state machines 

        // 'workflow' is the service tag name and injects both workflows and state machines;
        // 'name' tells Symfony to index services using that tag property
        #[AutowireLocator('workflow', 'name')]
        ServiceLocator $workflows,

        // You can also inject only workflows or only state machines
        #[AutowireLocator('workflow.workflow', 'name')]
        ServiceLocator $workflow,

        #[AutowireLocator('workflow.state_machine', 'name')]
        ServiceLocator $stateMachine

    ): Response
    {
        $pullRequest = new PullRequest();

        // You don't need to set the initial marking in the constructor or any other method;
        // this is configured in the workflow with the 'initial_marking' option

        $pullRequest->setState(PullRequestState::Start);

        /*
         * Service "state_machine.pull_request" not found: even though it exists in the app's container, 
         * the container inside "App\Controller\DefaultController" is a smaller service locator that only 
         * knows about the "router", "request_stack", "http_kernel", "security.authorization_checker", 
         * "twig", "security.token_storage", "security.csrf.token_manager" and "parameter_bag" services. 
         * Try using dependency injection instead.
         * 
         *   $pullRequestStateMachine = $this->container->get('state_machine.pull_request');
         */

        $pullRequestStateMachine->can($pullRequest, 'submit');          // true
        $pullRequestStateMachine->can($pullRequest, 'update');          // false
        $pullRequestStateMachine->can($pullRequest, 'wait_for_review'); // false
        $pullRequestStateMachine->can($pullRequest, 'request_change');  // false
        $pullRequestStateMachine->can($pullRequest, 'accept');          // false
        $pullRequestStateMachine->can($pullRequest, 'reject');          // false
        
        // See all the available transitions for the post in the current state
        $transitions = $pullRequestStateMachine->getEnabledTransitions($pullRequest);

        // See a specific available transition for the post in the current state
        $transition = $pullRequestStateMachine->getEnabledTransition($pullRequest, 'coding');

        try {
            // start -> test
            $pullRequestStateMachine->apply($pullRequest, 'submit', [
                'log_comment' => 'My logging comment for the wait for review transition.',
            ]);
        } catch (LogicException $exception) {
            // ...
        }

        //---------------------------------------------
        // Inject multiple workflows or state machines 

        // If you use the 'name' tag property to index services (see constructor above),
        // you can get workflows by their name; otherwise, you must use the full
        // service name with the 'workflow.' prefix (e.g. 'workflow.user_registration')
        $workflow = $workflows->get('make_table');

        //-------
        // Event

        // If you don't need the announce event, disable it using the context

        // test -> review
        $pullRequestStateMachine->apply($pullRequest, 'wait_for_review', [Workflow::DISABLE_ANNOUNCE_EVENT => true]);

        return new Response(
            '<html><body>Workflow</body></html>'
        );
    }
}
