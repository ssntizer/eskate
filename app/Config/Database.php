<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array<string, mixed>
     */
    public array $default = [
        'DSN'      => '',
        'hostname' => 'sql10.freesqldatabase.com',  // Cambiado a tu nuevo host de MySQL
        'username' => 'sql10733194',                // Usuario de tu base de datos MySQL
        'password' => '6747MCSCCX',                 // Contraseña de tu base de datos MySQL
        'database' => 'sql10733194',                // Nombre de tu base de datos MySQL
        'DBDriver' => 'MySQLi',                     // Cambiado a MySQLi
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'cacheOn'  => false,
        'cachedir' => '',
        'charSet'  => 'utf8mb4',                    // UTF8mb4 es compatible con más caracteres
        'DBCollat' => 'utf8mb4_general_ci',
        'swap_pre' => '',
        'encrypt'  => false,                        // Desactivado para MySQL
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,                         // Puerto para MySQL
    ];

    public function __construct()
    {
        parent::__construct();

        // Aseguramos que se use el grupo 'tests' en entornos de prueba
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
