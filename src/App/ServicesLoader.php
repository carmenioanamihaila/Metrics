<?php
/**
 * Created by PhpStorm.
 * User: carmenmihaila
 * Date: 17/02/2018
 * Time: 15:56
 */

namespace App;

use App\Handlers\MeasuresHandler;
use App\Services\MeasuresService;
use Silex\Application;

class ServicesLoader
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->bindServicesIntoContainer();
        $this->instantiateHandlers();
    }

    private function instantiateHandlers()
    {
        $this->app['measures.handler'] = function() {
            return new MeasuresHandler($this->app['measures.service']);
        };
    }

    public function bindServicesIntoContainer()
    {
        $this->app['measures.service'] = function() {
            return new MeasuresService($this->app["db"]);
        };
    }
}
