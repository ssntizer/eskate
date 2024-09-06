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

    $data = [
        'username' => $this->request->getPost('username'),
        'email' => $this->request->getPost('email'),
        'password' =>$this->request->getPost('password')
    ];

    $userModel->save($data);

    return redirect()->to('/login')->with('success', 'Registration successful');
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
            return redirect()->back()->with('error', 'Invalid Password');
        }
    } else {
        log_message('debug', 'User not found with email: ' . $email);
        return redirect()->back()->with('error', 'User not found');
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
                    return view('list_skates', ['message' => 'No skates found for this user.']);
                }
    
                return view('list_skates', ['skates' => $skates]);
    
            } catch (\Exception $e) {
                // Manejar cualquier excepción que pueda ocurrir durante la consulta
                return redirect()->to('/error')->with('error', 'An error occurred while retrieving skates.');
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
                return redirect()->to('/list-skates')->with('error', 'Skate not found.');
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

    // Intentar agregar el nuevo skate
    if ($skateModel->addSkate($codigo, $ID_usuario)) {
        return redirect()->to('/list-skates')->with('message', 'Skate agregado exitosamente.');
    } else {
        return redirect()->back()->with('error', 'El código del skate ya existe o ocurrió un error.');
    }
}
    
}
