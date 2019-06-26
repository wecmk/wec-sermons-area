<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class UploadedContent
{
    /** @var UploadedContentRange $uploadedContentRange */
    private $uploadedContentRange;
    
    /** @var Resource|string $uploadedContentRange */
    private $content;
    
    function __construct($uploadedContentRange, $content) {
        if ($uploadedContentRange instanceof UploadedContentRange) {
            $this->uploadedContentRange = $uploadedContentRange;
        } else {
            $this->uploadedContentRange = new UploadedContentRange($uploadedContentRange);
        }
        $this->content = $content;
    }
    
    function getUploadedContentRange(): UploadedContentRange
    {
        return $this->uploadedContentRange;
    }

    /**
     * 
     * @return string|resource
     */            
    function getContent() {
        return $this->content;
    }           
}
