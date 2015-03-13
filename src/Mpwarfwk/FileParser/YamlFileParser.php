<?php

namespace Mpwarfwk\FileParser;

use Symfony\Component\Yaml\Parser,
    Mpwarfwk\Component\Bootstrap;

class YamlFileParser extends AbstractFileParser {

    const CONFIG_FILE_NAME = "routes.yaml";

    public function __construct(){
        $yamlParser = new Parser();
        $this->configRoutes = $yamlParser->parse($this->getConfig(), true);
    }

    private function getConfig(){
        return Bootstrap::getRootApplicationPath() . "/config/" . self::CONFIG_FILE_NAME;
    }
}