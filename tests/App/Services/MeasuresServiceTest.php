<?php

/**
 * Created by PhpStorm.
 * User: carmenmihaila
 * Date: 17/02/2018
 * Time: 23:30
 */

namespace Tests\App\Services;

use App\Services\MeasuresService;
use App\Utils\MetricsTypes;
use Doctrine\DBAL\Connection;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

class MeasuresServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MeasuresService
     */
    private $measuresService;

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
        $this->measuresService = new MeasuresService($this->app["db"]);

        foreach (MetricsTypes::TYPES as $type) {
            $this->createInsert($type);
        }
    }

    function testSave()
    {
        $metricArray['unit_id'] = 1;
        $metricArray['year'] = 2017;
        $metricArray['month'] = 2;
        $metricArray['day'] = 2;
        $metricArray['hour'] = 14;
        $metricArray['value'] = 500;
        foreach (MetricsTypes::TYPES as $type) {
            $this->measuresService->save($type, $metricArray);

            $data = $this->app['db']->fetchAll("SELECT *  FROM $type WHERE `unit_id`=? AND `year`=? AND `month`=? AND `day`=? AND `hour`=? AND `value`=?", array_values($metricArray));
            $this->assertEquals(1, count($data));
        }
    }

    public function testGetMeasures()
    {
        $unitId = 1;
        $day = 2;
        $hour = 14;

        foreach (MetricsTypes::TYPES as $type) {
            $data = $this->measuresService->getMeasures($type, $unitId, $day, $hour);

            $this->assertEquals(4, $data['no_of_records']);
            $this->assertEquals(100, $data['minimum']);
            $this->assertEquals(800, $data['maximum']);
            $this->assertEquals(350, $data['mean']);
        }
    }

    public function testGetOrderedValues()
    {
        $unitId = 1;
        $day = 2;
        $hour = 14;

        foreach (MetricsTypes::TYPES as $type) {
            $data = $this->measuresService->getOrderedValues($type, $unitId, $day, $hour);

            $this->assertEquals(100, $data[0]['value']);
            $this->assertEquals(200, $data[1]['value']);
            $this->assertEquals(300, $data[2]['value']);
            $this->assertEquals(800, $data[3]['value']);
        }
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