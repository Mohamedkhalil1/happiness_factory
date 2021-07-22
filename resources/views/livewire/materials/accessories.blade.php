<div>
    @section('title',$pageTitle)
    <x-base.card title="{{ $pageTitle }}">
        <x-general.progress-bar/>
        {{--RIGHT ACTIONS--}}
        <x-base.grid class="mb-3">
            {{--SEARCH--}}
            <x-base.grid-col col="3">
                <x-form.input lazy="true" type="text" class="round" name="filters.search"
                              placeholder="search accessory name...">
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
                                <x-form.label title="Provider"></x-form.label>
                                <x-base.uselect wire:model="filters.provider_id">
                                    <x-select.option value="0">
                                        Select Provider
                                    </x-select.option>
                                    @foreach($providers as $provider)
                                        <x-select.option
                                            value="{{ $provider->id }}">{{ $provider->name }}</x-select.option>
                                    @endforeach
                                </x-base.uselect>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Height" hint="separate heights with comma"/>
                                <x-form.input lazy name="filters.height" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Weight" hint="separate weights with comma"/>
                                <x-form.input lazy name="filters.weight" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Min Quantity"/>
                                <x-form.input lazy name="filters.quantity_min" type="number"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Min Amount"/>
                                <x-form.input lazy name="filters.amount_min" type="number"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Max Quantity"/>
                                <x-form.input lazy name="filters.quantity_max" type="number"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Max Amount"/>
                                <x-form.input lazy name="filters.amount_max" type="number"/>
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

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('name')" id="name"
                                 :direction="$sortDirection">
                    Name
                </x-table.heading>

                <x-table.heading>
                    Provider
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('height')" id="height"
                                 :direction="$sortDirection">
                    Height
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('weight')" id="weight"
                                 :direction="$sortDirection">
                    Weight
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('quantity')" id="quantity"
                                 :direction="$sortDirection">
                    Quantity
                </x-table.heading>


                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('amount')" id="amount"
                                 :direction="$sortDirection">
                    Amount
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
                                        You have selected <strong>{{ $models->count() }}</strong> ores, do you want to select all
                                        <strong>{{ $models->total() }}</strong> ores?
                                        </span>

                                        <span wire:click="selectedAll" style="cursor: pointer;color:#0e46c5"
                                              class="ml-2">
                                            Select all
                                        </span>
                                    </div>
                                @else
                                    <span>
                                       You are currently selecting <strong>{{ $models->total() }}</strong> ores.
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
                        <x-table.cell>{{ $model->name }}</x-table.cell>
                        <x-table.cell>{{ $model->provider->name ?? '' }}</x-table.cell>
                        <x-table.cell>{{ $model->height }}</x-table.cell>
                        <x-table.cell>{{ $model->weight }}</x-table.cell>
                        <x-table.cell>{{ $model->quantity }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->amount) }}</x-table.cell>

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
                                No accessories found...
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
                    <x-form.label required title="Provider"/>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect wire:model="accessory.provider_id">
                        <x-select.option value="0">
                            Select Provider
                        </x-select.option>
                        @foreach($providers as $provider)
                            <x-select.option
                                value="{{ $provider->id }}">{{ $provider->name }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Name"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text" required lazy class="round"
                                  name="accessory.name"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Quantity"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="number" lazy class="round" name="accessory.quantity"/>
                </x-form.form-group>


                <div class="col-md-4">
                    <x-form.label  title="Height"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text"  lazy class="round"
                                  name="accessory.height"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label  title="Weight"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text"  lazy class="round"
                                  name="accessory.weight"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Amount"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="number" required lazy class="round"
                                  name="accessory.amount"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Note"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.textarea wire:model="accessory.note" title="note"/>
                </x-form.form-group>

            </x-base.grid>
        </x-slot>
        <x-slot name="footer">
            <x-base.button type="submit" @click="document.getElementById('form-id').submit()">Save</x-base.button>
        </x-slot>
    </x-base.modal>
</div>


