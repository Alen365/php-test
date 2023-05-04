<?php

namespace App\Collections;

use Generator;
use Traversable;

abstract class Collection implements \IteratorAggregate
{
    protected array $items = [];

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function find($field, int $value)
    {
        $data = $this->generator();
        $results = [];
        foreach ($data as $element) {
            if ($element->$field == $value) {
                $results[] = $element;
            }
        }
        $this->items = $results;

        return $this;
    }

    /**
     * Get the first item from the collection.
     *
     * @return mixed
     * @throws \Exception
     */
    public function first()
    {
        if (empty($this->items)) {
            throw new \Exception('Collection Empty');
        }
        return $this->items[0];
    }

    public function filter(callable $callable, $return_array = false): iterable
    {
        $data = [];
        foreach ($this->items as $item) {
            if ($callable($item)) {
                $data[] = $item;
            }
        }
        if ($return_array) {
            return $data;
        }
        return new $this($data);

    }

    /**
     * Get the last item from the collection.
     *
     * @return object
     */
    public function last(): object
    {
        return end($this->items);
    }

    /**
     * Get the number of items the collection has.
     *
     * @return int
     */
    public function count(): int
    {
        $items = $this->generator();
        $count = 0;
        foreach ($items as $item) {
            $count++;
        }
        return $count;
    }

    public function isEmpty()
    {
        return empty($this->items);
    }


    /**
     * Get the collection items as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Get Generator for collection items.
     *
     * @return Generator
     */
    private function generator(): Generator
    {
        foreach ($this->items as $element) {
            yield $element;
        }
    }
}