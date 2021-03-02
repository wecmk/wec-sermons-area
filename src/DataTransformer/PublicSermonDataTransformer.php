<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;

class CheeseListingOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        // TODO: Implement transform() method.
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // TODO: Implement supportsTransformation() method.
    }
}