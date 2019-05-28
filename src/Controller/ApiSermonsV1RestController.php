<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use JMS\Serializer\SerializerInterface;
use Wec\MediaBundle\Entity\Series;

/**
 * Download controller.
 *
 * @Route("/api/v1/sermons", name="api_sermons_")
 */
class ApiSermonsV1RestController extends AbstractFOSRestController
{
    
    /**
     * Return the overall user list.
     *
     * @Route("", name="get_all_sermons", methods={"GET"})
     */
    public function getAllSermonsAction()
    {
        $sermonsRepository = $this->getDoctrine()->getRepository(\App\Entity\Sermon::class);
        $entities = $sermonsRepository->findAll();
        if (!$entities) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $this->view($entities, 200);        
    }
    
    /**
     * Return sermon by id
     *
     * @Route("/{id}", name="get_sermon", methods={"GET"})
     */
    public function getSermonsAction($id)
    {
        $sermonsRepository = $this->getDoctrine()->getRepository(\App\Entity\Sermon::class);
        $entities = $sermonsRepository->find($id);
        if (!$entities) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $this->view($entities, 200);        
    }

    /**
     * Creates a new sermon record.
     *
     * @param Request $request
     *
     * @return Response|View
     * @param string $downloadId DownloadId
     * @Route("", name="post_new_sermon", methods={"POST"})
     */
    public function newAction(Request $request, 
            \App\Services\Sermons\SermonsService $sermonsService,
            \App\Services\Series\SeriesService $seriesService, 
            \App\Services\Speaker\SpeakerService $speakerService)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var $sermonNew App\Entity\Sermon */
        
        
        $sermon = new \App\Entity\Sermon();
        
        $sermon->setId(\Ramsey\Uuid\Uuid::uuid4());
        $sermon->setDate(\DateTime::createFromFormat("U", strtotime($request->get("date", ""))));
        $sermon->setApm(strtoupper($request->get("apm", "")));
        $seriesList = explode('/', $request->get('series', ""));
        
        foreach ($seriesList as $value) {
            $series = $seriesService->findBy($value);
            if (empty($series)) {
                $series = array($seriesService->create($value));
            } 
            $sermon->addSeries($series[0]);
        }
        
        $sermon->setReading($request->get("reading", ""));
        $sermon->setSecondReading($request->get("secondReading", ""));
        $sermon->setTitle($request->get("title", ""));
        $sermon->setCorrupt(boolval($request->get("corrupt", false)));
        $sermon->setIsPublic(boolval($request->get("isPublic", true)));
        $sermon->setTags($request->get("tags", ""));
        $sermon->setPublicComments($request->get("publicComments", ""));
        $sermon->setPrivateComments($request->get("privateComments", ""));
        
        $speakerString = $request->get("speaker", "");
        $speaker = $speakerService->findBy($speakerString);
        if (empty($speaker)) {
            $speaker = array($speakerService->create($speakerString));
        }
        $sermon->setSpeaker($speaker[0]);
        
        $sermonsService->add($sermon);

        return $this->view($sermon, 201);
    }

    /**
     * Delete a sermon by id
     *
     * @Route("/{id}", name="delete_sermon_by_id", methods={"DELETE"})
     * 
     * @param string $id the ID of the sermon
     * @return View
     */
    public function deleteSermon($id)
    {
        throw new \Exception("Action not implemented yet!");
    }
}
