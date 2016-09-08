<?php

namespace K_Laravel_Creator;

use Illuminate\Support\ServiceProvider;
use K_Laravel_Creator\Console\Commands\K_Make_Command;
use K_Laravel_Creator\Console\Commands\K_Make_Controller;
use K_Laravel_Creator\Console\Commands\K_Make_Factory;
use K_Laravel_Creator\Console\Commands\K_Make_Migration;
use K_Laravel_Creator\Console\Commands\K_Make_Model;
use K_Laravel_Creator\Console\Commands\K_Make_Seeder;
use Illuminate\Routing\Router;

class Creator_Service_Provider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $commands = [
        'K_Make_Command' => 'command.command.k_creator',
        'K_Make_Controller' => 'command.controller.k_creator',
        'K_Make_Factory' => 'command.factory.k_creator',
        'K_Make_Migration' => 'command.migration.k_creator',
        'K_Make_Model' => 'command.model.k_creator',
        'K_Make_Seeder' => 'command.seeder.k_creator',

    ];

    protected $namespace = 'App\Http\Controllers';

    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }


    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        //
    }

    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            $dir = (dirname(__FILE__));
            errorlog($dir);
            require $dir.'Http/routes.php';
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->registerCommands();
    }

    /**
     * Register the cache related console commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key){
                $class_name = "K_Laravel_Creator\\Console\\Commands\\" . $key;
                return new $class_name($app['files']);
            });

            $this->commands($value);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_values($this->commands);
    }
}
