<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer;

/**
 * Allow a Normalizer to transform an Entity into a REST DTO.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
interface RestTransformerInterface
{
    // Currently by using a FormType we can't distinguish
    // - if a value was not sent (Not all values are sent via PUT or optional) -> 'null'
    // - or if a key is empty (The name is empty) ->null
    // Replace null value (used on PUT to not send all data)
    final public const DEFAULT_VALUE = 'null';

    /**
     * Transform an Entity into a DTO.
     *
     * @param object $dto
     * @param object $entity
     */
    public function transform($dto, $entity, Normalizer $normalizer, array $options = []);
}
