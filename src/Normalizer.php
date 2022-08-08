<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer;

/**
 * Normalize a DTO into a JSON payload.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
class Normalizer
{
    private readonly NormalizerContainer $normalizerContainer;

    public function __construct(NormalizerContainer $normalizerContainer)
    {
        $this->normalizerContainer = $normalizerContainer;
    }

    /**
     * @param array|object|null $data
     */
    public function normalize($data, array $options = []): mixed
    {
        $options = \array_merge($options);

        if ($data instanceof \Traversable) {
            return $this->normalizeArray($data, $options);
        }

        if (\is_object($data)) {
            $className = get_class($data);
            if ($this->normalizerContainer->isSupportingEntity($data)) {
                $normalizer = $this->normalizerContainer->get($className);
            } else {
                return $data;
            }

            $dtoClass = $normalizer->getDTOQualifiedName();
            $dto = new $dtoClass();
            $normalizer->transform($dto, $data, $this, $options);

            return (array) $dto;
        }

        if (!is_array($data)) {
            return $data;
        }

        return $this->normalizeArray($data, $options);
    }

    private function normalizeArray($data, array $options = []): array
    {
        foreach ($data as $k => $value) {
            $data[$k] = $this->normalize($value, $options);
        }

        return $data;
    }
}
