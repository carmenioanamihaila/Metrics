<?php
/**
 * Created by PhpStorm.
 * User: carmenmihaila
 * Date: 17/02/2018
 * Time: 16:08
 */

namespace App\Services;

use App\Utils\MetricsTypes;

class MeasuresService extends BaseService
{
    public function getMeasures(string $type, int $unitId, int $day, int $hour)
    {
        return $this->db->fetchAssoc(
            "SELECT count(id) as no_of_records, " .
                   "min(`value`) AS minimum, " .
                   "max(`value`) AS maximum, " .
                   "AVG(`value`) AS mean" .
            " FROM " . $type .
            " GROUP BY unit_id, `day`, `year`, `month`, `hour`" .
            " HAVING unit_id=? AND `day`=? AND `hour`=?",
            [$unitId, $day, $hour]
        );
    }

    public function getOrderedValues(string $type, int $unitId, int $day, int $hour)
    {
        return $this->db->fetchAll(
            "SELECT `value`" .
            " FROM " . $type .
            " WHERE unit_id=? AND `day`=? AND `hour`=?" .
            " ORDER BY `value`",
            [$unitId, $day, $hour]
        );
    }

    public function save(string $type, array $measures)
    {
        $this->db->insert($type, $measures);

        return $this->db->lastInsertId();
    }
}