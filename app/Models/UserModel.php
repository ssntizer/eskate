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
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }
    public function findUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function setPasswordResetToken($email, $token, $expiration)
    {
        return $this->where('email', $email)
                    ->set('reset_token', $token)
                    ->set('reset_expiration', $expiration)
                    ->update();
    }

    public function verifyToken($token)
    {
        return $this->where('reset_token', $token)
                    ->where('reset_expiration >=', date('Y-m-d H:i:s'))
                    ->first();
    }

    public function resetPassword($token, $newPassword)
    { 
        $hashedPassword = $newPassword;
        echo 'Hashed Password: ' . $hashedPassword;
        
        return $this->where('reset_token', $token)
                    ->set('password', password_hash($newPassword, PASSWORD_DEFAULT))
                    ->set('reset_token', null)
                    ->set('reset_expiration', null)
                    ->update();
                   

    }
}   


   