<div>
    @section('title',$pageTitle)
    <x-general.progress-bar/>
    {{--    PRODUCTS--}}
    <x-base.grid>
        @if($showInventories)
            {{--ADVANCED SEARCH--}}
            <x-base.grid-col col="12" style="margin-top: 7px">
                <x-base.button class="outline-primary mb-3" wire:click="toggleAdvancedSearch()">
                    @if($showAdvancedSearch) Hide @endif Advanced Search...
                </x-base.button>
                <div>
                    @if($showAdvancedSearch)
                        <div>
                            <x-base.card style="background: #17161621" title="Filters">

                                <x-base.grid>
                                    <x-form.form-group col="6">
                                        <x-form.label title="Product Name"></x-form.label>
                                        <x-form.input lazy="true" type="text" class="round" name="filters.search"
                                                      placeholder="search product name...">
                                        </x-form.input>
                                    </x-form.form-group>

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
            </x-base.grid-col>
            @foreach($inventories as $inventory)
                <x-base.grid-col>
                    <x-base.card title="{{ $inventory->product->name }}">
                        <x-base.card-with-image>
                            <x-card.content>
                                <div>
                                    @if(isset($orderInventories[$inventory->id]))
                                        <x-base.button wire:click="removeItem('{{$inventory->id}}')"
                                                       style="float:right;"
                                                       class="danger me-1 mb-1">
                                            <i class="bi bi-cart-dash"></i>
                                        </x-base.button>
                                    @endif
                                </div>
                                <img class="card-img-top img-fluid" src="{{ getImageUrl($inventory->image) }}"
                                     alt="Card image cap" style="height: 200px;width:200px"/>
                                <x-card.body>
                                    <x-card.text>
                                        <div class="alert alert-light-info color-info">
                                            <i class="bi bi-star"></i>
                                            Color : {{ $inventory->color }}
                                        </div>
                                        <div class="alert alert-light-secondary color-secondary">
                                            <i class="bi bi-lightning-fill"></i>
                                            Size : {{ $inventory->size }}
                                        </div>
                                        <div class="alert alert-light-info color-info">
                                            <i class="bi bi-cash"></i>
                                            Price : {{ formatMoney($inventory->price) }}
                                        </div>
                                    </x-card.text>

                                    <x-card.text>
                                        <x-form.label title="Quantity"/>
                                        <p class="text-muted text-sm">
                                            (max quantity in store: {{ $inventory->quantity }})
                                        </p>
                                        <x-form.input class="mb-3"
                                                      lazy
                                                      name="quantities.{{ $inventory->id }}"
                                                      type="number"/>
                                    </x-card.text>
                                    <x-card.text>
                                        <div class="alert alert-light-secondary color-secondary">
                                            <i class="bi bi-lightbulb-fill"></i>
                                            Total
                                            : {{ formatMoney( $inventory->price * ($quantities[$inventory->id] ?? 0))  }}
                                        </div>
                                    </x-card.text>

                                    <x-base.button wire:click="addInventoryToOrder('{{$inventory->id}}')"
                                                   style="float:right;">
                                        Add To <i class="bi bi-cart-plus"></i>
                                    </x-base.button>
                                </x-card.body>
                            </x-card.content>
                        </x-base.card-with-image>
                    </x-base.card>
                </x-base.grid-col>
            @endforeach
            {{ $inventories->links() }}
            <x-base.grid-col col="12">
                <x-base.button wire:click="showOrHideInventories('{{false}}')" class="danger mb-2 mt-3"
                               style="float:right">
                    Hide Products
                </x-base.button>
            </x-base.grid-col>
        @else
            <x-base.grid-col col="12">
                <x-base.button wire:click="showOrHideInventories('{{true}}')" class="success mb-2" style="float:right">
                    Show Products
                </x-base.button>
            </x-base.grid-col>
        @endif
    </x-base.grid>
    {{--    ORDER--}}
    <x-base.card title="{{$pageTitle}}">
        <x-form.form action="addOrder">
            <x-form.form-group col="4">
                <x-form.label title="Total Amount"/>
                <x-form.input readonly name="order.amount_before_discount" type="number"/>
            </x-form.form-group>

            <x-form.form-group col="4">
                <x-form.label title="Discount %"/>
                <x-form.input lazy="true" required name="order.discount" type="number"/>
            </x-form.form-group>

            <x-form.form-group col="4">
                <x-form.label title="After Discount"/>
                <x-form.input readonly name="order.amount_after_discount" type="number"/>
            </x-form.form-group>

            <x-form.form-group col="6">
                <x-form.label required title="Date"/>
                <x-form.date-time required id="date" name="order.date" type="text"/>
            </x-form.form-group>

            <x-form.form-group col="6">
                <x-form.label title="Address"/>
                <x-form.input name="order.address" type="text"/>
            </x-form.form-group>

            <x-form.form-group col="12">
                <x-form.label required title="Client"></x-form.label>
                <x-base.uselect required wire:model="order.client_id" name="order.client_id">
                    <x-select.option value="0">
                        Select Client
                    </x-select.option>
                    @foreach($clients as $client)
                        <x-select.option
                            value="{{ $client->id }}">{{ $client->name }}</x-select.option>
                    @endforeach
                </x-base.uselect>

                <div class="col-sm-12 d-flex justify-content-end">
                    <x-base.button type="submit" class="primary me-1 mt-3 mb-1">
                        Save
                    </x-base.button>
                </div>

            </x-form.form-group>

        </x-form.form>
    </x-base.card>


</div>
