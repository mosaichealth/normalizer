<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Implementation\Normalizer;

use MosaicHealth\Normalizer\DTOTransformerInterface;
use MosaicHealth\Normalizer\Implementation\DTO\DateTimeImmutableDTO;
use MosaicHealth\Normalizer\Normalizer;
use MosaicHealth\Normalizer\RestTransformerInterface;

/**
 * Normalizer: This object is responsible to transform an Entity/ValueObject into a serializable DTO.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
class DateTimeImmutableNormalizer implements RestTransformerInterface, DTOTransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param DateTimeImmutableDTO $dto
     * @param \DateTimeImmutable   $entity
     */
    public function transform($dto, $entity, Normalizer $normalizer, array $options = [])
    {
        // Y-m-d\TH:i:sP easily parsed by Javascript
        $dto->dateTime = $entity->format(\DateTimeInterface::RFC3339);
    }

    /**
     * {@inheritdoc}
     */
    public function getDTOClass(): string
    {
        return DateTimeImmutableDTO::class;
    }
}
