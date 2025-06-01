<?php

namespace App\Livewire;

use Livewire\Component;

class LInicio extends Component
{
  public function render()
  {
    return view('livewire.l-inicio');
  }
  public function Error()
  {
    return redirect()->route('INICIO')->with('error', 'Error');
  }
  public function Aceptar()
  {
    return redirect()->route('INICIO')->with('success', 'Bien');
  }
}
