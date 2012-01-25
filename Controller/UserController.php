<?php

namespace Blage\JsUserBundle\Controller;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;



class UserController
{
    
    protected $securityContext;
    
    protected $mappedFields;
    
    public function __construct(SecurityContextInterface $securityContext, array $mappedFields)
    {
        $this->securityContext = $securityContext;
        $this->mappedFields = $mappedFields;
    }

    
    public function userDataAction($objectName)
    {
        if($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $user = $this->securityContext->getToken()->getUser();
            $userdataString = "{\n";
            $propCount = 0;
            foreach($this->mappedFields as $field){
                $propCount++;
                $methodName = 'get'. ucfirst(Container::camelize($field));
                if(method_exists($user, $methodName)){
                    $userdataString .= '  this.'.$field." = '".$user->{$methodName}()."'";
                    if($propCount < count($this->mappedFields)){
                        $userdataString .=",";
                    }
                    $userdataString .="\n";
                }
                
            }
            $userdataString = 'function '.$objectName.'()'.$userdataString;
            
            $userdataString .= "}\n window.".$objectName." = new ".$objectName."();";
            
            return new Response($userdataString);
        }
        return new Response();
    }
    
}
