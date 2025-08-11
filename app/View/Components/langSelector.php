<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class langSelector extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $currentLang = 'en',
        public $to = null,
    )
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
        return view('components.lang-selector', [
            'currentLang2' => $this->currentLang,
            'to2' => $this->to,
        ]);
    }
}
