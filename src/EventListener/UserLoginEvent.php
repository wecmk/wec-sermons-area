<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class UserLoginEvent implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess'
        ];
    }
    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        if ($this->security->isGranted('ROLE_API')) {
            /** @var User $user */
            $user = $event->getUser();
            if ($user->getExpires() < time()) {
                $event->setResponse(new RedirectResponse("/connect/google"));
            }
        }
    }
}