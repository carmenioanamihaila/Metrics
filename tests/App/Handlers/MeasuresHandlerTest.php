<?php

/**
 * Created by PhpStorm.
 * User: carmenmihaila
 * Date: 18/02/2018
 * Time: 06:35
 */
namespace Tests\App\Handlers;

use App\Handlers\MeasuresHandler;
use App\ServicesLoader;
use App\Utils\MetricsTypes;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

class MeasuresHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MeasuresHandler
     */
    private $handler;

    /**
     * @var Application
     */
    private $app;

    public function setUp()
    {
        $this->app = new Application();
        $this->app->register(new DoctrineServiceProvider(), array(
            "db.options" => array(
                "driver" => "pdo_sqlite",
                "memory" => true
            ),
        ));
        $servicesLoader = new ServicesLoader($this->app);
        $this->handler = new MeasuresHandler($this->app['measures.service']);

        foreach (MetricsTypes::TYPES as $type) {
            $this->createInsert($type);
        }
    }

    public function testGetSpecificMeasures()
    {
        $data = $this->handler->getSpecificMeasure(1, 2, 14);

        foreach (MetricsTypes::TYPES as $type) {
            $this->assertEquals(4, $data[$type]['no_of_records']);
            $this->assertEquals(100, $data[$type]['minimum']);
            $this->assertEquals(800, $data[$type]['maximum']);
            $this->assertEquals(350, $data[$type]['mean']);
            $this->assertEquals(250, $data[$type]['median']);
        }
    }

    function testCreateMetrics()
    {
        $metricArray = $this->createArray();
        $this->handler->createMetrics($metricArray);

        $passedArray = [
            "unit_id" => 1,
            "day" => 9,
            "hour" => 19,
        ];
        foreach (MetricsTypes::TYPES as $type) {
            $data = $this->app['db']->fetchAll("SELECT * FROM " . $type . " WHERE `unit_id`=? AND `day`=? AND `hour`=?", array_values($passedArray));
            $this->assertEquals(3, count($data));
        }
    }

    private function createArray()
    {
        return [
            0 => [
                "unit_id" => 1,
                "metrics" => [
                    "download" => [
                        0 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 4669200,
                        ],
                        1 => [
                            "timestamp" => "2017-02-21 05:00:00",
                            "value" => 567,
                        ],
                        2 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 0,
                        ],
                        3 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 300,
                        ],
                    ],
                    "upload" => [
                        0 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 100,
                        ],
                        1 => [
                            "timestamp" => "2017-02-21 05:00:00",
                            "value" => 200,
                        ],
                        2 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 300,
                        ],
                        3 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 400,
                        ],
                    ],
                    "packet_loss" => [
                        0 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 4669200,
                        ],
                        1 => [
                            "timestamp" => "2017-02-21 05:00:00",
                            "value" => 567,
                        ],
                        2 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 0,
                        ],
                        3 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 300,
                        ],
                    ],
                    "latency" => [
                        0 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 4669200,
                        ],
                        1 => [
                            "timestamp" => "2017-02-21 05:00:00",
                            "value" => 567,
                        ],
                        2 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 0,
                        ],
                        3 => [
                            "timestamp" => "2017-02-09 19:00:00",
                            "value" => 300,
                        ],
                    ],
                ],
            ],
        ];
    }

    private function createInsert(string $type)
    {
        $stmt = $this->app["db"]->prepare("CREATE TABLE " . $type .
            "(
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                month INTEGER NOT NULL,
                day INTEGER NOT NULL,
                year INTEGER NOT NULL,
                hour INTEGER NOT NULL,
                unit_id INTEGER NOT NULL,
                value INTEGER NOT NULL
            )");
        $stmt->execute();
        $stmt = $this->app["db"]->prepare("INSERT INTO ". $type . "(`month`, `day`, `year`, `hour`, `unit_id`, `value`) VALUES (2, 2, 2017, 14, 1, 300)");
        $stmt->execute();
        $stmt = $this->app["db"]->prepare("INSERT INTO ". $type . "(`month`, `day`, `year`, `hour`, `unit_id`, `value`) VALUES (2, 2, 2017, 14, 1, 200)");
        $stmt->execute();
        $stmt = $this->app["db"]->prepare("INSERT INTO ". $type . "(`month`, `day`, `year`, `hour`, `unit_id`, `value`) VALUES (2, 2, 2017, 14, 1, 100)");
        $stmt->execute();
        $stmt = $this->app["db"]->prepare("INSERT INTO ". $type . "(`month`, `day`, `year`, `hour`, `unit_id`, `value`) VALUES (2, 2, 2017, 14, 1, 800)");
        $stmt->execute();
    }
}
