<?php

namespace app\dao;

use config\Database;
use PDO;

abstract class BaseDAO {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }
}
