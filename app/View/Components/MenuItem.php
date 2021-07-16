<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MenuItem extends Component
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $icon;
    /**
     * @var string
     */
    public $url;
    /**
     * @var bool
     */
    public $isActive;
    /**
     * @var bool
     */
    public $isSingle;
    /**
     * @var array
     */
    public $items;

    /**
     * MenuItem constructor.
     *
     * @param string $title
     * @param string $icon
     * @param string $url
     * @param bool   $isActive
     * @param bool   $isSingle
     * @param array  $items
     */
    public function __construct(string $title = '', string $icon = '', string $url = '#', bool $isActive = false, bool $isSingle = true, array $items = [])
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->url = $url;
        $this->isActive = $isActive;
        $this->isSingle = $isSingle;
        $this->items = $items;
    }

    public function render()
    {
        return view('components.general.partial.menuItem');
    }

    public function setIsActive(): bool
    {
        if ($this->isSingle) {
            return $this->isActive;
        }
        $items = collect($this->items)->pluck('active');
        return $items->contains(function ($value) {
            return $value;
        });
    }
}
