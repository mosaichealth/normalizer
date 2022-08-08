<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer;

/**
 * A Normalizer able to transformed a DTO.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
interface DTOTransformerInterface
{
    /**
     * DTO full qualified name.
     */
    public function getDTOClass(): string;
}
