<?php

namespace App\Livewire\Adminip\Ips\Index;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Check extends Component
{
    public $color = 'bg-gray-200';
    public $texto = 'Check';
    public $ip;

    protected $listeners = ['refreshColor' => '$refresh'];

    public function mount($ip) 
    {
        $this->ip = $ip;
    }

    public function checkear()
    {
        $response = Http::get('http://172.17.9.231/checkip/index.php?ip='.$this->ip->ip)['activo'];
        if($response === true) {
            $this->color = 'bg-green-500';
            $this->texto = 'Activo';
            $this->render();
        } else {
            $this->color = 'bg-red-500';
            $this->texto = 'Caído';
            $this->render();
        }

    }

    public function render()
    {
        return view('livewire.adminip.ips.index.check');
    }
}
