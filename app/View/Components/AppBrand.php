<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <a href="{{ route('dashboard') }}" wire:navigate>
                    <!-- Hidden when collapsed -->
                    <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
                        <div class="flex items-center gap-2">
                            <x-icon name="o-sun" class="-mb-1 text-purple-500 w-7" />
                            <span class="mr-3 text-3xl font-bold text-transparent bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text ">
                                Lumo
                            </span>
                        </div>
                    </div>

                    <!-- Display when collapsed -->
                    <div class="display-when-collapsed hidden mx-5 mt-4 lg:mb-6 h-[28px]">
                        <x-icon name="s-sun" class="-mb-1 text-purple-500 w-7" />
                    </div>
                </a>
            HTML;
    }
}
