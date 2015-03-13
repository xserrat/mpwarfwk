<?php

namespace Mpwarfwk\FileParser;

abstract class AbstractFileParser implements FileParser{

    protected $configRoutes = null;

    abstract public function __construct($filePath);

    public function getFileData(){
        return $this->configRoutes;
    }
}