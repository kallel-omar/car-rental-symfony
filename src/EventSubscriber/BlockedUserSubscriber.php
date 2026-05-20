<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class BlockedUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private RouterInterface $router
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $user = $this->security->getUser();

        if (!$user) {
            return;
        }

        if ($user->isBlocked()) {

            $this->security->logout(false);

            $event->setResponse(
                new RedirectResponse(
                    $this->router->generate('app_login')
                )
            );
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
