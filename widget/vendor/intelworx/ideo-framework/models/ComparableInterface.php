<?php

/**
 * Description of ComparableInterface
 * An interface specifying that an object can be compared to another
 * object.
 */
interface ComparableInterface
{
    /**
     * compare function, returns -1 if less
     * 0 if equal
     * and 1 if greater
     */
    public function compare(ComparableInterface $compareTo);
}