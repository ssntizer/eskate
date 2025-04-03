


<?php
namespace App\Models;

use CodeIgniter\Model;

class SkateTrackingModel extends Model
{
    protected $table = 'skate_tracking';
    protected $primaryKey = 'id';
    protected $allowedFields = ['codigo', 'longitud', 'latitud', 'timestamp'];
    protected $useTimestamps = false;

    public function __construct()
    {
        parent::__construct();
        $this->eliminarDatosViejos();
    }

    private function eliminarDatosViejos()
    {
        $db = \Config\Database::connect();
        $db->query("DELETE FROM skate_tracking WHERE timestamp < NOW() - INTERVAL 2 DAY");
    }
}

