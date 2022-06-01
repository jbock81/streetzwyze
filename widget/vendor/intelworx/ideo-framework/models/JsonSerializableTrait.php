<?php

/**
 * Description of JsonSerializableObject
 *  Classes using this trait should implement JsonSerializable.
 * @author JosephT
 */
/**
 * Json properties must be camel cased
 */
define('IDEO_JSON_FIELD_STYLE_CAMEL', 1);

/**
 * Json properties must be _ demilimted
 */
define('IDEO_JSON_FIELD_STYLE_DELIMITED', 2);

/**
 * Leave JSON fields as is.
 */
define('IDEO_JSON_FIELD_STYLE_ANY', 3);

trait JsonSerializableTrait
{

    /**
     *
     * Maps classes to config for JsonSerializableTrait objects
     * @var JsonSerializableObjectConfig[]
     *
     */
    protected static $classConfig = array();
    protected static $jsonIgnoreFields = array();
    public static $JSON_FIELD_STYLE = IDEO_JSON_FIELD_STYLE_ANY;

    /**
     *
     * @var callable[] callables to pass fields through,
     */
    public static $jsonFieldFilterMap = [];

    protected static function _jsonSetConfig($name, $value)
    {
        $className = get_called_class();
        if (!array_key_exists($className, static::$classConfig)) {
            static::$classConfig[$className] = new JsonSerializableObjectConfig();
        }

        static::$classConfig[$className]
            ->setConfig($name, array_merge((array)static::_jsonGetParentConfig($name), (array)$value));
    }

    /**
     *
     * @param array $name
     */
    protected static function _jsonGetParentConfig($name)
    {
        $parents = class_parents(get_called_class());
        foreach ($parents as $className) {
            if (array_key_exists($className, static::$classConfig)) {
                return static::$classConfig[$className]->getConfig($name);
            }
        }

        return [];
    }

    protected static function _jsonGetConfig($name)
    {
        $className = get_called_class();
        if (!array_key_exists($className, static::$classConfig)) {
            return static::_jsonGetParentConfig($name);
        } else {
            return static::$classConfig[$className]->getConfig($name);
        }
    }

    protected static function _jsonMergeConfig($name, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $currentValue = (array)static::_jsonGetConfig($name);
        $newValue = array_merge($currentValue, $value);
        static::_jsonSetConfig($name, $newValue);
    }

    protected function __ideoIgnoredJsonFields()
    {
        return [];
    }

    protected function _jsonIgnoresGet()
    {
        $ignores = array_merge(static::$jsonIgnoreFields, (array)$this->__ideoIgnoredJsonFields(), (array)static::_jsonGetConfig(JsonSerializableObjectConfig::PROP_IGNORE_FIELD));

        return $ignores;
    }

    public function jsonSerialize()
    {
        //->format(DateTime::ISO8601)
        $reflect = new ReflectionObject($this);

        $propertyFilter = $this->synthesizeFields ? null : ReflectionProperty::IS_PUBLIC;
        $ignores = $this->_jsonIgnoresGet();

        $data = array();
        foreach ($reflect->getProperties($propertyFilter) as $property) {
            /* @var $property ReflectionProperty */
            if (!$property->isStatic()) {
                $propertyName = $property->getName();
                if (!in_array($propertyName, $ignores)) {
                    $data[static::jsonGetFieldName($propertyName)] = $property->getValue($this);
                }
            }
        }

        foreach ($reflect->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            /* @var $method ReflectionMethod */
            $matches = array();
            if (!$method->isStatic() && preg_match('/^(is|get)_?(.+)/', $method->getName(), $matches) && ($method->getNumberOfParameters() == 0 || $method->getNumberOfRequiredParameters() == 0)) {
                $varName = lcfirst($matches[2]);
                $varName2 = GeneralUtils::camelCaseToDelimited($varName);
                if (!in_array($varName, $ignores) && !in_array($varName2, $ignores)) {
                    $data[static::jsonGetFieldName($varName)] = call_user_func(array($this, $method->getName()));
                }
            }
        }

        $this->_jsonApplyFilterMap($data);
        return $data;
    }

    protected function _jsonApplyFilterMap(&$data)
    {
        $currentFilter = (array)static::_jsonGetConfig(JsonSerializableObjectConfig::PROP_FILTER_MAP);
        if (!empty($currentFilter)) {
            array_walk($data, function (&$value, $key) use ($currentFilter) {
                if (array_key_exists($key, $currentFilter) && is_callable(($fn = $currentFilter[$key]))) {
                    $value = $fn($value);
                }
            });
        }
    }

    protected static function jsonGetFieldName($v)
    {
        if (static::$JSON_FIELD_STYLE === IDEO_JSON_FIELD_STYLE_CAMEL) {
            return preg_match('/_/', $v) ? GeneralUtils::delimetedToCamelCased($v) : $v;
        } else if (static::$JSON_FIELD_STYLE === IDEO_JSON_FIELD_STYLE_DELIMITED) {
            return GeneralUtils::camelCaseToDelimited($v);
        } else {
            return $v;
        }
    }

    public static function jsonFilterMap($map)
    {
        static::_jsonMergeConfig(JsonSerializableObjectConfig::PROP_FILTER_MAP, $map);
    }

    public static function jsonAddIgnoreField($fld)
    {
        static::_jsonMergeConfig(JsonSerializableObjectConfig::PROP_IGNORE_FIELD, $fld);
    }

}
