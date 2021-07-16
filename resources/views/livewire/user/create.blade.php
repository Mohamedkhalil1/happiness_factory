<div>
    @section('title',$pageTitle)
    <x-base.card title="{{$pageTitle}}">
        <x-form.form action="createOrUpdate">
            <x-form.form-group col="6">
                <x-form.label title="Attribute 1"/>
                <x-form.input lazy="true" name="name" type="text"/>
            </x-form.form-group>

            <x-form.form-group col="6">
                <x-form.label title="Attribute 2"/>
                <x-form.input lazy="true" name="email" type="text"/>
            </x-form.form-group>


            <x-form.form-group col="12">
                <x-form.label title="Description"/>
                <x-form.textarea lazy="true" name="aboutMe" title="Description"/>
            </x-form.form-group>
            <x-form.form-group col="12">
                <x-form.switch title="Select Product Products" name="showCloseProductsCards"/>
            </x-form.form-group>

            <div class="col-sm-12 d-flex justify-content-end">
                <x-base.button type="submit" class="primary me-1 mb-1">
                   <x-icons.money /> Submit
                </x-base.button>
            </div>
        </x-form.form>
    </x-base.card>


    @if($showCloseProductsCards)
        <x-base.card title="test cards">
            <x-base.grid>
                @foreach($products as $product)
                    <x-base.grid-col >
                        <x-base.card-with-image title="Products" description="{{$product}}" removeFunction="removeItem" action="notify"/>
                    </x-base.grid-col>
                @endforeach
            </x-base.grid>

        </x-base.card>
    @endif

</div>
