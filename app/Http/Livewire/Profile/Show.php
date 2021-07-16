<?php

namespace App\Http\Livewire\Profile;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public $pageTitle = 'Profile';
    public $saved = false;
    public $name;
    public $email;
    public $password = '';
    public $passwordConfirmation = '';
    public $user;
    public $gender;
    public $has_job;
    public $about;
    public $birthday;
    public $social_status;
    public $avatar;
    public $newAvatar;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->about = $this->user->about;
        $this->birthday = $this->user->birthday;
        $this->has_job = $this->user->has_job;
        $this->gender = $this->user->gender;
        $this->social_status = $this->user->social_status;
        $this->newAvatar = $this->user->avatar;
    }

    public function updated()
    {
        $this->validate([
            'email' => [Rule::unique('users')->ignore($this->user->id)],
        ]);
    }

    public function save()
    {
        $this->validation();
        $this->avatar && $this->newAvatar = $this->avatar->store('/', 'files');
        $this->updateUser();
        $this->dispatchBrowserEvent('notify', ['message' => 'Profile has been saved successfully!', 'color' => '#4fbe87']);
        $this->password = $this->passwordConfirmation = '';

    }

    private function validation()
    {
        $this->validate([
            'email'    => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'min:6', 'same:passwordConfirmation'],
            'avatar'   => ['nullable', 'image', 'max:20'],
            'birthday' => ['required']
        ]);
    }

    private function updateUser(): void
    {
        $birthday = Carbon::parse($this->birthday);
        $this->user->update([
            'name'          => $this->name,
            'email'         => $this->email,
            'about'         => $this->about,
            'birthday'      => $birthday,
            'has_job'       => $this->has_job,
            'gender'        => $this->gender,
            'social_status' => $this->social_status,
            'avatar'        => $this->newAvatar,
        ]);
        if ($this->password) {
            $this->user->password = bcrypt($this->password);
            $this->user->save();
        }
    }

    public function render()
    {
        return view('livewire.profile.show')
            ->extends('layouts.app')
            ->section('content');
    }

}

