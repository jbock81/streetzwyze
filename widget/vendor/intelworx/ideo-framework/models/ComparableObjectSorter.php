<?php


/**
 * Description of ObjectSorter
 * This class sorts an array of objects which implement the ComparableInterface
 * @uses ComparableInterface
 *
 * @author intelWorX
 */
class ComparableObjectSorter extends IdeoObject
{


    //put your code here
    public static function sort(&$list, $maintainIndex = false, $sortDesc = FALSE)
    {
        if (!count($list)) {
            return TRUE;
        }
        $firstObject = current($list);
        if (!$firstObject instanceof ComparableInterface) {
            throw new ErrorException("The first object in the array is not an object of Comparable Interface");
        }

        $callBack = $sortDesc ? 'rcompare' : 'compare';
        return $maintainIndex ? uasort($list, array(self::getClass(), $callBack)) : usort($list, array(self::getClass(), $callBack));
    }

    public static function compare(ComparableInterface $object1, ComparableInterface $object2)
    {
        return $object1->compare($object2);
    }

    public static function rcompare(ComparableInterface $object1, ComparableInterface $object2)
    {
        return -1 * $object1->compare($object2);
    }

}

