<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Implementation\DTO;

/**
 * @author Lucas LOMBARDO <lucas.lombardo.dev@gmail.com>
 */
class CollectionDTO implements \JsonSerializable
{
    public array $items;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items,
        ];
    }
}
