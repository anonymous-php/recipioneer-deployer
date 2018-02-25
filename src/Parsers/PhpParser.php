<?php

namespace Anonymous\Recipioneer\Deployer\Parsers;


class PhpParser implements ParserInterface
{

    public function parseFile($file)
    {
        return require $file;
    }

}