<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class langSelector extends Component
{
    public string $to;
    public string $currentLang;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->currentLang = app()->getLocale();

        $this->to = match($this->currentLang) {
            'en' => '/',
            'es' => '/en',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.lang-selector');
    }
}
