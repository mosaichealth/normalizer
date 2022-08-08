<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer;

use App\Common\UI\Normalizer\Exception\NormalizerNotFoundException;

/**
 * @author Guillaume MOREL <me@gmorel.io>
 */
class NormalizerContainer
{
    private readonly array $mapping;

    /** @var RestTransformerInterface[] */
    private array $normalizers = [];

    public function __construct(array $mapping = [])
    {
        $this->mapping = $mapping;
    }

    public function addNormalizer(RestTransformerInterface $normalizer, string $serviceId)
    {
        $this->normalizers[$serviceId] = $normalizer;
    }

    public function isSupportingEntity(string $class): bool
    {
        $class = $this->normalizeClass($class);

        return \array_key_exists($class, $this->mapping) || null !== $this->getDataInstanceOf($class);
    }

    public function get(string $fullClassNameToNormalize, string $payload = 'default'): ?RestTransformerInterface
    {
        $fullClassNameToNormalize = $this->normalizeClass($fullClassNameToNormalize);

        if (!$this->isSupportingEntity($fullClassNameToNormalize)) {
            return null;
        }

        $data = isset($this->mapping[$fullClassNameToNormalize]) ? $this->mapping[$fullClassNameToNormalize] : $this->getDataInstanceOf($fullClassNameToNormalize);

        if (!\array_key_exists($payload, $data)) {
            if (isset($data['*'])) {
                $payload = '*';
            } else {
                throw new \LogicException(sprintf('Payload "%s" for class "%s" asked does not exist, choose one of : %s', $payload, $fullClassNameToNormalize, implode(', ', array_keys($data))));
            }
        }

        $normalizerId = $data[$payload];

        if ($this->isService($normalizerId)) {
            return $this->getNormalizerAsAService($normalizerId);
        }

        return $this->getNormalizerAsAClassName($normalizerId);
    }

    /**
     * We iterate on mapping to fetch a class which inherits from argument.
     */
    private function getDataInstanceOf(string $className): ?array
    {
        foreach ($this->mapping as $class => $data) {
            if ($className instanceof $class) {
                return $data;
            }

            $reflection = new \ReflectionClass($class);

            if ($reflection->isInterface()) {
                $reflection = new \ReflectionClass($className);
                if ($reflection->implementsInterface($class)) {
                    return $data;
                }
            }
        }

        return null;
    }

    private function normalizeClass(string $class): string
    {
        return 0 === \strpos($class, '\\') ? \substr($class, 1) : $class;
    }

    /**
     * @throws NormalizerNotFoundException
     */
    private function getNormalizerAsAService(string $idWithArobase): RestTransformerInterface
    {
        $serviceId = substr($idWithArobase, 1);
        if (false === $this->hasService($serviceId)) {
            throw NormalizerNotFoundException::fromServiceId($serviceId);
        }

        return $this->getService($serviceId);
    }

    /**
     * @throws NormalizerNotFoundException
     */
    private function getNormalizerAsAClassName(string $fullQualifiedClassName): RestTransformerInterface
    {
        try {
            $normalizer = new $fullQualifiedClassName();

            if (!$normalizer instanceof RestTransformerInterface) {
                throw new \LogicException(sprintf('Model mapping "%s" must return a RestTransformerInterface', $fullQualifiedClassName));
            }

            return $normalizer;
        } catch (\Exception $e) {
            throw NormalizerNotFoundException::fromFullQualifiedClassName($fullQualifiedClassName, $e);
        }
    }

    private function isService(string $key): bool
    {
        return 0 === \strpos($key, '@');
    }

    private function hasService(string $serviceId): bool
    {
        return isset($this->normalizers[$serviceId]);
    }

    private function getService(string $serviceId): RestTransformerInterface
    {
        return $this->normalizers[$serviceId];
    }
}
