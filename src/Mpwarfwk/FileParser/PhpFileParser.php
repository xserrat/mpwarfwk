<?php

namespace Mpwarfwk\FileParser;


class PhpFileParser extends AbstractFileParser{

    public function __construct($filePath){
        $this->configRoutes = include($filePath);
    }
}