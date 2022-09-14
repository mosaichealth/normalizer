<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Implementation\DTO;

use MosaicHealth\Normalizer\Json;

/**
 * @author Hugh O'REILLY <hscoreilly@gmail.com>
 */
class DateTimeImmutableDTO implements \JsonSerializable
{
    public \DateTimeImmutable $dateTime;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): mixed
    {
        return Json::objectNode([
            'dateTime' => $this->dateTime,
        ]);
    }
}
