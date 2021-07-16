<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Livewire\Component;

class Login extends Component
{
    use AuthenticatesUsers;

    public $email;
    public $password;
    public $rememberMe;

    protected $rules = [
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ];

    public function render()
    {
        return view('livewire.auth.login')
            ->extends('layouts.auth')
            ->section('content');
    }

    public function login()
    {
        $credentials = $this->validate();
        if (!auth()->attempt($credentials, $this->rememberMe)) {
            $this->addError('email', trans('auth.failed'));
            return;
        }
        return redirect()->route('dashboard');
    }
}
