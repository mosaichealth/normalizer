<?php
declare(strict_types=1);

namespace MosaicHealth\Normalizer;

use MosaicHealth\Normalizer\Implementation\DTO\DateTimeImmutableDTO;

/**
 * @author Guillaume MOREL <me@gmorel.io>
 */
trait NormalizerImplementationGuardTrait
{
    private function guardDTO(object $dto): void
    {
        if (get_class($dto) !== $this->getDTOQualifiedName()) {
            throw new \LogicException(
                sprintf(
                    'Normalizer "%s" is not accepting DTO "%". It accepts "%".',
                    self::class,
                    $dto::class,
                    $this->getDTOQualifiedName()
                )
            );
        }
    }

    private function guardEntity(object $entity): void
    {
        if (get_class($entity) !== $this->getEntityQualifiedName()) {
            throw new \LogicException(
                sprintf(
                    'Normalizer "%s" is not accepting Entity "%". It accepts "%".',
                    self::class,
                    $entity::class,
                    $this->getEntityQualifiedName()
                )
            );
        }
    }
}
