<?php

namespace Blage\JsUserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;



class UserController
{
    
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    protected function get($id)
    {
        return $this->container->get($id);
    }
    
    public function userDataAction($objectName)
    {
        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $user = $securityContext->getToken()->getUser();
            $mappedFields = $this->container->getParameter('blage.js.mapped_fields');
            $userdata = array();
            foreach($mappedFields as $field){
                $methodName = 'get'. ucfirst(Container::camelize($field));
                if(method_exists($user, $methodName)){
                    $userdata[$field] = $user->{$methodName}();
                }
                
            }
            return $this->render('BlageJsUserBundle:User:userJs.html.twig', array(
                'user_data' => $userdata,
                'object_name' => $objectName,
            ));
        }
        return new Response();
    }
    
    protected function render($template, $options)
    {
        return new Response($this->get('twig')->render($template, $options));
    }
}
