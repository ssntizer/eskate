<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SkateModel;
use App\Models\UbicacionModel;

class AuthController extends BaseController
{
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
        return view('login');
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
        return redirect()->to('/login');
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
                    return view('list_skates', ['message' => 'Este usuario aun no tiene skates.']);
                }

                return view('list_skates', ['skates' => $skates]);

            } catch (\Exception $e) {
                // Manejar cualquier excepción que pueda ocurrir durante la consulta
                return redirect()->to('/error')->with('error', 'No se ha encontrado informacion de tu skate.');
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
            $ubicacionModel = new UbicacionModel();

            // Obtener datos del skate con ubicación
            $skate = $skateModel->getSkateWithLocation($codigo);

            if (!$skate) {
                return redirect()->to('/list-skates')->with('error', 'Skate no encontrada.');
            }

            // Pasar datos a la vista
            return view('welcome', ['skate' => $skate]);
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
        if ($skateModel->addSkate($codigo, $ID_usuario)) {
            return redirect()->to('/list-skates')->with('message', 'Skate vinculado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'El código del skate ya está vinculado a otro usuario o no existe.');
        }
    }

    public function unlinkSkate($codigo)
    {
        $skateModel = new SkateModel();

        // Verifica si el skate está vinculado al usuario actual
        $ID_usuario = session()->get('user_id');
        $skate = $skateModel->where('codigo', $codigo)->first();

        if ($skate && $skate['ID_usuario'] == $ID_usuario) {
            // Intentar desvincular el skate del usuario
            if ($skateModel->unlinkSkate($codigo)) {
                return redirect()->to('/list-skates')->with('message', 'Skate desvinculado exitosamente.');
            } else {
                return redirect()->back()->with('error', 'No se pudo desvincular el skate.');
            }
        } else {
            return redirect()->back()->with('error', 'No puedes desvincular este skate.');
        }
    }
    public function forgotPassword(){
    return view('forgot_password'); // Asegúrate de tener la vista de recuperación de contraseña
}
public function primerpag()
{
    // Cargar la vista principal (landing page)
    return view('Primerpagina');
}
}


