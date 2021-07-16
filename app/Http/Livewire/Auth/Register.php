<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';

    protected $rules = [
        'email'    => ['required', 'email', 'unique:users'],
        'name'     => ['required'],
        'password' => ['required', 'min:6', 'same:passwordConfirmation'],
    ];

    public function render()
    {
        return view('livewire.auth.register')
            ->extends('layouts.auth')
            ->section('content');
    }

    public function updated()
    {
        $this->validate([
            'email' => [Rule::unique('users')],
            //            'passwordConfirmation' => ['same:password'],
        ]);
    }

    public function register()
    {
        $this->validate();
        $user = User::create([
            'email'    => $this->email,
            'name'     => $this->name,
            'password' => Hash::make($this->password),
        ]);
        auth()->login($user);
        return redirect()->route('dashboard');
    }
}
