<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer;

use Doctrine\Common\Util\ClassUtils;

/**
 * Normalize a DTO into a JSON payload.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
class Normalizer
{
    private readonly NormalizerContainer $normalizerContainer;

    private array $defaultOptions = [];

    public function __construct(NormalizerContainer $normalizerContainer)
    {
        $this->normalizerContainer = $normalizerContainer;
    }

    /**
     * @param mixed $value
     */
    public function addDefaultOption(string $key, $value)
    {
        $this->defaultOptions[$key] = $value;
    }

    public function setDefaultsOptions(array $defaultOptions = [])
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * @param array|object|null $data
     */
    public function normalize($data, array $options = [], Normalizer $defaultNormalizer = null): mixed
    {
        $options = \array_merge($this->defaultOptions, $options);

        if (\is_object($data)) {
            if (null !== $defaultNormalizer) {
                $normalizer = $defaultNormalizer;
            } elseif ($this->normalizerContainer->isSupportingEntity($class = ClassUtils::getClass($data))) {
                $normalizer = $this->normalizerContainer
                    ->get($class, isset($options['payload']) ? $options['payload'] : 'default');
            } elseif ($data instanceof \Traversable) {
                return $this->normalizeArray($data, $options, $defaultNormalizer);
            } else {
                $normalizer = $data;
            }

            $dto = null;
            if ($normalizer instanceof DTOTransformerInterface) {
                $dtoClass = $normalizer->getDTOClass();
                $dto = new $dtoClass();
            }

            if (null === $dto) {
                $dto = $normalizer;
            }

            if ($normalizer instanceof RestTransformerInterface) {
                $normalizer->transform($dto, $data, $this, $options);
            }

            return $dto;
        }

        if (!is_array($data)) {
            return $data;
        }

        return $this->normalizeArray($data, $options, $defaultNormalizer);
    }

    private function normalizeArray($data, array $options = [], Normalizer $defaultNormalizer = null): array
    {
        foreach ($data as $k => $value) {
            $data[$k] = $this->normalize($value, $options, $defaultNormalizer);
        }

        return $data;
    }
}
