<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
  * @Route("/files", name="files_")
  */
class FilesController extends AbstractController
{
    /**
     * Download file
     *
     * @Route("/download/{id}", name="download")
     */
    public function download($id)
    {
        return $this->render('files/index.html.twig', [
            'controller_name' => 'FilesController',
        ]);
    }
    
    /**
     * Steam file
     *
     * @Route("/stream/{id}", name="steam")
     */
    public function steam($id)
    {
        return $this->render('files/index.html.twig', [
            'controller_name' => 'FilesController',
        ]);
    }
}
