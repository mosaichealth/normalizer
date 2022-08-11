<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer;

use MosaicHealth\Normalizer\Exception\NormalizerNotFoundException;

/**
 * @author Guillaume MOREL <me@gmorel.io>
 */
class NormalizerContainer
{
    /** @var NormalizerInterface[] */
    private array $normalizers = [];

    public function addNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizers[$normalizer->getEntityQualifiedName()] = $normalizer;
    }

    public function isSupportingEntity(object $object): bool
    {
        return isset($this->normalizers[get_class($object)]);
    }

    public function get(string $className): NormalizerInterface
    {
        if (false === $this->hasService($className)) {
            throw NormalizerNotFoundException::fromServiceId($className);
        }

        return $this->getService($className);
    }

    private function hasService(string $serviceId): bool
    {
        return isset($this->normalizers[$serviceId]);
    }

    private function getService(string $serviceId): ?NormalizerInterface
    {
        if (isset($this->normalizers[$serviceId])) {
            return $this->normalizers[$serviceId];
        }

        return null;
    }
}
