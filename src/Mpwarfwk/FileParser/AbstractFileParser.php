<?php

namespace Mpwarfwk\FileParser;

abstract class AbstractFileParser implements FileParserInterface{

    protected $configRoutes = null;

    abstract public function __construct($filePath);

    public function getFileData(){
        return $this->configRoutes;
    }
}