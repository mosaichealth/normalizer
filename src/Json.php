<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer;

/**
 * @author Guillaume MOREL <me@gmorel.io>
 */
class Json
{
    public static function objectNode(array $data): \stdClass
    {
        $result = new \stdClass();
        foreach ($data as $key => $value) {
            if ($value instanceof \JsonSerializable) {
                $value = $value->jsonSerialize();
            }

            self::guardType($key, $value);

            $result->$key = $value;
        }

        return $result;
    }

    public static function arrayNode(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value instanceof \JsonSerializable) {
                $value = $value->jsonSerialize();
            }

            self::guardType($key, $value);
            self::guardNullValue($key, $value);

            $result[$key] = $value;
        }

        return $result;
    }

    private static function guardType($key, $value)
    {
        if (\is_object($value) && !($value instanceof \stdClass)) {
            throw new \DomainException(sprintf('The key "%s" is an object of class "%s" that cannot be converted. Should implement the \JsonSerializable interface.', $key, get_class($value)));
        }

        if (\is_resource($value)) {
            throw new \DomainException(sprintf('The key "%s" of "%s" is a resource of type "%s". Resource cannot be serialized.', $key, get_class($value), get_resource_type($value)));
        }
    }

    private static function guardNullValue($key, $value)
    {
        if (null === $value) {
            throw new \DomainException(sprintf('The key "%s" is NULL. NULL value are forbidden in JSON array node.', $key));
        }
    }
}
