<?php
/**
 * Created by PhpStorm.
 * User: carmenmihaila
 * Date: 17/02/2018
 * Time: 16:16
 */

namespace App\Handlers;

use App\Services\MeasuresService;
use App\Utils\MetricsTypes;

class MeasuresHandler
{
    /**
     * @var MeasuresService
     */
    protected $measuresService;

    public function __construct(MeasuresService $service)
    {
        $this->measuresService = $service;
    }

    public function createMetrics(array $metricsRequest)
    {
        $insertedIds = [];
        foreach ($metricsRequest as $unit) {
            $metricArray = [];
            $metricArray['unit_id'] = (int)$unit['unit_id'];
            foreach ($unit['metrics'] as $type => $metrics) {
                foreach ($metrics as $metric) {
                    $timestamp = $metric['timestamp'];
                    $metricArray['year'] = (int)substr($timestamp, 0, 4);
                    $metricArray['month'] = (int)substr($timestamp, 5, 2);
                    $metricArray['day'] = (int)substr($timestamp, 8, 2);
                    $metricArray['hour'] = (int)substr($timestamp, 11, 2);
                    $metricArray['value'] = (int)$metric['value'];
                    $insertedIds[$type][] = $this->measuresService->save($type, $metricArray);
                }
            }
        }

        return $insertedIds;
    }

    public function getSpecificMeasure(int $unitId, int $day, int $hour)
    {
        $metrics = [];
        foreach (MetricsTypes::TYPES as $type) {
            $metrics[$type] = $this->measuresService->getMeasures($type, $unitId, $day, $hour);

            $orderedValues = $this->measuresService->getOrderedValues($type, $unitId, $day, $hour);
            $metrics[$type]['median'] = $this->getMedian($orderedValues);
        }

        return $metrics;
    }

    private function getMedian(array $values)
    {
        $count = count($values);
        $middleVal = floor(($count-1)/2); // find the middle value, or the lowest middle value
        if ($count % 2) { // odd number, middle is the median
            $median = $values[$middleVal]['value'];
        } else { // even number, calculate avg of 2 medians
            $low = $values[$middleVal]['value'];
            $high = $values[$middleVal+1]['value'];
            $median = (($low+$high)/2);
        }

        return $median;
    }
}