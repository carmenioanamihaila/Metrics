<?php
/**
 * Created by PhpStorm.
 * User: carmenmihaila
 * Date: 17/02/2018
 * Time: 16:02
 */

namespace App\Controllers;

use App\Handlers\MeasuresHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MeasuresController
{
    /**
     * @var MeasuresHandler
     */
    protected $handler;

    public function __construct(MeasuresHandler $handler)
    {
        $this->handler = $handler;
    }

    public function getSpecificMeasure($unitId, $day, $hour)
    {
        return new JsonResponse($this->handler->getSpecificMeasure($unitId, $day, $hour));
    }

    public function save(Request $request)
    {
        $metrics = $request->getContent();

        return new JsonResponse($this->handler->createMetrics($metrics));
    }
}