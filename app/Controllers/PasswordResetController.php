<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class PasswordResetController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instanciar el modelo de usuario
    }

    public function requestReset()
{
    $email = $this->request->getPost('email');
    $user = $this->userModel->findUserByEmail($email);

    if ($user) {
        $token = bin2hex(random_bytes(3)); // Generar un token único
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Establecer la expiración a 1 hora

        // Insertar el token y su expiración en la base de datos
        $this->userModel->setPasswordResetToken($email, $token, $expiration);

        // Crear el enlace de restablecimiento de contraseña
        $resetLink = site_url('passwordreset/reset/' . $token);

        // Enviar el correo con el enlace de restablecimiento
        $asunto = 'Restablecimiento de contraseña';
        $cuerpo = 'Haga clic en este enlace para restablecer su contraseña: ' . $resetLink."   y este es su token de restauracion:".$token;

        if (\Config\Services::sendEmail($email, $asunto, $cuerpo)) {
            return redirect()->to('/')->with('message', 'Se ha enviado un enlace de restablecimiento de contraseña a tu correo.');
        } else {
            return redirect()->back()->with('error', 'Error al enviar el correo de restablecimiento.');
        }
    } else {
        return redirect()->back()->with('error', 'El correo no está registrado.');
    }
}

    public function reset($token)
    {
        $user = $this->userModel->verifyToken($token);
        
        if ($user) {
            return view('reset_password', ['token' => $token]);
        } else {
            return redirect()->to('/')->with('error', 'El token de restablecimiento no es válido o ha expirado.');
        }
    }
    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('password');
    
       
    
        $user = $this->userModel->verifyToken($token);
    
        if ($user) {
            $this->userModel->resetPassword($token, $newPassword);
            return redirect()->to('/')->with('message', 'Tu contraseña ha sido actualizada exitosamente.');
        } else {
            return redirect()->to('/')->with('error', 'El token de restablecimiento no es válido o ha expirado.');
        }
    }
    public function verifyToken()
{
    $token = $this->request->getPost('token');
    $newPassword = $this->request->getPost('new_password');

    // Verifica si el token es válido
    $user = $this->userModel->verifyToken($token);

    if ($user) {
        // Restablecer la contraseña
        $this->userModel->resetPassword($token, $newPassword);
        return redirect()->to('/')->with('message', 'Tu contraseña ha sido restablecida.');
    } else {
        return redirect()->back()->with('error', 'El token es inválido o ha expirado.');
    }
}

}