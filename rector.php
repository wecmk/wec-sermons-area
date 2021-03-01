<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Define what rule sets will be applied
    $parameters->set(Option::SETS, [
        SetList::CODE_QUALITY,
        SetList::DOCTRINE_GEDMO_TO_KNPLABS,
        SetList::PERFORMANCE,
        SetList::SYMFONY_50,
        SetList::SYMFONY_50_TYPES,
        SetList::SYMFONY_CODE_QUALITY,
        SetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();

    // register a single rule
    $services->set(TypedPropertyRector::class);
    $services->set(NameImportingPostRector::class);
};
