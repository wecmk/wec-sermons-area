<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\Serializer\SerializerInterface;
/**
 * Download controller.
 *
 * @Route("/api/v2/sermons", name="api_sermons")
 */
class ApiSermonsV1RestController extends AbstractController
{
    
    /**
     * Return the overall user list.
     *
     * @Route("/all", name="get_all_sermons")     
     */
    public function getAllSermonsAction(SerializerInterface $serialiser)
    {
        $sermonsRepository = $this->getDoctrine()->getRepository(\App\Entity\Sermon::class);
        $entities = $sermonsRepository->findAll();
        if (!$entities) {
            throw $this->createNotFoundException('Data not found.');
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($serialiser->serialize($entities, 'json'), 200, [], true);
    }

}
