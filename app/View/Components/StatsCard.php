<?php
namespace App\View\Components;

use Illuminate\View\Component;

class StatsCard extends Component
{
    public $title;
    public $value;
    public $icon;

    public function __construct($title, $value, $icon)
    {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.stats-card');
    }
}
