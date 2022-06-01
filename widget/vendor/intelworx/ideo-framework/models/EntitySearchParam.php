<?php


/**
 * Description of EntitySearchParam
 *
 * @author intelWorX
 *
 * @method EntitySearchParamRange range(mixed $low, mixed $high = null, bool $inclusive = true, string $field = null) range search parameter
 * @method EntitySearchParamEquals equals(mixed $value, boolean $netage, string $field = null) exact match
 * @method EntitySearchParamInArray inArray(array $values, $negate = false, $field = null) searches for item in  array.
 * @method EntitySearchParamIsNull isNull($field = null, bool $negate = false) searches for null value.
 * @method EntitySearchParamLike like(string $value, $negate = false, $field = null, $likeMode = EntitySearchParamLike::LIKE_MODE_IN_TEXT) matches like
 * @method EntitySearchParamMultiLike multiLike(string[] $value, $negate = false, $field = null, $likeMode = self::LIKE_MODE_IN_TEXT) field must be like any of the values in $value.
 */
abstract class EntitySearchParam extends IdeoObject
{

    protected $tableField;

    abstract function addToWhere(DbTableWhere $where, $field = null, $having = false);

    public function setField($field)
    {
        $this->tableField = $field;
        return $this;
    }

    public function getField()
    {
        return $this->tableField;
    }

    /**
     *
     * @param string $name
     * @param array $arguments
     * @return EntitySearchParam
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $paramClass = self::getClass() . ucfirst($name);
        if (class_exists($paramClass) && ($refl = new ReflectionClass($paramClass)) && $refl->isInstantiable()) {
            return $refl->newInstanceArgs($arguments);
        } else {
            throw new Exception("Invalid Entity Search param method {$name}.");
        }
    }

}
