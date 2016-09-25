<?php

class ORM extends Kohana_ORM
{
    public static function factory($model, $id = null)
    {
        if (class_exists($model)) {
            $class = $model;
        } elseif (class_exists(preg_replace('/:/','\\', $model))) {
            $class = preg_replace('/:/','\\', $model);
        } else {
            throw new \InvalidArgumentException(sprintf('Entity class "%s" not exist', $model));
        }

        return (new \ReflectionClass($class))->newInstanceArgs([$id]);
        
    }
}