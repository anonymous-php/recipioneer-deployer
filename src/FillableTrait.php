<?php

namespace Anonymous\Recipioneer\Deployer;


trait FillableTrait
{

    public function fillPropertiesFromArray($properties = [])
    {
        foreach ($properties as $property => $value) {
            $property = lcfirst(implode('', array_map('ucfirst', explode('-', preg_replace('/^\$/', '', $property)))));

            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }

}