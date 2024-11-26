<?php

namespace App\Models;

use CodeIgniter\Model;

class EntregaModel extends Model
{
    protected $table = 'entrega';
    protected $primaryKey = 'ID_entrega';
    protected $allowedFields = ['ID_usuario', 'codigo', 'ID_direccion'];
}