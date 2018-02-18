<?php
/**
 * Created by PhpStorm.
 * User: carmenmihaila
 * Date: 17/02/2018
 * Time: 15:58
 */

namespace App;

use App\Controllers\MeasuresController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
    }

    private function instantiateControllers()
    {
        $this->app['measures.controller'] = function() {
            return new MeasuresController($this->app['measures.handler']);
        };
    }

    public function bindRoutesToControllers()
    {
        $this->app->get(
            '/measures/{unit_id}/{day}/{hour}',
            function ($unit_id, $day, $hour) {
                $response = $this->app['measures.controller']->getSpecificMeasure($unit_id, $day, $hour);
                if (!$response->isOk()) {
                    throw new HttpException("Something wromg happened!");
                }

                return $response;
            }
        )->assert('unit_id', '\d+')->assert('day', '\d+')->assert('hour', '\d+');
        $this->app->post(
            '/measures',
            function (Request $request) {
                return $this->app['measures.controller']->save($request);
            }
        );

        $this->app->flush();

        return $this->app;
    }
}