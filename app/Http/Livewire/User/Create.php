<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class Create extends Component
{
    public $pageTitle = 'Create Product';
    public $name;
    public $email;
    public $password;
    public $aboutMe;
    public $showCloseProductsCards;
    public $products = [];

    public function mount()
    {
        $this->showCloseProductsCards = false;
    }

    public function createOrUpdate()
    {
        $array1 = explode(',', $this->name);
        $array2 = explode(',', $this->email);
        $this->get_combinations(['item1' => $array1, 'item2' => $array2]);
    }

    //CPD
    public function get_combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        $variant = '';
        foreach ($result as $item) {
            foreach ($item as $i) {
                $variant = $variant . '/' . $i;
            }
            array_push($this->products, ltrim($variant, '/'));
            $variant = '';
        }
        $this->showCloseProductsCards = true;
        $this->notify('product is declared successfully');
    }

    public function removeItem($title)
    {
        if (($key = array_search($title, $this->products)) !== false) {
            unset($this->products[$key]);
        }
        $this->notify('product is removed successfully', '#dc3545');
    }

    public function notify($message = 'saved', $color = "#4fbe87")
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    public function render()
    {
        return view('livewire.user.create')->extends('layouts.app')->section('content');
    }
}
