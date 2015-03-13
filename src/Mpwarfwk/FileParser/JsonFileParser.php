<?php

namespace Mpwarfwk\FileParser;

use Mpwarfwk\Component\Bootstrap;

class JsonFileParser extends AbstractFileParser {

    const CONFIG_FILE_NAME = "routes.json";

    public function __construct(){
        $jsonRoutesConfig = file_get_contents($this->getConfig());
        $this->configRoutes = json_decode($jsonRoutesConfig, true);
    }

    private function getConfig(){
        return Bootstrap::getRootApplicationPath() . "/config/" . self::CONFIG_FILE_NAME;
    }
}