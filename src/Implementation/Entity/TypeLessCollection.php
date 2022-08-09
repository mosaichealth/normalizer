<?php

declare(strict_types=1);

namespace MosaicHealth\Normalizer\Implementation\Entity;

/**
 * @author Lucas LOMBARDO <lucas.lombardo.dev@gmail.com>
 */
class TypeLessCollection implements \ArrayAccess
{
    private array $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (\is_null($offset)) {
            $this->items[] = $value;

            return;
        }

        $this->items[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }
}