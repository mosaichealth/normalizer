<?php
declare(strict_types=1);

namespace MosaicHealth\Normalizer;


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
                    'Normalizer "%s" is not accepting DTO "%s". It accepts "%s".',
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
                    'Normalizer "%s" is not accepting Entity "%s". It accepts "%s".',
                    self::class,
                    $entity::class,
                    $this->getEntityQualifiedName()
                )
            );
        }
    }
}
