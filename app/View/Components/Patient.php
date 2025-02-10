<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class Patient extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public string $last_name,
        public string $birth_date,
        public string $address,
        public string $city,
        public int    $postal_code,
        public string $dni,
        public int    $health_card_number,
        public string $phone,
        public string $email,
        public int    $zone_id,
        public string $personal_situation,
        public string $health_situation,
        public string $housing_situation,
        public string $personal_autonomy,
        public string $economic_situation
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.patient');
    }
}
