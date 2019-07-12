<?php

namespace Validation;
/**
 * rule factory
 */
class RuleFactory
{
    protected static $namespace = "Validation\\Rule\\";

    /**
     * make rule
     *
     * @param mixed $rule
     * @return Rule
     */
    public static function make($rule)
    {
        if ($rule instanceof \Validation\Rule\Rule) {
            return $rule;
        } elseif ($rule[1] instanceof \Closure) {
            $className = self::$namespace.'CallableRule';
            return new $className($rule[0], $rule[1]);
        } else {
            $className = ucfirst($rule[0]);
            $fullClass = self::$namespace.$className;

            if ($rule[1]) {
                return new $fullClass(...$rule[1]);
            } else {
                return new $fullClass();
            }
            
        }
    }
}
