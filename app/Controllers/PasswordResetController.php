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
            log_message('debug', 'Solicitud de restablecimiento de contraseña recibida.');
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
            return redirect()->to('/login')->with('message', 'Tu contraseña ha sido actualizada exitosamente.');
        } else {
            return redirect()->to('/')->with('error', 'El token de restablecimiento no es válido o ha expirado.');
        }
    }

     public function debugEmail()
    {
        // ** CAMBIA ESTO ** a un correo tuyo personal para recibir la prueba.
        $testEmail = 'tu_correo_personal@dominio.com'; 
        $asunto = 'Prueba de Conexión SMTP CodeIgniter';
        $cuerpo = '¡Hola! Si ves este correo, la configuración SMTP y la Contraseña de Aplicación funcionan correctamente. Si no lo ves, el log a continuación tendrá la respuesta.';

        $emailService = \Config\Services::email();

        // Si has cambiado la configuración en Config/Email.php, no necesitas esto. 
        // Si no has hecho el cambio, puedes forzar el uso de la config 587/TLS aquí:
        /*
        $emailService->SMTPPort = 587;
        $emailService->SMTPCrypto = 'tls';
        $emailService->initialize();
        */

        $emailService->setTo($testEmail);
        $emailService->setSubject($asunto);
        $emailService->setMessage($cuerpo);

        // Intenta enviar el correo
        if ($emailService->send()) {
            echo "<h1>✅ Éxito: Correo de prueba enviado a {$testEmail}.</h1>";
            echo "<p>Revisa tu bandeja de entrada o spam. ¡El problema ha sido resuelto!</p>";
        } else {
            // Muestra los errores detallados de la conexión SMTP
            echo "<h1>❌ Error al enviar el correo.</h1>";
            echo "<p>Copia el contenido del recuadro de abajo para que podamos diagnosticar el problema de conexión.</p>";
            // Imprime el log de depuración (CRÍTICO para ver por qué falla)
            echo "<pre style='background-color:#f8d7da; padding:15px; border: 1px solid #f5c6cb; color:#721c24;'>" . $emailService->printDebugger(['headers', 'subject', 'body']) . "</pre>";
        }
    }

}

