<?php


namespace App\EventListener;

use App\Entity\Event;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UpdateShortIdEventListener
{
    // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Event) {
            return;
        }

        $em = $args->getObjectManager();
        $result = $em->createQueryBuilder()
            ->select('MAX(event.shortId)')
            ->from(Event::class, 'event' )
            ->OrderBy('event.shortId', 'DESC')
            ->getQuery()
            ->getSingleScalarResult();

        if ($entity->getShortId() == null) {
            $entity->setShortId(($result < 4000) ? 4000 : $result + 1);
        }
    }
}