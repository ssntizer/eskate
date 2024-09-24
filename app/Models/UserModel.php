<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'reset_token', 'reset_expiration'];
    protected $beforeInsert = ['hashPassword'];

    protected function hashPassword(array $data)
    {
    if (isset($data['data']['password'])) {
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
    }
    return $data;
    }

    // Encuentra al usuario por email
    public function findUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    // Establece el token de restablecimiento de contraseña
    public function setPasswordResetToken($email, $token, $expiration)
    {
        return $this->where('email', $email)
                    ->set('reset_token', $token)
                    ->set('reset_expiration', $expiration)
                    ->update();
    }

    // Verifica si el token es válido
    public function verifyToken($token)
    {
        return $this->where('reset_token', $token)
                    ->where('reset_expiration >=', date('Y-m-d H:i:s'))
                    ->first();
    }

    // Restablece la contraseña con una nueva
    public function resetPassword($token, $newPassword)
    { 
        // Hashea la nueva contraseña
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        return $this->where('reset_token', $token)
                    ->set('password', $hashedPassword) // Almacena la contraseña hasheada
                    ->set('reset_token', null)
                    ->set('reset_expiration', null)
                    ->update();
    }

    // Verifica la contraseña durante el login
    public function verifyPassword($email, $password)
    {
        $user = $this->findUserByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return true; // Contraseña correcta
        }

        return false; // Contraseña incorrecta
    }
}