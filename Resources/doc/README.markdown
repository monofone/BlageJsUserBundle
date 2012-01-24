BlageJsUserBundle
=================

Inspired by the FOSJsRoutingBundle it allows access to defined UserData in one 
JS Object

Installation
------------

Add this bundle to your deps file

    [BlageJsUser]
      git=git://github.com/monofone/BlageJsUserBundle.git
      target=bundles/Blage/JsUserBundle

Register the namespace in 'app/autoload.php'
 
    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Blage' => __DIR__.'/../vendor/bundles',
    ));

Register the bundle in `app/AppKernel.php`:

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Blage\JsUserBundle\BlageJsUserBundle(),
        );
    }

Register the routing in `app/config/routing.yml`:

    # app/config/routing.yml
    blage_user_js:
        resource: "@BlageJsUserBundle/Resources/config/routing.xml"

Usage
-----

Just add this line in your layout:

    <script type="text/javascript" src="{{ path('blage_js_user', {'objectName': 'User'}) }}"></script>

The parameter part with the objectName can be omitted as 'User' is the default value.

In app/config.yml you can define more fields to be populated

    // app/config.yml
    blage_js_user:
        mapped_fields: [ username, email, ... ]
