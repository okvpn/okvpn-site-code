<?php

class ORM extends Kohana_ORM
{
    public static function factory($model, $id = null)
    {
        if (class_exists($model)) {
            $class = $model;
        } else {
            $class = static::resolveClassName($model);
        }
        
        return (new \ReflectionClass($class))->newInstanceArgs([$id]);
    }

    /**
     * Convent name as OkvpnFramework:SubNamespace:Class to full class name
     * 
     * @param string $name
     * @return mixed|string
     * @throws Exception
     */
    protected static function resolveClassName($name)
    {
        $namespaces = preg_split('/:/', $name);
        
        try {
            $searchBundle = \Kernel\Kernel::getBundleByAlias(reset($namespaces));
        } catch (\Exception $e) {
            if (class_exists(preg_replace('/:/', '\\',$name))) {
                return preg_replace('/:/', '\\',$name);
            }
            throw $e;
        }
        
        $class = preg_replace('/^\w+:/', '\\Entity\\', $name);
        return $searchBundle->getName() . preg_replace('/:/', '\\', $class);
    }
}