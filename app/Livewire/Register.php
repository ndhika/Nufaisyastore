<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class Register extends Component
{
    public $name, $email, $password, $phone_number;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone_number' => 'required|numeric|digits_between:10,15|unique:users,phone_number',
    ];

    public function register()
    {
        $this->validate();

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->phone_number = $this->phone_number;
        $user->save();
        
        // Login otomatis
        Auth::login($user);

        // Redirect ke halaman home
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.register')
            ->layout('components.layouts.app');
    }
}
