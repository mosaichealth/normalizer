<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Implementation\Normalizer;

use MosaicHealth\Normalizer\Implementation\DTO\DateTimeImmutableDTO;
use MosaicHealth\Normalizer\Normalizer;
use MosaicHealth\Normalizer\NormalizerImplementationGuardTrait;
use MosaicHealth\Normalizer\NormalizerInterface;

/**
 * Normalizer: This object is responsible to transform an Entity/ValueObject into a serializable DTO.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
class DateTimeImmutableNormalizer implements NormalizerInterface
{
    use NormalizerImplementationGuardTrait;

    /**
     * {@inheritdoc}
     *
     * @param DateTimeImmutableDTO $dto
     * @param \DateTimeImmutable   $entity
     */
    public function transform($dto, $entity, Normalizer $normalizer, array $options = [])
    {
        $this->guardDTO($dto);
        $this->guardEntity($entity);

        $dto->dateTime = $entity;
    }

    /**
     * @inheritDoc
     */
    public function getEntityQualifiedName(): string
    {
        return \DateTimeImmutable::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getDTOQualifiedName(): string
    {
        return DateTimeImmutableDTO::class;
    }
}
