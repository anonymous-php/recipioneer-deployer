<?php

namespace Anonymous\Recipioneer\Deployer;


use Anonymous\Recipioneer\Deployer\Exceptions\ConfigurationErrorException;
use Anonymous\Recipioneer\Deployer\Exceptions\RecipeErrorException;
use Anonymous\Recipioneer\Deployer\Ingredients\Recipe;
use Anonymous\Recipioneer\Deployer\Ingredients\StringEcho;
use Anonymous\Recipioneer\Deployer\Parsers\ParserInterface;
use Anonymous\Recipioneer\Deployer\Parsers\YamlParser;
use Anonymous\Recipioneer\Deployer\Parsers\PhpParser;
use Anonymous\Recipioneer\Deployer\Utils\ArrayHelper;
use Anonymous\Recipioneer\IngredientInterface;
use Anonymous\Recipioneer\ResolverInterface;

class Deployer implements DeployerInterface, ResolverInterface
{

    protected $config = [
        'ingredients' => [
            'recipe' => Recipe::class,
            'string-echo' => StringEcho::class,
        ],
        'parsers' => [
            'php' => PhpParser::class,
            'yml' => YamlParser::class,
            'yaml' => YamlParser::class,
        ],
    ];


    public function __construct(array $config = [], $replaceConfig = false)
    {
        $this->config = $replaceConfig
            ? $config
            : ArrayHelper::merge($this->config, $config);
    }

    /**
     * @param $ingredient
     * @param array $arguments
     * @return IngredientInterface
     * @throws ConfigurationErrorException
     */
    public function resolveIngredient($ingredient, $arguments = [])
    {
        if (empty($this->config['ingredients'][$ingredient]) || !class_exists($this->config['ingredients'][$ingredient])) {
            throw new ConfigurationErrorException();
        }

        $ingredientClass = $this->config['ingredients'][$ingredient];

        return new $ingredientClass($arguments);
    }

    /**
     * @param string $recipeFile
     * @return ParserInterface
     * @throws RecipeErrorException
     */
    public function getParser($recipeFile)
    {
        $extension = strtolower(pathinfo($recipeFile, PATHINFO_EXTENSION));

        if (empty($this->config['parsers'][$extension]) || !class_exists($this->config['parsers'][$extension])) {
            throw new RecipeErrorException();
        }

        $parserClass = $this->config['parsers'][$extension];

        return new $parserClass();
    }

    public function cook($recipeFile, $ingredientName = null)
    {
        if (!is_readable($recipeFile)) {
            throw new RecipeErrorException();
        }

        $recipeData = $this->getParser($recipeFile)->parseFile($recipeFile);

        $ingredient = key($recipeData);
        $arguments = current($recipeData);

        return $this->resolveIngredient($ingredient, $arguments)->process($this);
    }

}