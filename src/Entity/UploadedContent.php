<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class UploadedContent
{
    /** @var UploadedContentRange $uploadedContentRange */
    private $uploadedContentRange;

    /**
     * @param resource|string $content
     */
    public function __construct($uploadedContentRange, private $content)
    {
        if ($uploadedContentRange instanceof UploadedContentRange) {
            $this->uploadedContentRange = $uploadedContentRange;
        } else {
            $this->uploadedContentRange = new UploadedContentRange($uploadedContentRange);
        }
    }

    public function getUploadedContentRange(): UploadedContentRange
    {
        return $this->uploadedContentRange;
    }

    /**
     *
     * @return string|resource
     */
    public function getContent()
    {
        return $this->content;
    }
}
