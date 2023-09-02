<?php
class Meta
{
    public static function getCurrentController()
    {
        $context = debug_backtrace(false)[2];
        return $context['class'];
    }

    public static function getCurrentAction()
    {
        $context = debug_backtrace(false)[2];
        return $context['function'];
    }

    public static function getParams()
    {
        $context = debug_backtrace(false)[2];
        return $context['args'];
    }

    public static function  validateFunctionContext($classType, $functionType) //__construct o action
    {
        try {
            $context = debug_backtrace(false)[2];
            $utilFunctionName = debug_backtrace(false)[1]['function'];

            if ($context == null)  throw new Exception();

            $function = $context['function'] ?? null;
            $class = $context['class'] ?? null;

            if ($function == null || $class == null)  throw new Exception();

            if ($functionType != "action") {
                if ($function != $functionType) throw new Exception();
            } else {
                if ($function == "__construct") throw new Exception();
            }
            $pattern = '/^[A-Z][a-zA-Z0-9]*' . $classType . '$/';

            if (preg_match($pattern, $class) !== 1) throw new Exception();

            return true;
        } catch (Exception $e) {
            return "'$utilFunctionName()'can only be run in the $functionType of a $classType";
        }
    }
}
