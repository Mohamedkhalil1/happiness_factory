<div>
    @section('title',$pageTitle)
    <x-base.card title="{{ $pageTitle }}">
        <x-general.progress-bar/>
        {{--RIGHT ACTIONS--}}
        <x-base.grid class="mb-3">
            {{--SEARCH--}}
            <x-base.grid-col col="3">
                <x-form.input lazy="true" type="text" class="round" name="filters.search"
                              placeholder="search product name...">
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
                                <x-form.label title="Type"></x-form.label>
                                <x-base.uselect wire:model="filters.season_id">
                                    <x-select.option value="0">
                                        Select Season
                                    </x-select.option>
                                    @foreach($seasons as $season)
                                        <x-select.option
                                            value="{{ $season->id }}">{{ $season->name }}</x-select.option>
                                    @endforeach
                                </x-base.uselect>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Category"></x-form.label>
                                <x-base.uselect wire:model="filters.category_id">
                                    <x-select.option value="0">
                                        Select Category
                                    </x-select.option>
                                    @foreach($categories as $category)
                                        <x-select.option
                                            value="{{ $category->id }}">{{ $category->name }}</x-select.option>
                                    @endforeach
                                </x-base.uselect>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Color" hint="separate colors with comma"/>
                                <x-form.input lazy name="filters.color" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Size" hint="separate sizes with comma"/>
                                <x-form.input lazy name="filters.size" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Min Quantity"/>
                                <x-form.input lazy name="filters.quantity_min" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Min Amount"/>
                                <x-form.input lazy name="filters.amount_min" type="text"
                                              inputGroupText="EGP"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Max Quantity"/>
                                <x-form.input lazy name="filters.quantity_max" type="text"/>
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
                    Image
                </x-table.heading>

                <x-table.heading>
                    Name
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('price')" id="name"
                                 :direction="$sortDirection">
                    Price
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('quantity')" id="name"
                                 :direction="$sortDirection">
                    Quantity
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('color')" id="name"
                                 :direction="$sortDirection">
                    Color
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('size')" id="name"
                                 :direction="$sortDirection">
                    Size
                </x-table.heading>

                <x-table.heading>
                    Category
                </x-table.heading>

                <x-table.heading>
                    Season
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
                                        You have selected <strong>{{ $models->count() }}</strong> inventories, do you want to select all
                                        <strong>{{ $models->total() }}</strong> inventories?
                                        </span>

                                        <span wire:click="selectedAll" style="cursor: pointer;color:#0e46c5"
                                              class="ml-2">
                                            Select all
                                        </span>
                                    </div>
                                @else
                                    <span>
                                       You are currently selecting <strong>{{ $models->total() }}</strong> inventories.
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
                            @if(isset($model->image) && $model->image)
                                <a target="_blank" href="{{  getImageUrl($model->image) }}">
                                    <x-base.avatar imageUrl="{{ getImageUrl($model->image) }}"/>
                                </a>

                            @endif
                        </x-table.cell>
                        <x-table.cell>{{ $model->product->name ?? '-' }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->price) }}</x-table.cell>
                        <x-table.cell>{{ $model->quantity }}</x-table.cell>
                        <x-table.cell>{{ $model->color }}</x-table.cell>
                        <x-table.cell>{{ $model->size }}</x-table.cell>
                        <x-table.cell>{{ $model->product->category->name ?? '' }}</x-table.cell>
                        <x-table.cell>{{ $model->product->season->name ?? '' }}</x-table.cell>

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
                                No inventories found...
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
    {{--MODAL User --}}
    <x-base.modal id="model" size="lg" formAction="updateOrCreate">
        <x-slot name="title">
            Employee
        </x-slot>
        <x-slot name="content">
            <x-base.grid>
                <div class="col-md-4">
                    <x-form.label required title="Price"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text" required lazy class="round"
                                  name="inventory.price"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Quantity"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text" required lazy class="round"
                                  name="inventory.quantity"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Color"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text" required lazy class="round"
                                  name="inventory.color"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Size"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text" required lazy class="round"
                                  name="inventory.size"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Product"/>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect name="inventory.product_id" wire:model="inventory.product_id">
                        <x-select.option value="0">Select Category</x-select.option>
                        @foreach($products as $product)
                            <x-select.option value="{{ $product->id }}">{{ $product->name }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Image"/>

                    @if(isset($inventory->image) &&$inventory->image)
                        <img style="height:150px;width:150px;" src="{{ getImageUrl($inventory->image) }}"/>
                    @endif
                </div>

                <x-form.form-group col="8">
                    <x-form.upload-photo name="avatar"/>
                </x-form.form-group>


            </x-base.grid>
        </x-slot>
        <x-slot name="footer">
            <x-base.button type="submit" @click="document.getElementById('form-id').submit()">Save</x-base.button>
        </x-slot>
    </x-base.modal>
</div>


