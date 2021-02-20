<?php

namespace Mpwarfwk\FileParser;

use Symfony\Component\Yaml\Parser,
    Mpwarfwk\Component\Bootstrap;

class YamlFileParser extends AbstractFileParser {

    public function __construct($filePath){
        $yamlParser = new Parser();
        $this->configRoutes = $yamlParser->parse(file_get_contents($filePath), true);
    }
}