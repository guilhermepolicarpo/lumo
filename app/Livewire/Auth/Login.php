<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';


    public function mount()
    {
        if ($this->itIsLoggedIn()) {
            return redirect()->route('dashboard');
        }
    }


    #[Layout('components.layouts.guest')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }


    public function login()
    {
        $credentials = $this->validate();

        if (auth()->attempt($credentials)) {
            request()->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'E-mail ou senha invalidos.');
    }


    private function itIsLoggedIn()
    {
        return auth()->user();
    }
}
