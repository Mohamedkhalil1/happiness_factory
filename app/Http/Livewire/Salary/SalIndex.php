<?php

namespace App\Http\Livewire\Salary;

use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Employee;
use App\Models\Salary;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Salaries';
    public bool $showAdvancedSearch = false;
    public $salaries;
    public $employees;
    public Salary $salary;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['resfreshSalaries', '$refresh'];

    public array $filters = [
        'search'     => null,
        'with'       => null,
        'amount_min' => null,
        'amount_max' => null,
        'date_start' => null,
        'date_end'   => null,
    ];

    public function rules(): array
    {
        return [
            'salary.amount'      => 'required|numeric|max:99999999',
            'salary.with'        => 'nullable|integer|between:1,3',
            'salary.with_value'  => 'nullable|numeric|max:99999999',
            'salary.date'        => 'required|date',
            'salary.employee_id' => 'required|integer|exists:employees,id',
        ];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function updatedSalaryEmployeeId($value)
    {
        $this->salary->amount = $this->employees->find($value)->salary ?? 0;
    }

    public function exportSelected(): StreamedResponse
    {
        $csv = response()->streamDownload(function () {
            /* toCsv function you can get it in AppServiceProvider as macro*/
            echo $this->getselectedRowsQuery()->toCsv();
        }, 'Employees' . today() . '.csv');
        $this->notify('Transaction have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->notify('Transaction has been deleted successfully!');
    }

    public function edit($salaryId)
    {
        $this->useCachedRows();
        $this->salary = Salary::find($salaryId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->salary = new Salary();
    }

    public function updateOrCreate()
    {
        $this->validate();
        if (!$this->salary->with) {
            $this->salary->with_value = 0;
            $this->salary->with = 1;
        }
        $this->salary->save();
        $this->notify('Salary has been saved successfully!');
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

    private function notify(string $message = '', string $color = '#4fbe87')
    {
        $this->dispatchBrowserEvent('notify', ['message' => $message, 'color' => $color]);
    }

    #use cashing in the same request
    public function getRowsQueryProperty()
    {
        $query = Salary::query()->with('employee');
        // searching
        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->whereHas('employee', function ($query) {
                return $query->byNameNickName($this->filters['search']);
            });
        });
        //filters
        $query = $query->when($this->filters['with'] ?? null, function ($query) {
            $query->where('with', $this->filters['with']);
        });

        $query = $query->when($this->filters['amount_min'] ?? null, function ($query) {
            $query->where('amount', '>', $this->filters['amount_min']);
        });

        $query = $query->when($this->filters['amount_max'] ?? null, function ($query) {
            $query->where('amount', '<=', $this->filters['amount_max']);
        });

        $query = $query->when($this->filters['date_start'] ?? null, function ($query) {
            $query->whereDate('date', '>', $this->filters['date_start']);
        });

        $query = $query->when($this->filters['date_end'] ?? null, function ($query) {
            $query->where('date', '<=', $this->filters['date_end']);
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

        if (!$this->employees) {
            $this->employees = Employee::select('id', 'name', 'nickname', 'salary')->get();
        }

        return view('livewire.salary.sal-index', [
            'models'    => $this->rows,
            'employees' => $this->employees,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
