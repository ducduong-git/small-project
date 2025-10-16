<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionCheckSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;
    private RequestStack $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request?->getSession();

        // Skip if no session or not a main request
        if (!$event->isMainRequest() || !$session) {
            return;
        }

        // $route = $request->attributes->get('_route');

        // // 🟢 Public routes that do NOT need session
        // $publicRoutes = ['app_login', 'app_register', 'app_home'];

        // if (in_array($route, $publicRoutes, true)) {
        //     return;
        // }

        // // 🔒 Check if the session has user info
        // if (!$session->has('user_id')) {
        //     // Redirect to login page
        //     $loginUrl = $this->router->generate('app_login');
        //     $event->setController(fn() => new RedirectResponse($loginUrl));
        // }

        $path = $request->getPathInfo();

        if (str_starts_with($path, '/admin')) {
            if (!$session->has('user_id')) {
                $event->setController(fn() => new RedirectResponse('/'));
            }

            if ($session->get('user_permission') == 0) {
                $event->setController(fn() => new RedirectResponse('/'));
            }
        }

        if (str_starts_with($path, '/admin/permission')) {
            if (!$session->has('user_id')) {
                $event->setController(fn() => new RedirectResponse('/error'));
            }

            if ($session->get('user_permission') != 1) {
                $event->setController(fn() => new RedirectResponse('/error'));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
