<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Services\Filesystem\FilesystemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Entity\AttachmentMetadata;
use App\Services\Attachment\UploadService;
use App\Entity\CanBeDownloaded;

class AttachmentGetAction extends AbstractController
{
    public function __invoke(Request $request, FilesystemService $filesystemService, AttachmentMetadata $attachment): BinaryFileResponse
    {
        throw $this->createNotFoundException("asdf");
    }
}
