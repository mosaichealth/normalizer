<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Implementation\Normalizer;

use MosaicHealth\Normalizer\Implementation\DTO\CollectionDTO;
use MosaicHealth\Normalizer\Implementation\Entity\TypeLessCollection;
use MosaicHealth\Normalizer\Normalizer;
use MosaicHealth\Normalizer\NormalizerInterface;

/**
 * Normalizer: This object is responsible to transform an Entity/ValueObject into a serializable DTO.
 *
 * @author Lucas LOMBARDO <lucas.lombardo.dev@gmail.com>
 */
class CollectionNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param CollectionDTO $dto
     * @param TypeLessCollection    $entity
     */
    public function transform($dto, $entity, Normalizer $normalizer, array $options = [])
    {
        $dto->items = $this->createCollectionDTO($entity, $normalizer, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityQualifiedName(): string
    {
        return TypeLessCollection::class;
    }

    public function getDTOQualifiedName(): string
    {
        return CollectionDTO::class;
    }

    /**
     * @return \JsonSerializable[]
     */
    private function createCollectionDTO(TypeLessCollection $entity, Normalizer $normalizer, array $options): array
    {
        $collectionDTO = [];
        foreach ($entity->getItems() as $item) {
            $collectionDTO[] = $normalizer->normalize($item, $options);
        }

        return $collectionDTO;
    }
}
