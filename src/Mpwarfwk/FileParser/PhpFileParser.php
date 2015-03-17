<?php

namespace Mpwarfwk\FileParser;


class PhpFileParser extends AbstractFileParser{

    public function __construct($filePath){
        $this->configRoutes = include_once($filePath);
    }
}