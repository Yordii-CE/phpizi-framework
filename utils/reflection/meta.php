<?php

namespace Framework\Utils\Reflection;

use Framework\Utils\Namespaces\FrameworkNamespaces;



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

    public static function  validateFunctionContext(array $classTypes, $functionType) //__construct o action
    {
        try {
            $context = debug_backtrace(false)[2];
            $utilFunctionName = debug_backtrace(false)[1]['function'];

            if ($context == null)  throw new \Exception();

            $function = $context['function'] ?? null;
            $class = $context['class'] ?? null;

            if ($function == null || $class == null)  throw new \Exception();

            if ($functionType != "action") {
                if ($function != $functionType) throw new \Exception();
            } else {
                if ($function == "__construct") throw new \Exception();
            }
            foreach ($classTypes as $classType) {
                if (is_subclass_of($class, FrameworkNamespaces::$abstracts . $classType)) return true;
            }
            throw new \Exception();
        } catch (\Exception $e) {
            $classTypesString = implode(', ', $classTypes);
            return "'$utilFunctionName()'can only be run in the $functionType of [$classTypesString]";
        }
    }
}
