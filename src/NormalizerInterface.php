<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer;

/**
 * Allow a Normalizer to transform an Entity into a REST DTO.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
interface NormalizerInterface
{
    /**
     * Transform an Entity into a DTO
     */
    public function transform(object $dto, object $entity, Normalizer $normalizer, array $options = []);

    /**
     * Entity full qualified name
     */
    public function getEntityQualifiedName(): string;

    /**
     * DTO full qualified name
     */
    public function getDTOQualifiedName(): string;
}
