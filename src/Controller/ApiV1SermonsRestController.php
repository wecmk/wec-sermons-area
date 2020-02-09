<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use App\Repository\EventRepository;
use App\Entity\Event;

/**
 * This API Controller supports the old version of the API, converting
 * the old API methods to the new entities
 *
 * @Route("/api/v1/sermons", name="api_v1_sermons_")

 */
class ApiV1SermonsRestController extends AbstractFOSRestController
{

    /**
     * Return all recorded events
     *
     * @Route("/all", name="all", methods={"GET"})
     */
    public function getAllSermonsAction(EventRepository $eventRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_API');
                
        $entities = $eventRepository->findAll();
        return $this->view($entities, 200);
    }

    /**
     * Return a sermon by ID
     *
     * @Route("/{id}", name="id", methods={"GET"})
     */
    public function getSermonById(EventRepository $eventRepository, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_API');
        $entity = $eventRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        } elseif ($entity->isDeleted()) {
            $view = View::create();
            $view->setStatusCode(410);
            return $view;
        }
        return $this->view($entity, 200);
    }

    /**
     * Creates a new event.
     *
     * @Route("", name="new", methods={"POST"}, defaults={"id"=""})
     */
    public function new(
        Request $request,
        EventRepository $eventRepository,
        \App\Services\Event\EventService $eventService,
        \App\Services\Speaker\SpeakerService $speakerService,
        \App\Services\Series\SeriesService $seriesService
    ) {
        $this->denyAccessUnlessGranted('ROLE_API');
        $body = json_decode($request->getContent(), true);
        $id = $body['id'];
        
        /* @var $event Event */
        if (!empty($id)) {
            $event = $eventRepository->find($id);
        }
        
        if (empty($event)) {
            $event = new Event();
            // ID's are globally set and adopted from the client if set
            // If ID is not set, then it can be autogenerated
            if ($id != "") {
                $event->setId(\Ramsey\Uuid\Uuid::fromString($id));
            }
        }
                
        $event->setDate(\DateTime::createFromFormat("U", strtotime($body['date'])));
        $event->setApm($body["apm"]);
        $event->setReading($body["reading"]);
        $event->setSecondReading(isset($body["secondReading"]) ? $body["secondReading"] : "");
        $event->setTitle($body["title"]);
        
        // Get SPeaker from Doctrine
        $speaker = $speakerService->findBy($body["speaker"]);
        if ($speaker == null) {
            $speaker = $speakerService->create($body["speaker"]);
        }
        $event->setSpeaker($speaker);
        
        // Get Series
        $series = $body["series"];
        $seriesList = explode("/", $body["series"]['name']);
        
        foreach ($seriesList as $seriesName) {
            $seriesItem = $seriesService->findBy($seriesName);
            if ($seriesItem == null) {
                $event->addSeries($seriesService->create($seriesName));
            } else {
                $event->addSeries($seriesItem);
            }
        }
        
        $existingSeries = $event->getSeries();
        foreach ($existingSeries as $existing) {
            if (!in_array($existing, $seriesList)) {
                $event->removeSeries($existing);
            }
        }
        
        $event->setCorrupt(boolval($body["corrupt"]));
        $event->setTags(isset($body["tags"]) ? $body["tags"] : "");

        $event->setLegacyId(isset($body["download"]) ? $body["download"] : null);
        
        return $this->json($eventService->add($event));
    }

    /**
     * Delete a Sermon
     *
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param string $id
     */
    public function deleteSermonAction(\App\Services\Event\EventService $eventService, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_API');
        $eventService->deleteById($id);
        return $this->view("Sermon deleted.")->setStatusCode(204);
    }
}
