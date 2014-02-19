<?php

class Application extends \Phalcon\Mvc\Application
{

	protected function _registerAutoloaders()
	{

		$loader = new \Phalcon\Loader();

		$loader->registerDirs(array(
			'../apps/controllers/',
			'../apps/models/',
            '../apps/extensions/'
		));

		$loader->register();
	}

	/**
	 * This methods registers the services to be used by the application
	 */
	protected function _registerServices()
	{

		$di = new \Phalcon\DI();

		//Registering a router
		$di->set('router', function(){
			return new \Phalcon\Mvc\Router();
		});

		//Registering a dispatcher
		$di->set('dispatcher', function(){
			return new \Phalcon\Mvc\Dispatcher();
		});

		//Registering a Http\Response
		$di->set('response', function(){
			return new \Phalcon\Http\Response();
		});

		//Registering a Http\Request
		$di->set('request', function(){
			return new \Phalcon\Http\Request();
		});

		//Registering the view component
		$di->set('view', function(){
			$view = new \Phalcon\Mvc\View();
			$view->setViewsDir('../apps/views/');
			return $view;
		});

		$di->set('db', function(){
			return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
				"host" => "localhost",
				"username" => "totwit",
				"password" => "totwit",
				"dbname" => "totwit"
			));
		});

		//Registering the Models-Metadata
		$di->set('modelsMetadata', function(){
			return new \Phalcon\Mvc\Model\Metadata\Memory();
		});

		//Registering the Models Manager
		$di->set('modelsManager', function(){
			return new \Phalcon\Mvc\Model\Manager();
		});

        $di->set('config',function() {
            require "config.php";
            return new \Phalcon\Config($config);
        });

		$this->setDI($di);
	}

	public function main()
	{

		$this->_registerServices();
		$this->_registerAutoloaders();

		echo $this->handle()->getContent();
	}

}

try {
	$application = new Application();
	$application->main();
}
catch(Phalcon\Exception $e){
	echo $e->getMessage();
}
