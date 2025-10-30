<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SkateModel;
use App\Models\DireccionModel;
use App\Models\ProvinciaModel;
use App\Models\LocalidadModel;

class AuthController extends BaseController
{
    protected $skateModel;

    public function __construct()
    {
        $this->skateModel = new SkateModel();
    }

    public function register()
    {
        return view('register');
    }

    public function trayectoria()
    {
        return view('trayectoria.php');
    }

   public function registerUser()
{
    $session = session();
    $userModel = new UserModel();

    // Obtener datos del formulario
    $data = [
        'username' => $this->request->getPost('username'),
        'email' => $this->request->getPost('email'),
        'password' => $this->request->getPost('password'),
        'is_active' => 0, // Cuenta inactiva hasta confirmación
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Verificar si el email ya existe
    if ($userModel->where('email', $data['email'])->first()) {
        return redirect()->back()->with('error', 'El correo electrónico ya está registrado.')->withInput();
    }

    // Generar token de verificación
    $verificationToken = bin2hex(random_bytes(16));
    $verificationExpire = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expira en 1 hora

    // Guardar el nuevo usuario con token
    $data['verification_token'] = $verificationToken;
    $data['verification_expires'] = $verificationExpire;

    try {
        $userId = $userModel->insert($data);

        if ($userId) {
            // Enviar email de verificación
            $verifyLink = site_url('auth/verify-email/' . $verificationToken);

            $emailService = \Config\Services::email();
            $emailService->clear();

            $emailService->setTo($data['email']);
            $emailService->setSubject('Verifica tu cuenta');
            $emailService->setMessage("
                <h2>Bienvenido a nuestro sitio, {$data['username']}!</h2>
                <p>Gracias por registrarte. Por favor verifica tu correo electrónico haciendo clic en el siguiente enlace:</p>
                <p><a href='{$verifyLink}'>{$verifyLink}</a></p>
                <p>Este enlace expirará en 1 hora.</p>
                <p>Si no te registraste en nuestro sitio, por favor ignora este mensaje.</p>
            ");
            $emailService->setMailType('html');

            if ($emailService->send()) {
                // ⭐ CAMBIO REALIZADO AQUÍ ⭐
                $session->setFlashdata('success', '¡Registro exitoso! Por favor, revisa tu correo electrónico para confirmar tu cuenta y poder iniciar sesión.');
                return redirect()->to('/login');
            } else {
                // Si falla el envío, eliminar el usuario creado
                $userModel->delete($userId);
                log_message('error', $emailService->printDebugger(['headers']));
                return redirect()->back()->with('error', 'Error al enviar correo de verificación. Por favor intenta nuevamente.')->withInput();
            }
        }
    } catch (\Exception $e) {
        log_message('error', 'Error en registro: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Ocurrió un error durante el registro. Por favor intenta nuevamente.')->withInput();
    }

    return redirect()->back()->with('error', 'Ocurrió un error inesperado.')->withInput();
}

    public function verifyEmail($token)
    {
        $session = session();
        $userModel = new UserModel();
        
        $user = $userModel->where('verification_token', $token)
                         ->where('verification_expires >', date('Y-m-d H:i:s'))
                         ->first();
        
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Token inválido o expirado.');
        }
        
        // Activar cuenta
        $userModel->update($user['id'], [
            'is_active' => 1,
            'verification_token' => null,
            'verification_expires' => null
        ]);
        
        return redirect()->to('/login')->with('success', 'Cuenta verificada correctamente. Ya puedes iniciar sesión.');
    }

    public function login()
    {
        $session = session();

        if ($session->get('logged_in')) {
            return redirect()->to('/list-skates');
        } else {
            return view('login');
        }
    }

    public function loginUser()
    {
        $userModel = new UserModel();
        $session = session();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // Verificar si la cuenta está activa
            if (!$user['is_active']) {
                return redirect()->back()->with('error', 'Tu cuenta no está activada. Por favor verifica tu correo electrónico.');
            }

            log_message('debug', 'User found: ' . print_r($user, true));

            if (password_verify($password, $user['password'])) {
                log_message('debug', 'Password verified successfully.');
                
                // Configurar datos de sesión
                $session->set([
                    'username' => $user['username'],
                    'user_id' => $user['id'],
                    'logged_in' => true,
                ]);
                
                // Verificar si hay una URL de redirección guardada
                $redirect_url = $session->get('redirect_url');
                
                if ($redirect_url) {
                    // Eliminar la URL de redirección de la sesión
                    $session->remove('redirect_url');
                    return redirect()->to($redirect_url);
                }
                
                // Redirección por defecto
                return redirect()->to('/list-skates');
            } else {
                log_message('debug', 'Password verification failed.');
                return redirect()->back()->with('error', 'Contraseña incorrecta');
            }
        } else {
            log_message('debug', 'User not found with email: ' . $email);
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/primerpagina');
    }

    public function listSkates()
    {
        $session = session();

        if ($session->get('logged_in')) {
            $userId = $session->get('user_id');
            $skateModel = new SkateModel();

            // Obtener solo los skates asociados al ID del usuario
            try {
                $skates = $skateModel->where('ID_usuario', $userId)->findAll();

                if (empty($skates)) {
                    return view('list_skates', ['message' => 'Este usuario aún no tiene skates.']);
                }

                return view('list_skates', ['skates' => $skates]);

            } catch (\Exception $e) {
                // Manejar cualquier excepción que pueda ocurrir durante la consulta
                log_message('error', 'Error al obtener skates: ' . $e->getMessage());
                return redirect()->to('/list-skates')->with('error', 'No se ha encontrado información de tu skate.');
            }
        } else {
            return redirect()->to('/login');
        }
    }

    public function viewSkate($codigo)
    {
        $session = session();

        if ($session->get('logged_in')) {
            $skateModel = new SkateModel();

            // Obtener datos del skate con ubicación
            try {
                $skate = $skateModel->getSkateWithLocation($codigo);

                if (!$skate) {
                    return redirect()->to('/list-skates')->with('error', 'Skate no encontrado.');
                }

                // Pasar datos a la vista
                return view('welcome', ['skate' => $skate]);

            } catch (\Exception $e) {
                log_message('error', 'Error al obtener el skate con ubicación: ' . $e->getMessage());
                return redirect()->to('/list-skates')->with('error', 'No se ha podido obtener información del skate.');
            }
        } else {
            return redirect()->to('/login');
        }
    }

    public function addSkate()
    {
        $skateModel = new SkateModel();
        
        // Verifica si el usuario ha iniciado sesión
        $ID_usuario = session()->get('user_id');
        
        if (empty($ID_usuario)) {
            return redirect()->back()->with('error', 'Debes estar autenticado para agregar un skate.');
        }
    
        // Obtener el código del skate desde el formulario
        $codigo = $this->request->getPost('codigo');
    
        // Validar que se ha ingresado un código
        if (empty($codigo)) {
            return redirect()->back()->with('error', 'Debe ingresar el código del skate.');
        }
    
        // Intentar vincular el skate al usuario
        try {
            if ($skateModel->addSkate($codigo, $ID_usuario)) {
                return redirect()->to('/list-skates')->with('message', 'Skate vinculado exitosamente.');
            } else {
                return redirect()->back()->with('error', 'El código del skate ya está vinculado a otro usuario o no existe.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al vincular el skate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se ha podido vincular el skate.');
        }
    }

    public function unlinkSkate($codigo)
    {
        $skateModel = new SkateModel();

        // Verifica si el skate está vinculado al usuario actual
        $ID_usuario = session()->get('user_id');
        $skate = $skateModel->where('codigo', $codigo)->first();

        if ($skate && $skate['ID_usuario'] == $ID_usuario) {
            try {
                // Intentar desvincular el skate del usuario
                if ($skateModel->unlinkSkate($codigo)) {
                    return redirect()->to('/list-skates')->with('message', 'Skate desvinculado exitosamente.');
                } else {

                    return redirect()->back()->with('error', 'No se pudo borrar este apodo');
                }
            } catch (\Exception $e) {
                log_message('error', 'Error al desvincular el skate: ' . $e->getMessage());
                return redirect()->back()->with('error', 'No se pudo borrar este apodo');

            }
        } else {
            return redirect()->back()->with('error', 'No puedes desvincular este skate.');
        }
    }
    public function deleteapodo($codigo)
    {
        $skateModel = new SkateModel();

        // Verifica si el skate está vinculado al usuario actual
        $ID_usuario = session()->get('user_id');
        $skate = $skateModel->where('codigo', $codigo)->first();

        if ($skate && $skate['ID_usuario'] == $ID_usuario) {
            try {
                // Intentar desvincular el skate del usuario
                if ($skateModel->deleteapodo($codigo)) {
                    return redirect()->to('/list-skates')->with('message', 'Apodo borrado exitosamente.');
                } else {
                    return redirect()->back()->with('error', 'No se pudo borrar este apodo');
                }
            } catch (\Exception $e) {
                log_message('error', 'Error al desvincular el skate: ' . $e->getMessage());
                return redirect()->back()->with('error', 'No se pudo borrar este apodo');
            }
        } else {
            return redirect()->back()->with('error', 'No puedes borrar este apodo.');
        }
    }
    public function forgotPassword()
    {
        return view('forgot_password'); // Asegúrate de tener la vista de recuperación de contraseña
    }

    public function primerpag()
    {
        // Cargar la vista principal (landing page)
        return view('Primerpagina');
    }
    public function detail($id) {
        $session = session();
        
        if ($session->get('logged_in')) {
            // Definir los modelos de skates en un array
            $modelos = [
                1 => [
                    'id' => 1,
                    'nombre' => 'E-Skate 1',
                    'precio' => 299,
                    'descripcion' => 'Descripción del Modelo E-Skate 1.',
                    'imagen' => 'https://imgs.search.brave.com/tps24H47-2oaLseYhRphCnOSszeFXtoK-3EaI9JezrA/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9za2F0/ZXNlbGVjdHJpY29z/LmNvbS93cC1jb250/ZW50L3VwbG9hZHMv/MjAyMS8wNi9tZWVw/by1taW5pMi1zY2Fs/ZWQuanBlZw'
                ],
                2 => [
                    'id' => 2,
                    'nombre' => 'E-Skate 2',
                    'precio' => 599,
                    'descripcion' => 'Descripción del Modelo E-Skate 2.',
                    'imagen' => 'https://imgs.search.brave.com/qH8RsQ019QLQkGLFWZExzsnL4kvsrQ_GwfP-ckTx5pI/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NTF1a3dQK3F5b1Mu/anBn'
                ],
                3 => [
                    'id' => 3,
                    'nombre' => 'E-Skate 3',
                    'precio' => 699,
                    'descripcion' => 'Descripción del Modelo E-Skate 3.',
                    'imagen' => 'https://imgs.search.brave.com/4hfX1Aw6h9uwaa7HX6i2vtgTdUT3mvVz1GoT5ojtQQE/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NDFNMnd5YTMzMEwu/anBn'
                ],
            ];
        
            // Registro de depuración en el log
            log_message('debug', 'ID recibido: ' . $id);
            log_message('debug', 'Modelos disponibles: ' . print_r(array_keys($modelos), true));
        
            // Verifica si el modelo existe
            if (!array_key_exists($id, $modelos)) {
                log_message('error', 'Modelo no encontrado para el ID: ' . $id);
                throw new \CodeIgniter\Exceptions\PageNotFoundException("Modelo no encontrado");
            }
        
            // Obtener los otros modelos
            $otrosModelos = array_filter($modelos, function($modelo) use ($id) {
                return $modelo['id'] != $id; // Excluye el modelo actual
            });
        
            // Pasa la información a la vista
            log_message('debug', 'Modelo encontrado: ' . print_r($modelos[$id], true));
            return view('skate_detail', [
                'modelo' => $modelos[$id],
                'otrosModelos' => $otrosModelos // Pasa los otros modelos a la vista
            ]);
        } else {
            // Guardar la URL actual en la sesión antes de redirigir
            $session->set('redirect_url', current_url());
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para acceder a esta página');
        }
    }

    public function updateSkateApodo()
{
    $codigo = $this->request->getPost('codigo');
    
    // Validar que se ha ingresado un código
    if (empty($codigo)) {
        return redirect()->back()->with('error', 'Debe ingresar el código del skate.');
    }
    // Obtener el apodo desde la solicitud
    $apodo = $this->request->getPost('apodo');

    if (!$apodo) {
        return redirect()->back()->with('error', 'El apodo es requerido.');
    }

    // Actualizar el apodo usando el modelo
    $resultado = $this->skateModel->updateApodo($codigo, $apodo);

    if ($resultado) {
        return redirect()->to('/list-skates')->with('message', 'Apodo cambiado exitosamente.');
    } else {
        return redirect()->back()->with('error', 'No se pudo actualizar el apodo. Verifica el código.');
    }

}
public function enviarmail()
{
    // Cargar el servicio de correo
    $emailService = \Config\Services::email();
    // Obtener los datos del formulario
    $nombre = $this->request->getPost('nombre');
    $correo = $this->request->getPost('email');
    $telefono = $this->request->getPost('telefono');
    $mensaje = $this->request->getPost('mensaje');
    // Configurar el correo
    $emailService->setFrom($correo,$nombre); // Cambiar según tu configuración
    $emailService->setTo('eskatevz@gmail.com'); // Cambiar al correo donde se reciban los mensajes
    $emailService->setSubject('Nuevo mensaje de contacto');
    
    // Cuerpo del correo
    $cuerpo = "
        <p>Hola, tienes un nuevo mensaje de contacto:</p>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Correo:</strong> $correo</p>
        <p><strong>Teléfono:</strong> $telefono</p>
        <p><strong>Mensaje:</strong><br>$mensaje</p>
    ";
    
    $emailService->setMessage($cuerpo);
    $emailService->setMailType('html'); // Para enviar en formato HTML
    // Enviar el correo
    if ($emailService->send()) {
        return redirect()->to('/')->with('message', 'Tu mensaje ha sido enviado exitosamente.');
    } else {
        // Obtener errores en caso de fallo
        $error = $emailService->printDebugger(['headers']);
        log_message('error', $error); // Log del error
        return redirect()->back()->with('error', 'Hubo un problema al enviar tu mensaje. Inténtalo de nuevo.');
    }
}
public function comprar($precio = 0)
{
    $session = session();
    
    // Verificar que el usuario está logueado
    if (!$session->get('logged_in')) {
        // Guardar la URL actual (con precio) para redirigir después del login
        $session->set('redirect_url', current_url());
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión para realizar una compra');
    }

    // GUARDAR EL PRECIO TEMPORALMENTE EN SESIÓN
    $session->set('temp_precio_compra', $precio);

    // Obtener el ID del usuario
    $userId = $session->get('user_id');
    
    // Obtener las direcciones asociadas a este usuario
    $direccionModel = new DireccionModel();
    $userAddresses = $direccionModel->where('ID_usuario', $userId)->findAll();
    
    // Verificar si hay direcciones
    if (empty($userAddresses)) {
        $session->setFlashdata('error', 'No tienes direcciones registradas.');
        return redirect()->to('nuevadireccion');
    }

    // Obtener las provincias y localidades
    $provinciaModel = new ProvinciaModel();
    $localidadModel = new LocalidadModel();

    foreach ($userAddresses as &$address) {
        $provincia = $provinciaModel->find($address['ID_provincia']);
        $localidad = $localidadModel->find($address['ID_localidad']);
        
        $address['provincia_nombre'] = $provincia ? $provincia['provincia'] : 'Desconocida';
        $address['localidad_nombre'] = $localidad ? $localidad['localidad'] : 'Desconocida';
    }

    $Data = [
        'monto_skate' => $precio,
        'userAddresses' => $userAddresses
    ];

    return view('comprar', $Data);
}
public function guardar()
{
    // Verificar que el usuario está logueado
    $userId = session()->get('user_id');
    if (!$userId) {
        return redirect()->to('/login');
    }

    // Obtener las direcciones del usuario
    $direccionModel = new DireccionModel();
    $direcciones = $direccionModel->getDireccionesPorUsuario($userId);

    // Obtener provincias disponibles
    $provinciaModel = new ProvinciaModel();
    $provincias = $provinciaModel->findAll();

    // Obtener todas las localidades para usar con la función de búsqueda en el frontend
    $localidadModel = new LocalidadModel();
    $localidades = $localidadModel->select('id, localidad, id_provincia')->findAll();

    // Cargar la vista con las direcciones y las provincias/localidades
    return view('nuevadireccion', [
        'direcciones' => $direcciones,
        'provincias'  => $provincias,
        'localidades' => $localidades
    ]);
}

public function guardarNueva()
{
    // Validar los campos
    if (!$this->validate([
        'calle' => 'required|string',
        'numero' => 'required|integer',
        'localidad' => 'required|string',
        'provincia' => 'required|string',
    ])) {
        session()->setFlashdata('error', 'Por favor, completa todos los campos correctamente.');
        return redirect()->to('/nuevadireccion');
    }

    // Obtener los valores del formulario
    $calle = $this->request->getPost('calle');
    $numero = $this->request->getPost('numero');
    $localidadNombre = $this->request->getPost('localidad');
    $provinciaNombre = $this->request->getPost('provincia');
    $user_id = session()->get('user_id');

    if (!$user_id) {
        session()->setFlashdata('error', 'No se pudo identificar al usuario.');
        return redirect()->to('/');
    }
    
    // Buscar el ID de la localidad
    $localidadModel = new LocalidadModel();
    $localidad = $localidadModel->where('localidad', $localidadNombre)->first();
    $id_localidad = $localidad ? $localidad['id'] : null;

    // Buscar el ID de la provincia
    $provinciaModel = new ProvinciaModel();
    $provincia = $provinciaModel->where('provincia', $provinciaNombre)->first();
    $id_provincia = $provincia ? $provincia['id'] : null;

    // Verificar si se encontraron los IDs
    if (!$id_localidad || !$id_provincia) {
        session()->setFlashdata('error', 'La localidad o provincia no se encuentran registradas.');
        return redirect()->to('/nuevadireccion');
    }

    // Guardar la dirección
    $direccionModel = new DireccionModel();
    $direccionData = [
        'calle' => $calle,
        'numero' => $numero,
        'ID_localidad' => $id_localidad,
        'ID_provincia' => $id_provincia,
        'ID_usuario' => $user_id 
    ];

    if (!$direccionModel->save($direccionData)) {
        session()->setFlashdata('error', 'Hubo un error al guardar la dirección.');
        return redirect()->to('/nuevadireccion');
    }

    // ÉXITO: Recuperar el precio guardado y redirigir
    $session = session();
    $precio = $session->get('temp_precio_compra') ?? 0;
    $session->remove('temp_precio_compra'); // Limpiar

    session()->setFlashdata('success', 'Dirección guardada correctamente.');

    // Redirigir a /comprar con el precio correcto
    if ($precio > 0) {
        return redirect()->to("/comprar/{$precio}");
    } else {
        // Fallback si no hay precio (poco probable)
        return redirect()->to('/list-skates');
    }
}
public function obtenerLocalidadesPorProvincia($provinciaId)
{
    $localidadModel = new LocalidadModel();

    // Obtener las localidades asociadas a la provincia
    $localidades = $localidadModel->where('id_provincia', $provinciaId)->findAll();

    if ($localidades) {
        return $this->response->setJSON($localidades);  // Devuelve las localidades en formato JSON
    } else {
        // Si no hay localidades, devuelve un error 404
        return $this->response->setStatusCode(404, 'No se encontraron localidades');
    }
}
public function updateUserProfile()
{
    $session = session();
    $userModel = new UserModel();
    $userId = $session->get('user_id');
    $currentUser = $userModel->find($userId);

    if (!$session->get('logged_in') || !$currentUser) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión para actualizar tu perfil');
    }

    $username = $this->request->getPost('username');
    $newEmail = $this->request->getPost('email');
    $newPassword = $this->request->getPost('new_password');
    $currentPassword = $this->request->getPost('current_password');

    if (!$userModel->verifyPassword($currentUser['email'], $currentPassword)) {
        return redirect()->back()->with('error', 'La contraseña actual es incorrecta');
    }

    // Procesar cambio de username
    if ($username && $username !== $currentUser['username']) {
        try {
            $userModel->update($userId, ['username' => $username]);
            $session->set('username', $username);
            $session->setFlashdata('success', 'Nombre de usuario actualizado correctamente.');
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar username: ' . $e->getMessage());
            $session->setFlashdata('error', 'Error al actualizar nombre de usuario');
        }
    }

    // Procesar cambio de email
    if ($newEmail && $newEmail !== $currentUser['email']) {
        if ($userModel->where('email', $newEmail)->first()) {
            $session->setFlashdata('error', 'El correo electrónico ya está en uso');
        } else {
            $emailToken = bin2hex(random_bytes(16));
            $emailExpire = date('Y-m-d H:i:s', strtotime('+1 hour'));

            if ($userModel->setEmailResetToken($userId, $emailToken, $emailExpire)) {
                $confirmLink = site_url('profile/confirm-email/' . $emailToken);
                
                $emailService = \Config\Services::email();
                $emailService->clear();
                
                $emailService->setTo($currentUser['email']);
                $emailService->setSubject('Confirma tu nuevo correo electrónico');
                $emailService->setMessage("
                    <h2>Confirmación de cambio de email</h2>
                    <p>Hola {$currentUser['username']},</p>
                    <p>Has solicitado cambiar tu correo electrónico a: {$newEmail}</p>
                    <p>Por favor haz clic en el siguiente enlace para confirmar el cambio:</p>
                    <p><a href='{$confirmLink}'>{$confirmLink}</a></p>
                    <p>Si no solicitaste este cambio, por favor ignora este mensaje.</p>
                ");
                $emailService->setMailType('html');

                if ($emailService->send()) {
                    $session->set('pending_new_email', $newEmail);
                    $session->setFlashdata('success', 'Se ha enviado un enlace de confirmación a tu correo actual.');
                } else {
                    $session->setFlashdata('error', 'Error al enviar correo de confirmación de email');
                    log_message('error', $emailService->printDebugger(['headers']));
                }
            } else {
                $session->setFlashdata('error', 'Error al generar token de confirmación de email');
            }
        }
    }

    // Procesar cambio de contraseña
    if ($newPassword) {
        $passwordToken = bin2hex(random_bytes(16));
        $passwordExpire = date('Y-m-d H:i:s', strtotime('+1 hour'));

        if ($userModel->setPasswordResetToken($currentUser['email'], $passwordToken, $passwordExpire)) {
            $confirmLink = site_url('profile/confirm-password/' . $passwordToken);
            
            $emailService = \Config\Services::email();
            $emailService->clear();
            
            $emailService->setTo($currentUser['email']);
            $emailService->setSubject('Confirma el cambio de tu contraseña');
            $emailService->setMessage("
                <h2>Confirmación de cambio de contraseña</h2>
                <p>Hola {$currentUser['username']},</p>
                <p>Has solicitado cambiar tu contraseña.</p>
                <p>Por favor haz clic en el siguiente enlace para confirmar el cambio:</p>
                <p><a href='{$confirmLink}'>{$confirmLink}</a></p>
                <p>Si no solicitaste este cambio, por favor cambia tu contraseña inmediatamente.</p>
            ");
            $emailService->setMailType('html');

            if ($emailService->send()) {
                $session->set('pending_new_password', password_hash($newPassword, PASSWORD_DEFAULT));
                $session->setFlashdata('success', 'Se ha enviado un enlace de confirmación a tu correo actual.');
            } else {
                $session->setFlashdata('error', 'Error al enviar correo de confirmación de contraseña');
                log_message('error', $emailService->printDebugger(['headers']));
            }
        } else {
            $session->setFlashdata('error', 'Error al generar token de confirmación de contraseña');
        }
    }

    return redirect()->to('/profile');
}

public function confirmEmail($token)
{
    $session = session();
    $userModel = new UserModel();

    $user = $userModel->verifyEmailToken($token);
    $newEmail = $session->get('pending_new_email');

    if ($user && $newEmail) {
        $userModel->update($user['id'], [
            'email' => $newEmail,
            'email_reset_token' => null,
            'email_reset_expire' => null
        ]);
        $session->set('email', $newEmail);
        $session->remove('pending_new_email');
        return redirect()->to('/profile')->with('success', 'Correo electrónico actualizado correctamente');
    }

    return redirect()->to('/profile')->with('error', 'Token inválido o expirado');
}

public function confirmPassword($token)
{
    $session = session();
    $userModel = new UserModel();

    $user = $userModel->verifyPasswordToken($token);
    $newPassword = $session->get('pending_new_password');

    if ($user && $newPassword) {
        $userModel->update($user['id'], [
            'password' => $newPassword,
            'reset_token' => null,
            'reset_expiration' => null
        ]);
        $session->remove('pending_new_password');
        return redirect()->to('/profile')->with('success', 'Contraseña actualizada correctamente');
    }

    return redirect()->to('/profile')->with('error', 'Token inválido o expirado');
}
public function profile()
{
    $session = session();

    if (!$session->get('logged_in')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión para ver tu perfil');
    }

    $userModel = new UserModel();
    $user = $userModel->find($session->get('user_id'));

    return view('profile', ['user' => $user]);
}
public function eliminarUsuariosNoVerificadosCron()
{
    $userModel = new UserModel();
    $cutoff = date('Y-m-d H:i:s');

    $deletedUsers = $userModel->where('is_active', 0)
                              ->where('verification_expires <', $cutoff)
                              ->delete();

    if ($deletedUsers) {
        log_message('info', "Cron Job: Se eliminaron {$deletedUsers} cuentas no verificadas.");
        echo "OK: Se eliminaron {$deletedUsers} cuentas.\n";
    } else {
        log_message('info', "Cron Job: No se encontraron cuentas no verificadas para eliminar.");
        echo "OK: No se encontraron cuentas para eliminar.\n";
    }
}

}
