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
    public function getAllSermonsAction(SerializerInterface $serialiser)
    {
        $sermonsRepository = $this->getDoctrine()->getRepository(\App\Entity\Sermon::class);
        $entities = $sermonsRepository->findAll();
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
     * Return an user identified by username/email.
     *
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
     * Create a Sermon from the submitted data.<br/>
     *
     *
     * @param ParamFetcher $paramFetcher Paramfetcher

     * @Route("/download/{id}", name="update_sermon")
     * @Method({"PUT"})
     * @return View
     */
    public function putSermonAction(ParamFetcher $paramFetcher, $id)
    {
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
        $view = View::create();
        $errors = $this->get('validator')->validate($sermon, array('Update'));
        if (count($errors) == 0) {
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($sermon);
            $em->flush();
            $view->setStatusCode(204);
            return $view;
        } else {
            $view = $this->getErrorsView($errors);
            return $view;
        }
    }

    /**
     * Delete an user identified by username/email.
     *

     * @param string $slug username or email
     *
     * @return View
     */
    public function deleteUserAction($slug)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $entity = $userManager->findUserByUsernameOrEmail($slug);
        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }
        $userManager->deleteUser($entity);
        $view = View::create();
        $view->setData("User deteled.")->setStatusCode(204);
        return $view;
    }
}
