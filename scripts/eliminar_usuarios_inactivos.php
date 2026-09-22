<?php

use CodeIgniter\Database\Config;

// Cargar la configuración de la base de datos de CodeIgniter
$db = Config::connect();

// Definir el tiempo después del cual se considerará expirada la verificación (ejemplo: 24 horas + un margen)
$cutoff = date('Y-m-d H:i:s');

$deleted = $db->table('users')
    ->where('is_active', 0)
    ->where('verification_expires <', $cutoff)
    ->delete();

if ($deleted) {
    log_message('info', "Cron Job: Se eliminaron {$deleted} cuentas de usuario no activadas (token expirado).");
    echo "OK: Se eliminaron {$deleted} cuentas.\n";
} else {
    log_message('info', "Cron Job: No se encontraron cuentas de usuario no activadas con token expirado para eliminar.");
    echo "OK: No se encontraron cuentas para eliminar.\n";
}

$db->close();

?>