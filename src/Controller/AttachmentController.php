<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/attachment", name="attachment_")
 */
class AttachmentController extends AbstractController
{
    private $attachmentMetadataRepository;
    
    function __construct(\App\Repository\AttachmentMetadataRepository $attachmentMetadataRepository) {
        $this->attachmentMetadataRepository = $attachmentMetadataRepository;
    }

    /**
     * 
     * Optional GET parameter force-dl=true (default to force the download or 
     * false to stream
     * 
     * @Route("/{id}", name="index")
     */
    public function index(Request $request, $id)
    {
        $forceDownload = boolval($request->query->get("force-dl", true));        
        /** @var AttachmentMetadata $meta */
        $meta = $this->attachmentMetadataRepository->find($id);
        return $this->json($meta->getId());
    }
}
