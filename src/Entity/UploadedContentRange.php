<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

/**
 * Description of UploadedContentRange
 *
 * @author samuel
 */
class UploadedContentRange
{
    public function __construct(private readonly string $rangeHeader)
    {
    }

    public function startsAt()
    {
        $matches = [];
        preg_match("|bytes ([0-9]+)-[0-9]+/[0-9]|", $this->rangeHeader, $matches);
        return $matches[1];
    }

    public function endsAt()
    {
        $matches = [];
        preg_match("|bytes [0-9]+-([0-9]+)/[0-9]|", $this->rangeHeader, $matches);
        return $matches[1];
    }

    public function totalSize()
    {
        $matches = [];
        preg_match("|bytes [0-9]+-[0-9]+/([0-9]+)|", $this->rangeHeader, $matches);
        return $matches[1];
    }

    public function length()
    {
        return ($this->endsAt() + 1) - $this->startsAt();
    }
}
