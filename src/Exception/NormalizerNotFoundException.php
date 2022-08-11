<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Exception;

/**
 * @author Guillaume MOREL <me@gmorel.io>
 */
class NormalizerNotFoundException extends \LogicException
{
    public static function fromServiceId(string $serviceId): NormalizerNotFoundException
    {
        return new self(
            sprintf(
                'Unable to find Normalizer as a service with id "%s". Did you forget to tag your service with <tag name="ds.common.ui.normalizer"/> ?',
                $serviceId
            )
        );
    }

    public static function fromFullQualifiedClassName(string $fullQualifiedClassName, \Exception $previousException): NormalizerNotFoundException
    {
        return new self(
            sprintf(
                'Unable to find Normalizer as class name with FQN "%s".',
                $fullQualifiedClassName
            ),
            0,
            $previousException
        );
    }
}
