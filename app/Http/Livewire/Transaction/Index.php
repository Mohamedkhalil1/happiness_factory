<?php

namespace App\Http\Livewire\Transaction;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $pageTitle = 'Transactions';
    public string $search = '';
    public string $sortField = 'date';
    public string $sortDirection = 'asc';
    public bool $editing = false;
    public Transaction $transaction;
    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function rules(): array
    {
        return [
            'transaction.title'  => 'required',
            'transaction.amount' => 'required|numeric|max:640000',
            'transaction.date'   => 'required|date',
            'transaction.status' => 'required|between:1,3',
        ];
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function edit($transactionId)
    {
        $this->transaction = Transaction::find($transactionId);
    }

    public function create()
    {
        $this->transaction = new Transaction();
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->transaction->save();
        $this->dispatchBrowserEvent('notify', ['message' => 'Transaction has been saved successfully!', 'color' => '#4fbe87']);
    }

    public function render()
    {
        return view('livewire.transaction.index', [
            /* search function you can get it in AppServiceProvider as macro*/
            'transactions' => Transaction::search('title', $this->search)
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(),
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
