<div>
    @section('title',$pageTitle)
    <x-base.card title="{{ $pageTitle }}">
        <x-general.progress-bar/>
        {{--RIGHT ACTIONS--}}
        <x-base.grid class="mb-3">
            {{--SEARCH--}}
            <x-base.grid-col col="3">
                <x-form.input lazy="true" type="text" class="round" name="filters.search"
                              placeholder="search client name...">
                </x-form.input>
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
                <x-base.button-link href="{{ route('orders.create') }}" style="float: right">
                    <x-icons.add/>
                    New
                </x-base.button-link>
                {{--BulkACTIONS--}}
                <x-base.dropdown color="secondary" style="float: right" label="Bulk Actions" class="mr-2 ml-2">
                    <x-dropdown.item>
                        <x-icons.download class="text-muted"/>
                        <span style="cursor: pointer" class="text-muted"
                              wire:click="exportSelected">Export</span>
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
                                <x-form.label :required="false" title="Status"></x-form.label>
                                <x-base.uselect wire:model="filters.status">
                                    <x-select.option value="0">
                                        Status
                                    </x-select.option>
                                    @foreach(\App\Enums\OrderStatus::keyValue() as $status)
                                        <x-select.option
                                            value="{{ $status['id'] }}">{{ $status['name'] }}</x-select.option>
                                    @endforeach
                                </x-base.uselect>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Start Date"/>
                                <x-form.date-time id="date_start" name="filters.date_start" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Min Amount"/>
                                <x-form.input lazy name="filters.amount_min" type="text"
                                              inputGroupText="EGP"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="End Date"/>
                                <x-form.date-time id="date_end" name="filters.date_end" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Max Amount"/>
                                <x-form.input lazy name="filters.amount_max" type="text"
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

                <x-table.heading>
                    Avatar
                </x-table.heading>

                <x-table.heading>
                    Name
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('date')" id="date"
                                 :direction="$sortDirection">
                    Date
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('status')" id="status"
                                 :direction="$sortDirection">
                    Status
                </x-table.heading>


                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('address')" id="address"
                                 :direction="$sortDirection">
                    address
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('amount_before_discount')"
                                 id="total_amount"
                                 :direction="$sortDirection">
                    Total Amount
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('discount')" id="discount"
                                 :direction="$sortDirection">
                    Discount
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('amount_after_discount')"
                                 id="amount"
                                 :direction="$sortDirection">
                    Amount
                </x-table.heading>

                <x-table.heading>
                    Paid Amount
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('remain')" id="salary"
                                 :direction="$sortDirection">
                    Remain
                </x-table.heading>

                <x-table.heading>
                    Actions
                </x-table.heading>
            </x-slot>

            <x-slot name="body">

                @if($selectedPage)
                    <x-table.row>
                        <x-table.cell class="text-black" style="background: #4b455042" colspan="11">
                            <div class="text-black">
                                @unless($selectedAll)
                                    <div>
                                        <span>
                                        You have selected <strong>{{ $models->count() }}</strong> orders, do you want to select all
                                        <strong>{{ $models->total() }}</strong> orders?
                                        </span>

                                        <span wire:click="selectedAll" style="cursor: pointer;color:#0e46c5"
                                              class="ml-2">
                                            Select all
                                        </span>
                                    </div>
                                @else
                                    <span>
                                       You are currently selecting <strong>{{ $models->total() }}</strong> orders.
                                   </span>
                                @endunless
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endif

                @forelse($models as $model)
                    <x-table.row wire:key="{{ $model->id }}">
                        <x-table.cell>
                            <x-base.checkbox wire:model="selected" value="{{ $model->id }}"/>
                        </x-table.cell>
                        <x-table.cell>
                            @if(isset($model->client->avatar) && $model->client->avatar)
                                <x-base.avatar imageUrl="{{ getImageUrl($model->client->avatar) }}"/>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            {{ $model->client->name }}
                        </x-table.cell>
                        <x-table.cell>{{ formatDate($model->date) }}</x-table.cell>
                        <x-table.cell>
                            <x-base.badge type="{{\App\Enums\OrderStatus::getColor($model->status)  }}">
                                {{ \App\Enums\OrderStatus::name($model->status) }}
                            </x-base.badge>
                        </x-table.cell>
                        <x-table.cell>{{ $model->address }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->amount_before_discount) }}</x-table.cell>
                        <x-table.cell>{{ $model->discount }}%</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->amount_after_discount) }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->paid_amount) }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->remain) }}</x-table.cell>

                        <x-table.cell>
                            <a href="javascript:" title="edit" wire:click="edit({{$model->id}})" style="cursor: pointer"
                               data-bs-toggle="modal" data-bs-target="#model">
                                <x-icons.edit/>
                            </a>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="11">
                            <div class="text-center text-muted text-uppercase">
                                No orders found...
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-base.table>
        <div>
            {{ $models->links() }}
        </div>
    </x-base.card>
</div>


