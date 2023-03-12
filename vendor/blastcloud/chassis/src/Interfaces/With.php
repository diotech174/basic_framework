<?php

namespace BlastCloud\Chassis\Interfaces;

/**
 * Interface With
 * @package BlastCloud\Chassis\Interfaces
 * @codeCoverageIgnore
 */
interface With
{
    /**
     * Add values to the filter.
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function add($name, array $arguments);

    /**
     * Filter through the history items and return only
     * the items that match.
     *
     * @param array $history
     * @return array
     */
    public function __invoke(array $history): array;

    /**
     * Return a human readable representation of what this
     * with* statement added.
     *
     * @return string
     */
    public function __toString(): string;
}