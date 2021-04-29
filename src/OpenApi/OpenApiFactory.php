<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }


    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        foreach ($openApi->getPaths()->getPaths() as $key => $path){
            //get
            if($path->getGet() && $path->getGet()->getSummary() === "hidden"){
                $openApi->getPaths()->addPath($key,  $path->withGet(null));
            }
        }

        foreach ($openApi->getPaths()->getPaths() as $key => $path){
            //post
            if($path->getPost() && $path->getPost()->getSummary() === "hidden"){
                $openApi->getPaths()->addPath($key,  $path->withPost(null));
            }
        }

        foreach ($openApi->getPaths()->getPaths() as $key => $path){
            //put
            if($path->getPut() && $path->getPut()->getSummary() === "hidden"){
                $openApi->getPaths()->addPath($key,  $path->withPut(null));
            }
        }


        foreach ($openApi->getPaths()->getPaths() as $key => $path){
        //patch
            if($path->getPatch() && $path->getPatch()->getSummary() === "hidden"){
                $openApi->getPaths()->addPath($key,  $path->withPatch(null));
            }
        }

        foreach ($openApi->getPaths()->getPaths() as $key => $path){
       //delete
            if($path->getDelete() && $path->getDelete()->getSummary() === "hidden"){
                $openApi->getPaths()->addPath($key,  $path->withDelete(null));
            }
        }


        return $openApi;
    }
}

