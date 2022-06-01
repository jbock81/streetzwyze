<?php

namespace models\forms;

/**
 * Description of ValidationRule
 *
 * @author intelWorX
 *
 * @todo Add documentaion for shortcut using static methods.
 *
 */
abstract class ValidationRule extends \IdeoObject
{

    protected $error = null;
    protected $errorCode = null;
    protected $sanitized = false;
    protected $errorCodes = [];
    protected static $aliases = array(
        'alnum' => 'alphanumeric',
        'float' => 'FloatType',
        'string' => 'Text',
    );

    abstract public function validate($inputValue);

    public function getError()
    {
        return $this->error;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getSanitized()
    {
        return $this->sanitized;
    }

    public function setErrorMessage($msg)
    {
        $this->error = $msg;
        return $this;
    }

    public function setErrorbyCode($code)
    {
        $this->errorCode = array_key_exists($code, $this->errorCodes) ? $this->errorCodes[$code] : $code;
        return $this->setErrorMessage(\Strings::get($this->errorCode));
    }

    public function setErrorCodes(array $errorCodes)
    {
        $this->errorCodes = array_merge($this->errorCodes, $errorCodes);
        return $this;
    }

    /**
     * Returns validation rule matching the specified method.
     * @param string $name
     * @param array $arguments
     * @return ValidationRule
     * @throws ValidationRuleException
     */
    public static function __callStatic($name, $arguments)
    {

        if (array_key_exists($name, static::$aliases)) {
            $name = static::$aliases[$name];
        }

        $className = __NAMESPACE__ . '\\validationrules\\' . ucfirst($name);
        if (class_exists($className) && ($reflection = new \ReflectionClass($className)) && $reflection->isInstantiable()) {
            return $reflection->newInstanceArgs($arguments);
        } else {
            throw new ValidationRuleException("No valid handler was found for the specified rule {$name} --> {$className}.");
        }
    }

    /**
     *
     * Returns new instance of the calling class the arguments.
     * {@link static::__construct()}
     * @return static
     * @throws ValidationRuleException
     */
    public static function rule()
    {
        return self::__callStatic(static::getClassBasic(), func_get_args());
    }

}
