<?php

namespace App\Http\Livewire\Employee;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Employee;
use App\Models\EmployeesCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmpIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Employees';
    public bool $showAdvancedSearch = false;
    public $categories;
    public Employee $employee;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['resfreshEmployees', '$refresh'];
    public $avatar;

    public array $filters = [
        'search'        => null,
        'social_status' => null,
        'amount_min'    => null,
        'amount_max'    => null,
        'date_start'    => null,
        'date_end'      => null,
    ];

    public function rules(): array
    {
        return [
            'employee.name'          => 'required',
            'employee.nickname'      => 'nullable|string|max:255',
            'employee.address'       => 'nullable|string|max:255',
            'employee.phone'         => 'nullable|numeric',
            'employee.social_status' => 'nullable|between:1,3',
            'employee.national_id'   => 'nullable|numeric',
            'employee.worked_date'   => 'nullable|date',
            'employee.details'       => 'nullable|string|max:64000',
            'employee.category_id'   => 'required|integer|exists:employees_categories,id',
            'employee.salary'        => 'nullable|integer|max:6400000',
            'employee.avatar'        => ['nullable'],
            'avatar'                 => ['nullable', 'image', 'max:1048'],
        ];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function exportSelected(): StreamedResponse
    {
        $csv = response()->streamDownload(function () {
            /* toCsv function you can get it in AppServiceProvider as macro*/
            echo $this->getselectedRowsQuery()->toCsv();
        }, 'Employees' . today() . '.csv');
        $this->notify('Employees have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->notify('Employees has been deleted successfully!');
    }

    public function edit($transactionId)
    {
        $this->useCachedRows();
        $this->employee = Employee::find($transactionId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->employee = new Employee();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->avatar && $this->employee->avatar = $this->avatar->store('/', 'files');
        $this->employee->save();
        $this->notify('Employee has been saved successfully!');
    }

    public function toggleAdvancedSearch(): void
    {
        $this->useCachedRows();
        $this->showAdvancedSearch = !$this->showAdvancedSearch;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    private function notify(string $message = '', string $color = '4fbe87')
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    #use cashing in the same request
    public function getRowsQueryProperty()
    {
        // searching
        $query = Employee::query()
            ->byNameNickName($this->filters['search'] ?? null);

        //filters
        $query = $query->when($this->filters['social_status'] ?? null, function ($query) {
            $query->where('social_status', $this->filters['social_status']);
        });

        $query = $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('salary', '>', $this->filters['amount_min']);
        });
        $query = $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('salary', '<=', $this->filters['amount_max']);
        });
        $query = $query->when($this->filters['date_start'] ?? null, function ($query) {
            $query->whereDate('worked_date', '>', $this->filters['date_start']);
        });
        $query = $query->when($this->filters['date_end'] ?? null, function ($query) {
            $query->where('worked_date', '<=', $this->filters['date_end']);
        });

        // sorting
        $query = $this->applySorting($query);


        return $query;
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPaginatation($this->rowsQuery);
        });
    }

    public function render()
    {
        if ($this->selectedAll) {
            $this->selectPageRows();
        }

        if (!$this->categories) {
            $this->categories = EmployeesCategory::select('id', 'name')->get();
        }

        return view('livewire.employee.emp-index', [
            'models'     => $this->rows,
            'categories' => $this->categories,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
