<div>
    @section('title',$pageTitle)
    <x-form.form action="showInventories">
        <x-base.card title="{{$pageTitle}}">
            <x-form.form-group col="12">
                <x-form.label required title="Name"/>
                <x-form.input type="text" lazy="true" name="product.name"/>
            </x-form.form-group>

            <x-form.form-group col="6">
                <x-form.label required title="Category"/>
                <x-base.uselect name="product.category_id" wire:model="product.category_id">
                    <x-select.option value="0">Select Categories</x-select.option>
                    @foreach($categories as $category)
                        <x-select.option value="{{ $category->id }}">{{ $category->name }}</x-select.option>
                    @endforeach
                </x-base.uselect>
            </x-form.form-group>

            <x-form.form-group col="6">
                <x-form.label required title="Season"/>
                <x-base.uselect name="product.season_id" wire:model="product.season_id">
                    <x-select.option value="0">Select Season</x-select.option>
                    @foreach($seasons as $season)
                        <x-select.option value="{{ $season->id }}">{{ $season->name }}</x-select.option>
                    @endforeach
                </x-base.uselect>
            </x-form.form-group>


            <x-form.form-group col="12">
                <x-form.label title="Description"/>
                <x-form.textarea lazy="true" name="product.description" title="Description"/>
            </x-form.form-group>

            <x-form.form-group required col="6">
                <x-form.label required title="Color"/>
                <x-form.input required name="color" type="text"/>
            </x-form.form-group>

            <x-form.form-group required col="6">
                <x-form.label required title="Size"/>
                <x-form.input required name="size" type="text"/>
            </x-form.form-group>
            @if($color && $size)
                <div class="col-sm-12 d-flex justify-content-end">
                    <x-base.button type="button" wire:click="showInventories" class="primary me-1 mb-1">
                        Make Inventories
                    </x-base.button>
                </div>
            @endif
        </x-base.card>
        @if($showCloseProductsCards)
            <x-base.grid>
                @foreach($inventories as $inventory)
                    <x-base.grid-col>
                        <x-base.card title="{{ $inventory }}">
                            <x-card.content>
                                <x-form.form-group col="12">
                                    @if(!isset($images[$inventory]))
                                        <input type="file" wire:model="images.{{ $inventory }}"/>
                                    @endif
                                    <img class="card-img-top img-fluid"
                                         style="height:100px;width: auto;"
                                         src="{{ isset($images[$inventory]) ? $images[$inventory]->temporaryUrl() : '' }}"/>
                                </x-form.form-group>
                                <x-card.body>
                                    {{--                                            <x-card.title>--}}
                                    {{--                                                {{ $inventory }}--}}
                                    {{--                                            </x-card.title>--}}
                                    <x-card.text>
                                        <x-form.form-group required col="12">
                                            <x-form.label required title="Price"/>
                                            <x-form.input required lazy="true" name="prices.{{ $inventory }}"
                                                          type="text"/>
                                        </x-form.form-group>
                                        <x-form.form-group required col="12">
                                            <x-form.label required title="Quantity"/>
                                            <x-form.input required lazy="true"
                                                          name="quantities.{{ $inventory }}"
                                                          type="number"/>
                                        </x-form.form-group>
                                    </x-card.text>
                                </x-card.body>
                            </x-card.content>
                        </x-base.card>
                    </x-base.grid-col>
                @endforeach
            </x-base.grid>
        @endif

    </x-form.form>


</div>
