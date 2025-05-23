<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'email_reset_token', 'email_reset_expire', 'reset_token', 'reset_expiration', 'is_active', 'token', 'verification_token', 'verification_expires', 'created_at']; // Asegúrate de que estos estén aquí
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
                    ->set([
                        'reset_token' => $token,
                        'reset_expiration' => $expiration
                    ])
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
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->where('reset_token', $token)
                    ->set([
                        'password' => $hashedPassword,
                        'reset_token' => null,
                        'reset_expiration' => null
                    ])
                    ->update();
    }

    public function verifyPassword($email, $password)
    {
        $user = $this->findUserByEmail($email);
        return ($user && password_verify($password, $user['password']));
    }

    public function updateUser($userId, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->where('id', $userId)->set($data)->update();
    }

    public function setEmailResetToken($userId, $token, $expire)
    {
        return $this->where('id', $userId)
                    ->set([
                        'email_reset_token' => $token,
                        'email_reset_expire' => $expire
                    ])
                    ->update();
    }

    public function verifyEmailToken($token)
    {
        return $this->where('email_reset_token', $token)
                    ->where('email_reset_expire >', date('Y-m-d H:i:s'))
                    ->first();
    }

    public function verifyPasswordToken($token)
    {
        return $this->where('reset_token', $token)
                    ->where('reset_expiration >', date('Y-m-d H:i:s'))
                    ->first();
    }

    public function emailExists(string $email): bool
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    public function usernameExists(string $username): bool
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }
}