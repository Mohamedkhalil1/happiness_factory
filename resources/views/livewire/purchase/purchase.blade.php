<div>
    @section('title',$pageTitle)
    <x-base.card title="{{ $pageTitle }}">
        <x-general.progress-bar/>
        {{--RIGHT ACTIONS--}}
        <x-base.grid class="mb-3">
            {{--SEARCH--}}
            <x-base.grid-col col="4">
                <x-form.input lazy="true" type="text" class="round" name="filters.search"
                              placeholder="search provider or material name...">
                </x-form.input>
            </x-base.grid-col>
            {{--ADVANCED SEARCH--}}
            <x-base.grid-col col="3" style="margin-top: 7px">
                <span wire:click="toggleAdvancedSearch()" style="cursor: pointer">
                   @if($showAdvancedSearch) Hide @endif Advanced Search...
               </span>
            </x-base.grid-col>

            {{--LEFT ACTIONS--}}
            <x-base.grid-col style="padding-left:3px" col="5">
                {{--NEW--}}
                <x-base.button wire:click="create" style="float: right" data-bs-toggle="modal"
                               data-bs-target="#model">
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
                                <x-form.label title="Min Quantity"/>
                                <x-form.input lazy name="filters.quantity_min" type="text"
                                              />
                            </x-form.form-group>
                            <x-form.form-group col="6">
                                <x-form.label title="Max Quantity"/>
                                <x-form.input lazy name="filters.quantity_max" type="text"
                                              />
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
                    Material
                </x-table.heading>

                <x-table.heading>
                    Provider
                </x-table.heading>

                <x-table.heading>
                    Date
                </x-table.heading>

                <x-table.heading>
                    Status
                </x-table.heading>


                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('date')" id="date"
                                 :direction="$sortDirection">
                    Amount
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('quantity')" id="status"
                                 :direction="$sortDirection">
                    Quantity
                </x-table.heading>


                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('total_amount')"
                                 id="total_amount"
                                 :direction="$sortDirection">
                    Total Amount
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('paid_amount')"
                                 id="total_amount"
                                 :direction="$sortDirection">
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
                                        You have selected <strong>{{ $models->count() }}</strong> purchases, do you want to select all
                                        <strong>{{ $models->total() }}</strong> purchases?
                                        </span>

                                        <span wire:click="selectedAll" style="cursor: pointer;color:#0e46c5"
                                              class="ml-2">
                                            Select all
                                        </span>
                                    </div>
                                @else
                                    <span>
                                       You are currently selecting <strong>{{ $models->total() }}</strong> purchases.
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

                        <x-table.cell>{{ $model->ore->material->name ?? '' }}
                            <div class="text-muted">({{ $model->ore->companite() }})</div>
                        </x-table.cell>

                        <x-table.cell>{{ $model->provider->name ?? '' }}</x-table.cell>


                        <x-table.cell>{{ formatDate($model->date) }}</x-table.cell>

                        <x-table.cell>
                            <x-base.badge type="{{\App\Enums\OrderStatus::getColor($model->status)  }}">
                                {{ \App\Enums\OrderStatus::name($model->status) }}
                            </x-base.badge>
                        </x-table.cell>
                        <x-table.cell>{{ formatMoney($model->amount) }}</x-table.cell>
                        <x-table.cell>{{ $model->quantity }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->total_amount) }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->paid_amount) }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->remain) }}</x-table.cell>

                        <x-table.cell>
                            @if($model->status != \App\Enums\OrderStatus::DONE)
                                @if($model->status != \App\Enums\OrderStatus::DONE)
                                    <x-icons.money class="text-muted"/>
                                @endif

                                @if($model->status == \App\Enums\OrderStatus::PENDING)

                                    <a href="javascript:" title="edit" wire:click="edit({{$model->id}})" style="cursor: pointer"
                                       data-bs-toggle="modal" data-bs-target="#model">
                                        <x-icons.edit/>
                                    </a>

                                    <a href="javascript:" title="delete" style="cursor: pointer" class="text-muted"
                                       onclick="confirm('are you sure ?') || event.stopImmediatePropagation()"
                                       wire:click="delete({{$model->id}})">
                                        <x-icons.delete class="text-muted"/>
                                    </a>
                                @endif

                            @else
                                <p class="text-muted">NO ACTIONS</p>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="11">
                            <div class="text-center text-muted text-uppercase">
                                No purchases found...
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

    <x-base.modal id="model" size="lg" formAction="updateOrCreate">
        <x-slot name="title">
            Employee
        </x-slot>
        <x-slot name="content">
            <x-base.grid>

                <div class="col-md-4">
                    <x-form.label required title="Date"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.date-time id="date" name="purchase.date" type="text"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Materials"/>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect name="material_id" wire:model="material_id">
                        <x-select.option value="0">Select Material</x-select.option>
                        @foreach($materials as $material)
                            <x-select.option value="{{ $material->id }}">{{ $material->name }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Ores"/>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect  name="purchase.ore_id" wire:model="purchase.ore_id" :disabled="$edit">
                        <x-select.option  value="0">Select Ore</x-select.option>
                        @foreach($ores as $ore)
                            <x-select.option disbaled value="{{ $ore->id }}">{{ $ore->companite() }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Providers"/>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect name="purchase.provider_id" wire:model="purchase.provider_id">
                        <x-select.option value="0">Select Provider</x-select.option>
                        @foreach($providers as $provider)
                            <x-select.option value="{{ $provider->id }}">{{ $provider->name }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Price(for one piece)"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="number" required lazy class="round"
                                  name="purchase.amount" inputGroupText="EGP"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Quantity"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="number" required lazy class="round"
                                  name="purchase.quantity"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Amount"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="number" required lazy class="round"
                                  readonly name="purchase.total_amount" inputGroupText="EGP"></x-form.input>
                </x-form.form-group>

            </x-base.grid>
        </x-slot>
        <x-slot name="footer">
            <x-base.button type="submit" @click="document.getElementById('form-id').submit()">Save</x-base.button>
        </x-slot>
    </x-base.modal>
</div>


