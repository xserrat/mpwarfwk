<?php

namespace Mpwarfwk\Component\Container;

abstract class ContainerAbstract {
    /**
     * @var $container ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container){
        $this->container = $container;
    }
}