<?php
/**
 * This file is part of the ramsey/uuid-doctrine library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <http://benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @link https://packagist.org/packages/ramsey/uuid-doctrine Packagist
 * @link https://github.com/ramsey/uuid-doctrine GitHub
 */

namespace App\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

/**
 * UUID generator. If an ID is already set, use that and do not generate a new UUID
 */
class CustomUuidGenerator extends AbstractIdGenerator
{
    /**
     * Generate an identifier
     *
     * @param \Doctrine\ORM\EntityManager  $em
     * @param \Doctrine\ORM\Mapping\Entity $entity
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function generate(EntityManager $em, $entity)
    {
        if (null !== $entity->getId() && "" !== $entity->getId()) {
            return $entity->getId();
        } else {
            return \Ramsey\Uuid\Uuid::uuid4();
        }
    }
}
