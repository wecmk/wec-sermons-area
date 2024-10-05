<?php

namespace App\Controller;

use App\DataProvider\YouTubeLiveServicesProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    /***
     * A ployfill for ApiPlatform to return live services for website
     * @param YouTubeLiveServicesProvider $liveServicesProvider
     * @return Response
     */
    #[Route('/api/you_tube_live_services', name: 'app_api_youtube_live_services')]
    public function index(YouTubeLiveServicesProvider $liveServicesProvider): Response
    {
        /* @var \App\Entity\YouTubeLiveServices $collection */
        $collection = $liveServicesProvider->getCollection("", "")[0];
        $string = <<< HERE
{
  "@context": "/api/contexts/YouTubeLiveServices",
  "@id": "/api/you_tube_live_services",
  "@type": "hydra:Collection",
  "hydra:member": [
    {
      "@id": "/api/you_tube_live_services/1",
      "@type": "YouTubeLiveServices",
      "id": 1,
      "nextWeekAm": "{$collection->getNextWeekAm()}",
      "nextWeekPm": "{$collection->getNextWeekPm()}",
      "lastWeekAm": "{$collection->getLastWeekAm()}",
      "lastWeekPm": "{$collection->getLastWeekPm()}"
    }
  ],
  "hydra:totalItems": 1
}       
HERE;
        return $this->json(json_decode($string), Response::HTTP_OK);
    }
}
