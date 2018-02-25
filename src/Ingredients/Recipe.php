<?php

namespace Anonymous\Recipioneer\Deployer\Ingredients;


use Anonymous\Recipioneer\Deployer\FillableTrait;
use Anonymous\Recipioneer\Deployer\Exceptions\ConfigurationErrorException;

class Recipe extends \Anonymous\Recipioneer\Recipe
{

    use FillableTrait;


    protected $name;
    protected $workingDir = './';
    protected $clearBefore = false;
    protected $clearAfter = false;


    public function __construct(array $arguments = [])
    {
        if (!isset($arguments['ingredients']) || !is_array($arguments['ingredients'])) {
            throw new ConfigurationErrorException();
        }

        parent::__construct($arguments['ingredients']);
        unset($arguments['ingredients']);

        $this->fillPropertiesFromArray($arguments);

        if ($this->name === null) {
            $classParts = explode('\\', get_class($this));
            $this->name = array_pop($classParts);
        }
    }

}