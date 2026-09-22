<?php

namespace App\Models;

use CodeIgniter\Model;

class ProvinciaModel extends Model
{
    protected $table = 'provincias';
    protected $primaryKey = 'id';
    protected $allowedFields = ['provincia'];
}