<?php

namespace K_Laravel_Creator;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class Creator_Route_Service_Provider extends ServiceProvider
{

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
            error_log($dir);
            require $dir.'/Http/routes.php';
        });
    }


}
