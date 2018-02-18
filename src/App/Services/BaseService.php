<?php
/**
 * Created by PhpStorm.
 * User: carmenmihaila
 * Date: 17/02/2018
 * Time: 16:06
 */

namespace App\Services;

use Doctrine\DBAL\Connection;

class BaseService
{
    /**
     * @var Connection
     */
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
}