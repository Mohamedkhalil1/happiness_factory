<?php

namespace App\Http\Livewire\Attendance;

use App\Enums\EmployeeType;
use App\Http\Livewire\Datatable\WithBulkActions;
use App\Http\Livewire\Datatable\WithCachedRows;
use App\Http\Livewire\Datatable\WithPerPagePagination;
use App\Http\Livewire\Datatable\WithSorting;
use App\Models\Attendance;
use App\Models\Employee;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttIndex extends Component
{
    use WithFileUploads, WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public string $pageTitle = 'Attendances';
    public bool $showAdvancedSearch = false;
    public $attendances;
    public Attendance $attendance;
    public $employees;
    protected $queryString = ['sortField', 'sortDirection', 'filters'];
    protected $listeners = ['resfreshAttendances', '$refresh'];

    public array $filters = [
        'search'     => null,
        'status'     => null,
        'date_start' => null,
        'date_end'   => null,
    ];

    public function rules(): array
    {
        return [
            'attendance.date'        => 'required',
            'attendance.employee_id' => 'required|integer|exists:employees,id',
            'attendance.attended'    => 'required|between:1,2',
            'attendance.note'        => 'nullable|string|max:64000',
        ];
    }

    public function exportSelected(): StreamedResponse
    {
        $csv = response()->streamDownload(function () {
            /* toCsv function you can get it in AppServiceProvider as macro*/
            echo $this->getselectedRowsQuery()->toCsv();
        }, 'Attendances' . today() . '.csv');
        $this->notify('Attendances have been downloaded successfully!');
        return $csv;
    }

    public function deleteSelected(): void
    {
        $this->getselectedRowsQuery()->delete();
        $this->selectedPage = false;
        $this->selectedAll = false;
        $this->resetPage();
        $this->notify('Attendances has been deleted successfully!');
    }

    public function edit($transactionId)
    {
        $this->useCachedRows();
        $this->attendance = Attendance::find($transactionId);
    }

    public function create()
    {
        $this->useCachedRows();
        $this->attendance = new Attendance();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->attendance->save();
        $this->notify('Attendance has been saved successfully!');
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
        // searching
        $query = Attendance::with('employee');

        $query->when($this->filters['search'] ?? null, function ($query) {
            $query->whereHas('employee', function ($query) {
                return $query->byNameNickName($this->filters['search']);
            });
        });

        //filters
        $query = $query->when(isset($this->filters['status']) , function ($query) {
            $query->where('attended', $this->filters['status']);
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
            $this->employees = Employee::where('type', EmployeeType::FULL_TIME)->get();
        }

        return view('livewire.attendance.att-index', [
            'models'    => $this->rows,
            'employees' => $this->employees,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
