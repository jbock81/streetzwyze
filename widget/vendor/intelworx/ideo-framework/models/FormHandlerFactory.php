<?php

class FormHandlerFactory
{

    /**
     * @param string $type
     * @return AbstractFormHandler $hanlder
     */
    public static function getHandler($type, $data = null)
    {
        $className = self::getHandlerClassName($type);
        if (!class_exists($className)) {
            throwException(new Exception("form handler for type {$type} was not found"));
        }

        return new $className($data);
    }

    private static function getHandlerClassName($type)
    {
        $className = GeneralUtils::delimetedToCamelCased($type, '/_/', GeneralUtils::CAMEL_CASE_STYLE_UCFIRST);
        return $className . "FormHandler";
    }


}
