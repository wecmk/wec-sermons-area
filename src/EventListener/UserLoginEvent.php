<?php

namespace App\EventListener;

use App\Entity\User;
use App\Services\Google\GoogleCredentials;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class UserLoginEvent implements EventSubscriberInterface
{
    private \Symfony\Bundle\SecurityBundle\Security $security;
    private GoogleCredentials $googleCredentials;

    public function __construct(\Symfony\Bundle\SecurityBundle\Security $security, GoogleCredentials $googleCredentials)
    {
        $this->security = $security;
        $this->googleCredentials = $googleCredentials;
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
            if ($this->googleCredentials->getExpires() - (15 * 60) < time()) {
                $event->setResponse(new RedirectResponse("/connect/google"));
            }
        }
    }
}
