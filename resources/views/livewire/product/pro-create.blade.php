<div>
    @section('title',$pageTitle)
    <x-form.form action="createInventories">
        <x-base.card title="{{$pageTitle}}">
            <x-form.form-group col="12">
                <x-form.label required title="Name"/>
                <x-form.input type="text" lazy="true" name="product.name"/>
            </x-form.form-group>

            <x-form.form-group col="6">
                <x-form.label required title="Category"/>
                <x-base.uselect name="product.category_id" wire:model="product.category_id">
                    <x-select.option value="0">Select Category</x-select.option>
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
                                <x-base.button wire:click="removeItem('{{$inventory}}')" style="float:right"
                                               class="danger me-1 mb-1">
                                    <x-icons.cut/>
                                </x-base.button>
                                <x-form.form-group col="10">
                                    <input type="file" wire:model="images.{{ $inventory }}"/>
                                    <span class="text-danger" style="display: inline-block">
                                            @error("images") {{ $message }} @enderror
                                        </span>
                                    <span class="text-danger" style="display: inline-block">
                                           @error("images.$inventory") {{ $message }} @enderror
                                        </span>
                                    @isset($images[$inventory])
                                        <img class="card-img-top img-fluid center"
                                             src="{{ isset($images[$inventory]) ? $images[$inventory]->temporaryUrl() : '' }}"/>
                                    @endisset
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
        <div>
            @if($showCloseProductsCards)
                <div class="col-sm-12 d-flex justify-content-end">
                    <x-base.button type="submit" class="primary me-1 mb-1">
                        Save
                    </x-base.button>
                </div>
            @endif
        </div>

    </x-form.form>
</div>

@push('head')
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            height: 150px;
            width: auto;
        }
    </style>
@endpush
