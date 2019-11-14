<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use App\Repository\EventRepository;

/**
 * This API Controller supports the old version of the API, converting 
 * the old API methods to the new entities
 *
 * @Route("/api/v1/sermons", name="api_v1_sermons_")

 */
class ApiV1SermonsRestController extends AbstractFOSRestController {

    /**
     * Return all recorded events
     *
     * @Route("/all", name="all", methods={"GET"})
     */
    public function getAllSermonsAction(EventRepository $eventRepository) {
//        $this->denyAccessUnlessGranted('ROLE_API');
                
        $entities = $eventRepository->findAll();
        if (!$entities) {
            throw $this->createNotFoundException('Data not found.');
        }                
        return $this->view($entities, 200);
    }

    /**
     * Return a sermon by ID
     * 
     * @Route("/{id}", name="id")
     */
    public function getSermonById(EventRepository $eventRepository, $id) {
//        $this->denyAccessUnlessGranted('ROLE_API');
        $entity = $eventRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        } else if ($entity->isDeleted()) {
            $view = View::create();
            $view->setStatusCode(410);
            return $view;
        }
        return $this->view($entity, 200);
    }

    /**
     * Creates a new event.
     *
     * @Route("", name="new", methods={"POST"})
     * @return View
     */
    public function new(Request $request) {
//        $this->denyAccessUnlessGranted('ROLE_API');

        $em = $this->getDoctrine()->getManager();

        $serializer = $this->container->get('jms_serializer');
        /* @var $sermonNew \Wec\MediaBundle\Entity\Sermons */
        $sermonNew = $serializer->deserialize($request->getContent(), 'Wec\MediaBundle\Entity\Sermons', 'json');
        /* @var $sermon \Wec\MediaBundle\Entity\Sermons */
        $sermon = $this->container->get('doctrine')->getRepository('WecMediaBundle:Sermons')->findOneBy(
                array('download' => $sermonNew->getDownload())
        );

        if ($sermon != null) {
            $sermon->setDate($sermonNew->getDate());
            $sermon->setApm($sermonNew->getApm());
            $sermon->setReading($sermonNew->getReading());
            $sermon->setSecondReading($sermonNew->getSecondReading());
            $sermon->setTitle($sermonNew->getTitle());
            $sermon->setSpeaker($sermonNew->getSpeaker());
            $sermon->setDownload($sermonNew->getDownload());
            $sermon->setCorrupt($sermonNew->getCorrupt());
            $sermon->setTags($sermonNew->getTags());
        } else {
            $sermon = $sermonNew;
        }
        
        $seriesName = $sermonNew->getSeries()->getName();
        $sermon->setSeries(null);
        $series = $this->container->get('doctrine')->getRepository('WecMediaBundle:Series')->findOneBy(
                array('name' => $seriesName)
        );
        if ($series === null) {
            $series = new \Wec\MediaBundle\Entity\Series();
            $series->setName($seriesName);
        }
        $sermon->setSeries($series);
        $em->persist($sermon);
        $em->flush();

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->setStatusCode(201);
        $response->headers->set(
                'Location', $this->generateUrl(
                        'get_sermon_by_downloadid', array('downloadId' => $sermonNew->getDownload())
                )
        );
        return $response;
    }

    /**
     * Create a Sermon from the submitted data.<br/>
     *
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @Route("/download/{id}", name="update_sermon")
     * @Method({"PUT"})
     * @return View
     */
    public function putSermonAction(ParamFetcher $paramFetcher, $id) {
//        $this->denyAccessUnlessGranted('ROLE_API');
        $sermon = $this->getDoctrine()->getRepository('WecMediaBundle:Sermons')->findOneBy(
                array('download' => $paramFetcher->get('download'))
        );

        if ($paramFetcher->get('date')) {
            $sermon->setDate(new \DateTime($paramFetcher->get('date')));
        }
        if ($paramFetcher->get('apm')) {
            $sermon->setApm($paramFetcher->get('apm'));
        }
        if ($paramFetcher->get('series')) {
            $series = $this->container->get('doctrine')->getRepository('WecMediaBundle:Series')->findOneBy(array('name' => $paramFetcher->get('series')));
            if ($series === null) {
                $series = new \Wec\MediaBundle\Entity\Series();
                $series->setName($paramFetcher->get('series'));
            }
            $sermon->setSeries($series);
        }
        if ($paramFetcher->get('reading')) {
            $sermon->setReading($paramFetcher->get('reading'));
        }
        if ($paramFetcher->get('second_reading')) {
            $sermon->setSecondReading($paramFetcher->get('second_reading'));
        }
        if ($paramFetcher->get('title')) {
            $sermon->setTitle($paramFetcher->get('title'));
        }
        if ($paramFetcher->get('speaker')) {
            $sermon->setSpeaker($paramFetcher->get('speaker'));
        }
        if ($paramFetcher->get('download')) {
            $sermon->setDownload($paramFetcher->get('download'));
        }
        if ($paramFetcher->get('corrupt')) {
            $sermon->setCorrupt($paramFetcher->get('corrupt'));
        }
        if ($paramFetcher->get('tags')) {
            $sermon->setTags($paramFetcher->get('tags'));
        }
        $errors = $this->get('validator')->validate($sermon, array('Update'));
        if (count($errors) == 0) {
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($sermon);
            $em->flush();
            return $this->view("", 200);
        } else {
            return $this->view("", 400);
        }
    }

    /**
     * Delete a sermon
     *   
     * @param string $id 
     */
    public function deleteUserAction(EventRepository $eventRepository, \Doctrine\ORM\EntityManager $em, $id) {
//        $this->denyAccessUnlessGranted('ROLE_API');
        $entity = $eventRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }
        $entity->setDeletedAt(new \DateTime());
        $em->persist($entity);
        return $this->view("User deteled.")->setStatusCode(204);
    }

}
