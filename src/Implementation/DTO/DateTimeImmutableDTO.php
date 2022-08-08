<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Implementation\DTO;

/**
 * @author Hugh O'REILLY <hscoreilly@gmail.com>
 */
class DateTimeImmutableDTO implements \JsonSerializable
{
    public string $dateTime;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): mixed
    {
        return $this->dateTime;
    }
}
