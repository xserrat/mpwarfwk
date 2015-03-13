<?php

namespace Mpwarfwk\FileParser;

use Mpwarfwk\Component\Bootstrap;

class JsonFileParser extends AbstractFileParser {

    public function __construct($filePath){
        $this->configRoutes = json_decode(file_get_contents($filePath), true);
    }
}