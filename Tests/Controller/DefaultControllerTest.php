<?php

namespace Blage\JsUserBundle\Tests\Controller;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase
{

    public function getSecurityContext()
    {
        $user = new User();
        
        $token = $this->getMock("Symfony\\Component\\Security\Core\\Authentication\Token\\TokenInterface");
        $token->expects($this->any())
                ->method('getUser')
                ->will($this->returnValue($user));
        
        $secContext = $this->getMock("Symfony\\Component\\Security\\Core\\SecurityContextInterface");        
        $secContext->expects($this->any())
                ->method('getToken')
                ->will($this->returnValue($token));
        
        return $secContext;
    }

    public function testAuthenticated()
    {
        $mappedFields = array('username','email');
        $securityContext = $this->getSecurityContext();
        $securityContext->expects($this->once())
                ->method('isGranted')
                ->with('IS_AUTHENTICATED_FULLY')
                ->will($this->returnValue(true));
        
        $controller = new \Blage\JsUserBundle\Controller\UserController($securityContext, $mappedFields);
        $response = $controller->userDataAction('User');
        $this->assertInstanceOf("Symfony\\Component\\HttpFoundation\\Response", $response);
        $this->assertEquals("function User(){\n  this.username = 'testname',\n  this.email = 'test@example.com'\n}\n window.User = new User();", $response->getContent());
    }
    
    public function testNotAuthenticated()
    {
        $securityContext = $this->getSecurityContext();
        $securityContext->expects($this->once())
                ->method('isGranted')
                ->with('IS_AUTHENTICATED_FULLY')
                ->will($this->returnValue(false));
        
        $controller = new \Blage\JsUserBundle\Controller\UserController($securityContext, array());
        $response = $controller->userDataAction('User');
        $this->assertInstanceOf("Symfony\\Component\\HttpFoundation\\Response", $response);
        $this->assertEquals("", $response->getContent());
        
    }

}

class User
{

    protected $username;
    protected $email;

    public function __construct()
    {
        $this->username = 'testname';
        $this->email = 'test@example.com';
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

}
