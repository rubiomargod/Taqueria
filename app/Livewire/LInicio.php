<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LInicio extends Component
{
  public $userName;

  public function mount()
  {
    $this->userName = Auth::user()->name ?? 'Invitado';
  }

  public function render()
  {
    return view('livewire.l-inicio');
  }
}
