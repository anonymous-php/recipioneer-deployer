<?php

namespace Anonymous\Recipioneer\Deployer\Ingredients;


use Anonymous\Recipioneer\ResolverInterface;

class StringEcho extends AbstractIngredient
{

    protected $value;


    public function process(ResolverInterface $resolver)
    {
        echo $this->value, PHP_EOL;

        return true;
    }

}