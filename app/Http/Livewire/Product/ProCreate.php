<?php

namespace App\Http\Livewire\Product;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProCreate extends Component
{
    use WithFileUploads;

    public string $pageTitle = 'Create Product';
    public string $color = '';
    public string $size = '';
    public int $quantity = 0;
    public Product $product;
    public array $prices = [];
    public array $quantities = [];
    public array $images = [];
    public $categories;
    public $seasons;
    public bool $showCloseProductsCards = false;
    public array $inventories = [];

    public function mount()
    {
        $this->product = new Product();
    }

    public function rules(): array
    {
        return [
            'product.name'        => 'required|string|max:255',
            'product.description' => 'nullable|string|max:640000',
            'product.category_id' => 'required|exists:product_categories,id',
            'product.season_id'   => 'required|exists:seasons,id',
        ];
    }


    public function showInventories()
    {
        $this->inventories = [];
        $array1 = explode(',', $this->color);
        $array2 = explode(',', $this->size);
        $this->getCombinations(['item1' => $array1, 'item2' => $array2]);
    }

    //CPD
    public function getCombinations($arrays)
    {
        $this->images = [];
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
            array_push($this->inventories, ltrim($variant, '/'));
            $variant = '';
        }
        $this->showCloseProductsCards = true;
//        $this->notify('product is declared successfully');
    }

    public function removeItem($title)
    {
        if (($key = array_search($title, $this->inventories)) !== false) {
            unset($this->inventories[$key]);
        }
        $this->notify('product is removed successfully', '#dc3545');
    }

    public function createInventories()
    {
        DB::beginTransaction();
        $this->validate();
        $this->createProduct();
        $this->validate([
            'images'       => 'required|array',
            'images.*'     => 'required|image',
            'prices'       => 'required|array',
            'prices.*'     => 'required|numeric|max:99999999',
            'quantities'   => 'required|array',
            'quantities.*' => 'required|numeric|max:99999999',
        ]);
        foreach ($this->inventories as $inventory) {
            $price = $this->prices[$inventory];
            $quantity = $this->quantities[$inventory];
            $image = $this->images[$inventory];
            $this->createInventory($inventory, $price, $quantity, $image);
        }
        DB::commit();
        $this->notify('Product created successfully');
        return redirect()->route('products.index');
    }

    public function notify($message = 'saved', $color = "#4fbe87")
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    private function createProduct()
    {
        $this->product->save();
    }

    private function createInventory($inventory, $price, $quantity, $image)
    {
        $colorSize = explode('/', $inventory);
        $image = $image->store('/', 'files');
        $color = $colorSize[0] ?? '';
        $size = $colorSize[1] ?? '';
        Inventory::create([
            'price'      => $price,
            'quantity'   => $quantity,
            'color'      => $color,
            'size'       => $size,
            'image'      => $image,
            'product_id' => $this->product->id,
        ]);
    }

    public function render()
    {
        if (!$this->categories) {
            $this->categories = ProductCategory::select('id', 'name')->get();
        }
        if (!$this->seasons) {
            $this->seasons = Season::select('id', 'name')->get();
        }

        return view('livewire.product.pro-create', [
            'categories' => $this->categories,
            'seasons'    => $this->seasons,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
