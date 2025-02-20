<?php

namespace App\Livewire;

use Livewire\Component;

class About extends Component
{
    public function render()
    {
        return view('livewire.about')->layout('components.layouts.app');
    }
}
