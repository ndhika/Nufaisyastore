<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class Login extends Component
{
    public $email, $password;

    public function render()
    {
        return view('livewire.login')->layout('components.layouts.app');
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return redirect()->to('/');
        } else {
            session()->flash('error', 'Email atau password salah!');
            $this->dispatch('loginFailed');
        }
    }
}

