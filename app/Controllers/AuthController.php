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
            'password' => $this->request->getPost('password'),
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
            if (password_verify($password, $user['password'])) {
                session()->set([
                    'username' => $user['username'],
                    'logged_in' => true,
                ]);
                return redirect()->to('/welcome');
            } else {
                return redirect()->back()->with('error', 'Invalid Password');
            }
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function welcome()
{
    $session = session();

    if ($session->get('logged_in')) {
        // Instancia del modelo de la patineta
        $skateModel = new SkateModel();

        // Obtener los datos del skate junto con la ubicación
        $skate = $skateModel->getSkateWithLocation();

        // Verificar si se obtuvieron los datos del skate
        if (!$skate) {
            // Manejo de error: puedes redirigir a otra vista o mostrar un mensaje
            return redirect()->to('/error')->with('error', 'No se encontraron datos de skate.');
        }

        // Pasar los datos a la vista
        return view('welcome', ['skate' => $skate]);
    } else {
        return view('login');
    }
}
}