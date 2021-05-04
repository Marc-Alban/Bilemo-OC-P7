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
        //Route Deleted ----
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
	
	    //Route shemas response etc  ----
	
	    $schemas = $openApi->getComponents()->getSchemas();
	
	    $schemas['Token'] = new \ArrayObject([
		    'type' => 'object',
		    'properties' => [
			    'token' => [
				    'type' => 'string',
				    'readOnly' => true,
			    ],
		    ],
	    ]);
	
	    $schemas['Credentials'] = new \ArrayObject([
		    'type' => 'object',
		    'properties' => [
			    'email' => [
				    'type' => 'string',
				    'example' => 'tata@orange.com',
			    ],
			    'password' => [
				    'type' => 'string',
				    'example' => 'password',
			    ],
		    ],
	    ]);
	
	    $responses = [
		    '200' => [
			    'description' => 'Get JWT token',
			    'content' => [
				    'application/json' => [
					    'schema' => [
						    '$ref' => '#/components/schemas/Token',
					    ],
				    ],
			    ]
		    ],
		    '401' => [
			    'description' => 'Unauthorized. Bad credentials.',
		    ],
	    ];
	
	    $content = new \ArrayObject([
		    'application/json' => [
			    'schema' => [
				    '$ref' => '#/components/schemas/Credentials',
			    ],
		    ],
	    ]);
	
	    $requestBody = new Model\RequestBody('Generate new JWT Token', $content);
	
	    $post = new Model\Operation('postCredentialsItem', ['AUTHORIZATION TOKEN'], $responses, 'Get JWT token to login.', 'Enter your credentials to generate a JWT Token', new Model\ExternalDocumentation, [], $requestBody);
	
	    $pathItem = new Model\PathItem('JWT Token', null, null, null, null, $post);
	
	    $openApi->getPaths()->addPath('/api/auth/login', $pathItem);
	    
        return $openApi;
    }
}

