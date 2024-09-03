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
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
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

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'username' => $user['username'],
                'logged_in' => true,
            ]);
            return redirect()->to('/list-skates');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
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
            $skateModel = new SkateModel();
            $skates = $skateModel->findAll(); // Asume que quieres obtener todos los skates

            return view('list_skates', ['skates' => $skates]);
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
                return redirect()->to('/list-skates')->with('error', 'Skate no encontrado.');
            }

            // Pasar datos a la vista
            return view('welcome', ['skate' => $skate]);
        } else {
            return redirect()->to('/login');
        }
    }

    public function welcome()
    {
        $session = session();

        if ($session->get('logged_in')) {
            $skateModel = new SkateModel();

            // Aquí podrías obtener un skate por defecto o redirigir a otro método si es necesario
            $skate = $skateModel->getSkateWithLocation('YYYYY1'); // Ejemplo de skate por defecto

            if (!$skate) {
                return redirect()->to('/list-skates')->with('error', 'No se encontraron datos de skate.');
            }

            return view('welcome', ['skate' => $skate]);
        } else {
            return redirect()->to('/login');
        }
    }
}
