<?php

namespace Anonymous\Recipioneer\Deployer\Ingredients;


use Anonymous\Recipioneer\Deployer\FillableTrait;
use Anonymous\Recipioneer\Ingredient;

abstract class AbstractIngredient extends Ingredient
{

    use FillableTrait;


    protected $name;


    public function __construct(array $arguments = [])
    {
        $this->fillPropertiesFromArray($arguments);

        if ($this->name === null) {
            $classParts = explode('\\', get_class($this));
            $this->name = array_pop($classParts);
        }
    }

}