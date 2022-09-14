<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Implementation\DTO;

use MosaicHealth\Normalizer\Implementation\Entity\TypeLessCollection;
use MosaicHealth\Normalizer\Json;

/**
 * @author Lucas LOMBARDO <lucas.lombardo.dev@gmail.com>
 */
class CollectionDTO implements \JsonSerializable
{
    public TypeLessCollection $items;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): mixed
    {
        return Json::objectNode([
            'item' => $this->items,
        ]);
    }
}
