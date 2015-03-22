<?php

namespace Mpwarfwk\Component\Container;

use Mpwarfwk\Component\Bootstrap;
use Mpwarfwk\FileParser\YamlFileParser;
use Mpwarfwk\Exception\ServiceNotFoundException;

class Container implements ContainerInterface{

    const SERVICES_FILE_NAME = 'services.yaml';

    private $configServices;
    private $service;

    public function __construct(){
        $fileParser = new YamlFileParser(Bootstrap::getRootApplicationPath() . '/config/' . self::SERVICES_FILE_NAME);
        $this->configServices = $fileParser->getFileData()['services'];
    }

    public function get($service){
        if(!array_key_exists($service, $this->configServices)){
            throw new ServiceNotFoundException('Service Not Found in services.yaml');
        }
        $serviceConfig = $this->configServices[$service];
        $serviceNamespace = $serviceConfig['class'];

        //If arguments exists, return object with this arguments passed as parameters
        if(array_key_exists('arguments', $serviceConfig)){
            $reflection = new \ReflectionClass($serviceNamespace);
            $serviceArguments = array();
            foreach($serviceConfig['arguments'] as $argument){
                if($argument[0] === '@'){ //if argument is another service
                    $anotherService = $this->get(preg_replace('/@(.+)/', '\\1', $argument));
                    $serviceArguments[] = $anotherService;
                }
                elseif(class_exists($argument)){
                    $serviceArguments[] = new $argument;
                } else{
                    $serviceArguments[] = $argument;
                }
            }
            $this->service = $reflection->newInstanceArgs($serviceArguments);
        } else{
            $this->service = new $serviceNamespace();
        }
        return $this->service->run();
    }
}