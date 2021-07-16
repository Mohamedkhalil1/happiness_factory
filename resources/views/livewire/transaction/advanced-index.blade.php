<div>
    @push('head')
        <style>
            .loading-bar {
                position: fixed;
                height: 2px;
                left: 0;
                top: 0;
                width: 100%;
                z-index: 10000;
            }
        </style>
    @endpush
    @section('title',$pageTitle)
    <x-base.card title="{{ $pageTitle }}">
        {{--PROGRESS BAR--}}
        <x-base.grid>
            <x-base.grid-col col="12">
                <div wire:loading.flex class="progress progress-primary loading-bar">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         style="width: 100%"
                         aria-valuenow="100"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>
            </x-base.grid-col>
        </x-base.grid>

        {{--RIGHT ACTIONS--}}
        <x-base.grid class="mb-3">
            {{--SEARCH--}}
            <x-base.grid-col col="3">
                <x-form.input lazy="true" type="text" class="round" name="filters.search"
                              placeholder="Search Transactions..."/>
            </x-base.grid-col>
            {{--ADVANCED SEARCH--}}
            <x-base.grid-col col="3" style="margin-top: 7px">
                <span wire:click="toggleAdvancedSearch()" style="cursor: pointer">
                   @if($showAdvancedSearch) Hide @endif Advanced Search...
               </span>
            </x-base.grid-col>

            {{--LEFT ACTIONS--}}
            <x-base.grid-col style="padding-left:3px" col="6">
                {{--NEW--}}
                <x-base.button wire:click="create" style="float: right" data-bs-toggle="modal"
                               data-bs-target="#transaction">
                    <x-icons.add/>
                    New
                </x-base.button>
                {{--BulkACTIONS--}}
                <x-base.dropdown color="secondary" style="float: right" label="Bulk Actions" class="mr-2 ml-2">
                    <x-dropdown.item>
                        <x-icons.download class="text-muted"/>
                        <span style="cursor: pointer" class="text-muted"
                              wire:click="exportSelected">Export</span>
                    </x-dropdown.item>
                    <x-dropdown.item>
                        <x-icons.delete class="text-muted"/>
                        <span style="cursor: pointer" class="text-muted"
                              onclick="confirm('are you sure ?') || event.stopImmediatePropagation()"
                              wire:click="deleteSelected">Delete</span>
                    </x-dropdown.item>
                </x-base.dropdown>
                {{--PERPAGE PAGINATION--}}

                <x-base.uselect style="float: right; padding: 6px; width:120px" wire:model="perPage">
                    <x-select.option value="10">10 items</x-select.option>
                    <x-select.option value="25">25 items</x-select.option>
                    <x-select.option value="50">50 items</x-select.option>
                </x-base.uselect>

            </x-base.grid-col>
        </x-base.grid>
        <div>
            @if($showAdvancedSearch)
                <div>
                    <x-base.card style="background: #17161621" title="Filters">
                        <x-base.grid>
                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Status"/>
                                <x-form.select
                                    wire:model="filters.status"
                                    name="transaction.status"
                                    :options="\App\Enums\Status::keyValue()"
                                    selectTitle="Select Status"
                                />
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Start Date"/>
                                <x-form.date-time id="date_start" name="filters.date_start" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Min Amount"/>
                                <x-form.input :required="false" lazy="true" name="filters.amount_min" type="text"
                                              inputGroupText="EGP"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="End Date"/>
                                <x-form.date-time id="date_end" name="filters.date_end" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Max Amount"/>
                                <x-form.input :required="false" lazy="true" name="filters.amount_max" type="text"
                                              inputGroupText="EGP"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <span wire:click="resetFilters" class="text-black"
                                      style="float:right;margin-top:29px;cursor: pointer">
                                    Reset Filters
                                </span>
                            </x-form.form-group>
                        </x-base.grid>
                    </x-base.card>
                </div>
            @endif
        </div>

        {{--Table--}}
        <x-base.table class="border">
            <x-slot name="head">
                <x-table.heading>
                    <x-base.checkbox wire:model="selectedPage"/>
                </x-table.heading>

                <x-table.heading :sortable="true" :direction="$sortDirection" wire:click="sortBy('id')" id="id">
                    ID
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('title')" id="title"
                                 :direction="$sortDirection">
                    Title
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('amount')" id="amount"
                                 :direction="$sortDirection">
                    Amount
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('status')" id="status"
                                 :direction="$sortDirection">
                    Status
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('date')" id="date"
                                 :direction="$sortDirection">
                    Date
                </x-table.heading>

                <x-table.heading :sortable="false">
                    Actions
                </x-table.heading>
            </x-slot>

            <x-slot name="body">

                @if($selectedPage)
                    <x-table.row>
                        <x-table.cell class="text-black" style="background: #4b455042" colspan="6">
                            <div class="text-black">
                                @unless($selectedAll)
                                    <div>
                                        <span>
                                        You have selected <strong>{{ $transactions->count() }}</strong> transactions, do you want to select all
                                        <strong>{{ $transactions->total() }}</strong> transactions?
                                        </span>

                                        <span wire:click="selectedAll" style="cursor: pointer;color:#0e46c5"
                                              class="ml-2">
                                            Select all
                                        </span>
                                    </div>
                                @else
                                    <span>
                                       You are currently selecting <strong>{{ $transactions->total() }}</strong> transactions.
                                   </span>
                                @endunless
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endif

                @forelse($transactions as $transaction)
                    <x-table.row wire:key="{{ $transaction->id }}">
                        <x-table.cell>
                            <x-base.checkbox wire:model="selected" value="{{ $transaction->id }}"/>
                        </x-table.cell>

                        <x-table.cell>
                            {{ $transaction->id }}
                        </x-table.cell>

                        <x-table.cell>
                            <x-icons.money/>
                            {{ $transaction->title }}
                        </x-table.cell>

                        <x-table.cell>{{ formatMoney($transaction->amount) }} </x-table.cell>

                        <x-table.cell>
                            <x-base.badge type="{{\App\Enums\Status::getColor($transaction->status)  }}">
                                {{ \App\Enums\Status::name($transaction->status) }}
                            </x-base.badge>
                        </x-table.cell>

                        <x-table.cell>{{ formatDate($transaction->date) }}</x-table.cell>
                        <x-table.cell>
                              <span wire:click="edit({{$transaction->id}})" style="cursor: pointer"
                                    data-bs-toggle="modal" data-bs-target="#transaction">
                                  <x-icons.edit/>
                              </span>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="6">
                            <div class="text-center text-muted text-uppercase">
                                No transactions found...
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-base.table>
        <div>
            <nav aria-label="Page navigation example">
                <ul class="pagination pagination-primary">
                    {{ $transactions->links() }}
                </ul>
            </nav>
        </div>
    </x-base.card>
    {{--MODAL--}}
    <x-base.modal id="transaction" size="lg" formAction="updateOrCreate">
        <x-slot name="title">
            Transaction
        </x-slot>
        <x-slot name="content">
            <x-base.grid>
                <div class="col-md-4">
                    <x-form.label :required="false" title="Title"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text" :required="false" lazy="true" class="round" name="transaction.title"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Amount" :required="false"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.input :required="false" lazy="true" name="transaction.amount" type="amount"
                                  inputGroupText="EGP"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label :required="false" title="Date"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.date-time id="date" name="transaction.date" wire:model="transaction.date" type="text"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label :required="false" title="Status"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.select
                        value="{{ $transaction->status ?? '' }}"
                        name="transaction.status"
                        :options="\App\Enums\Status::keyValue()"
                        selectTitle="Select Status"
                        wire:model="transaction.status"
                    />
                </x-form.form-group>
            </x-base.grid>
        </x-slot>
        <x-slot name="footer">
            <x-base.button type="submit" @click="document.getElementById('form-id').submit()">Save</x-base.button>
        </x-slot>

    </x-base.modal>

</div>


