<?php

namespace Anonymous\Recipioneer\Deployer\Parsers;


use Symfony\Component\Yaml\Yaml;

class YamlParser implements ParserInterface
{

    public function parseFile($file)
    {
        return Yaml::parseFile($file);
    }

}