<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\skatemodel;

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

    public function registerUser()
    {
        $userModel = new UserModel();
    
        // Obtener datos del formulario
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password') 
        ];
    
        // Verificar si el email ya existe
        if ($userModel->where('email', $data['email'])->first()) {
            return redirect()->back()->with('error', 'El correo electrónico ya está registrado.')->withInput();
        }
    
        // Guardar el nuevo usuario
        $userModel->save($data);
    
        return redirect()->to('/login')->with('success', 'Registro exitoso');
    }

    public function login()
    {
        $session = session();

        if ($session->get('logged_in')) {
            return redirect()->to('/list-skates');
        }
        else{
        return view('login');
    }
}

    public function loginUser()
    {
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            log_message('debug', 'User found: ' . print_r($user, true));

            if (password_verify($password, $user['password'])) {
                log_message('debug', 'Password verified successfully.');
                session()->set([
                    'username' => $user['username'],
                    'user_id' => $user['id'],
                    'logged_in' => true,
                ]);
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
        if ($session->get('logged_in')) {// Definir los modelos de skates en un array
        $modelos = [
            1 => [
                'id' => 1,
                'nombre' => 'E-Skate 1',
                'precio' => '$299',
                'descripcion' => 'Descripción del Modelo E-Skate 1.',
                'imagen' => 'https://imgs.search.brave.com/tps24H47-2oaLseYhRphCnOSszeFXtoK-3EaI9JezrA/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9za2F0/ZXNlbGVjdHJpY29z/LmNvbS93cC1jb250/ZW50L3VwbG9hZHMv/MjAyMS8wNi9tZWVw/by1taW5pMi1zY2Fs/ZWQuanBlZw'
            ],
            2 => [
                'id' => 2,
                'nombre' => 'E-Skate 2',
                'precio' => '$599',
                'descripcion' => 'Descripción del Modelo E-Skate 2.',
                'imagen' => 'https://imgs.search.brave.com/qH8RsQ019QLQkGLFWZExzsnL4kvsrQ_GwfP-ckTx5pI/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NTF1a3dQK3F5b1Mu/anBn'
            ],
            3 => [
                'id' => 3,
                'nombre' => 'E-Skate 3',
                'precio' => '$699',
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
    }else{
        return redirect()->to('/login');
    }}

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
    $emailService->setFrom('eskatevz@gmail.com', 'E-Skate'); // Dirección que coincide con $SMTPUser // Cambiar según tu configuración
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
}