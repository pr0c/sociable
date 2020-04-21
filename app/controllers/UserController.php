<?php

namespace App\Controllers;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Controller.php';
include $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';

use App\Models\User;
use Vendor\Controller;
use Vendor\Core;
use Vendor\Request;

class UserController extends Controller {
    public function profile(Request $request) {
        if(!$request->isExist('id')) {
            $user = User::find(Core::$auth->getId());
        }
        else {
            $user = User::find(1);
        }

        return $this->render('user/profile.php.twig', ['user' => $user->getFields()]);
    }

    public function register(Request $request) {
        if($request->isExist('login') && $request->isExist('password')) {
            $user = new User(['login' => $request->login, 'password' => $request->password]);
            $user->save();
        }
        else return $this->render('user/register.php.twig');
    }

    public function login(Request $request) {
        if($request->isExist('login') && $request->isExist('password')) {
            $user = User::where(['login' => $request->login])->first();
            if(!is_null($user)) {
                if($request->password == $user->password) {
                    Core::$auth->auth($user);
                    Core::$router->redirect('news');
                }
                else Core::$router->redirect('login');
            }
        }
        else return $this->render('user/login.php.twig');
    }

    public function logout() {
        Core::$auth->logout();
        Core::$router->redirect('news');
    }

    // public function save(Request $request) {
        
    // }
}