<?php

namespace App\Http\Livewire;

use App\Models\Client;
use App\Models\Employee;
use App\Models\inventoryOrder;
use App\Models\Provider;
use Livewire\Component;

class Dashboard extends Component
{
    public bool $activeStats = true;
    public bool $activePopularProducts = false;
    public bool $activePopularClient = false;
    public bool $activePopularEmployee = false;
    public bool $activeUnpopularEmployee = false;
    public bool $activePopularProvider = false;
    public array $orderDetails = [];
    protected $popularClients;
    protected $popularEmployees;
    protected $unpoplularEmployees;
    protected $poplularProivders;


    public function active($tab)
    {
        $this->activeStats =
        $this->activePopularProducts =
        $this->activePopularEmployee =
        $this->activeUnpopularEmployee =
        $this->activePopularProvider =
        $this->activePopularClient = false;
        switch ($tab) {
            case '':
                break;
            case 'stats':
                $this->activeStats = true;
                break;
            case 'popular-products':
                $this->activePopularProducts = true;
                break;
            case 'popular-clients':
                $this->activePopularClient = true;
                break;
            case 'popular-employees':
                $this->activePopularEmployee = true;
                break;
            case 'unpopular-employees':
                $this->activeUnpopularEmployee = true;
                break;
            case 'popular-providers':
                $this->activePopularProvider = true;
                break;
        }
    }

    private function calculatePopularProducts()
    {
        $this->orderDetails = inventoryOrder::query()
            ->with('inventory.product')
            ->selectRaw('sum(quantity) as inventory_quantities,inventory_id')->groupBy('inventory_id')
            ->orderByDesc('inventory_quantities')
            ->take(50)
            ->get()
            ->toArray();
    }

    private function calculatePopularClients()
    {
        $this->popularClients = Client::query()
            ->join('orders', 'orders.client_id', 'clients.id')
            ->select('clients.id', 'clients.name', 'clients.type', 'clients.avatar', 'clients.phone', 'clients.address', 'clients.worked_date')
            ->selectRaw('sum(amount_after_discount) as total_amount , count(orders.id) as orders_count')
            ->orderByDesc('total_amount')
            ->groupBy('clients.id', 'clients.name', 'clients.type', 'clients.avatar', 'clients.phone', 'clients.address', 'clients.worked_date')
            ->take(10)
            ->get();
    }

    public function CalculatePopularEmployee()
    {
        $this->popularEmployees = Employee::query()
            ->with('category')
            ->join('attendances', 'attendances.employee_id', 'employees.id')
            ->select('employees.id', 'employees.name', 'employees.type', 'employees.avatar',
                'employees.phone', 'employees.address', 'employees.worked_date', 'employees.social_status'
                , 'employees.salary')
            ->selectRaw('sum(attended) as attendance_count')
            ->orderByDesc('attendance_count')
            ->groupBy('employees.id', 'employees.name', 'employees.type', 'employees.avatar',
                'employees.phone', 'employees.address', 'employees.worked_date', 'employees.social_status'
                , 'employees.salary')
            ->take(10)
            ->get();
    }

    public function CalculateUnPopularEmployee()
    {
        $this->unpoplularEmployees = Employee::query()
            ->with('category')
            ->join('attendances', 'attendances.employee_id', 'employees.id')
            ->select('employees.id', 'employees.name', 'employees.type', 'employees.avatar',
                'employees.phone', 'employees.address', 'employees.worked_date', 'employees.social_status'
                , 'employees.salary')
            ->selectRaw('sum(attended) as attendance_count')
            ->orderBy('attendance_count')
            ->groupBy('employees.id', 'employees.name', 'employees.type', 'employees.avatar',
                'employees.phone', 'employees.address', 'employees.worked_date', 'employees.social_status'
                , 'employees.salary')
            ->take(10)
            ->get();
    }

    public function calculatePopularProviders()
    {
        $this->poplularProivders = Provider::query()
            ->withSum('purchases', 'total_amount')
            ->withSum('accessories', 'amount')
            ->take(10)
            ->orderByRaw('purchases_sum_total_amount + accessories_sum_amount desc')
            ->get();
    }

    public function render()
    {
        if (!$this->orderDetails) {
            $this->calculatePopularProducts();
        }
        if (!$this->popularClients) {
            $this->calculatePopularClients();
        }
        if (!$this->popularEmployees) {
            $this->CalculatePopularEmployee();
        }
        if (!$this->unpoplularEmployees) {
            $this->CalculateUnPopularEmployee();
        }

        if (!$this->poplularProivders) {
            $this->calculatePopularProviders();
        }

        return view('livewire.dashboard', [
            'popular_clients'     => $this->popularClients,
            'popular_employees'   => $this->popularEmployees,
            'unpopular_employees' => $this->unpoplularEmployees,
            'popular_providers'   => $this->poplularProivders,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
