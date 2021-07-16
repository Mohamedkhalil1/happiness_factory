<div>
    @section('title',$pageTitle)
    <x-base.card title="{{ $pageTitle }}">
        <x-form.form action="save" :isHorizontal="true">

            <div class="col-md-4">
                <x-form.label title="Name"/>
            </div>
            <x-form.form-group col="8" hasIcon>
                <x-form.input lazy="true" name="name" type="text" placeholder="Insert Name"
                              rightIcon="bi bi-person"/>
            </x-form.form-group>


            {{--            Communication between js and livewire so easily with event input--}}
            {{--            <div wire:model="count">--}}
            {{--                <div class="col-md-4">--}}
            {{--                    <x-form.label title="Count" :required="false"/>--}}
            {{--                    {{ 'livewire Count:' . $count }}--}}
            {{--                </div>--}}
            {{--                <x-form.form-group col="8">--}}
            {{--                    Count:--}}
            {{--                    <x-base.button type="button" title="0"--}}
            {{--                                   class="default"--}}
            {{--                                   x-data="{ count : 0 } "--}}
            {{--                                   @click="count++;$dispatch('input',count)"--}}
            {{--                                   x-text="count"/>--}}
            {{--                </x-form.form-group>--}}
            {{--            </div>--}}


            <div class="col-md-4">
                <x-form.label title="Email"/>
            </div>
            <x-form.form-group col="8">
                <x-form.input lazy="true" name="email" type="email" placeholder="Insert Email"/>
            </x-form.form-group>

            <div class="col-md-4">
                <x-form.label title="Password" :required="false"></x-form.label>
            </div>
            <x-form.form-group col="8">
                <x-form.input :required="false" lazy="true" name="password" type="password" placeholder="Password"
                              inputGroupText="{{ $password }}"/>
            </x-form.form-group>

            <div class="col-md-4">
                <x-form.label :required="false" title="Password Confirmation"/>
            </div>
            <x-form.form-group col="8">
                <x-form.input :required="false" lazy="true" name="passwordConfirmation" type="password"
                              placeholder="Password Confirmation"/>
            </x-form.form-group>

            <div class="col-md-4">
                <x-form.label title="Birthday"/>
            </div>
            <x-form.form-group col="8">
                <x-form.date-time id="birthday" name="birthday" wire:model="birthday" type="text" placeholder="MM/DD/YYYY"/>
            </x-form.form-group>

            <div class="col-md-4">
                <x-form.label :required="false" title="Gender"/>
            </div>
            <x-form.form-group col="8">
                <x-form.select2 value="{{ $gender }}" name="gender" :options="\App\Enums\Gender::keyValue()"
                               selectTitle="Select Gender" wire:model="gender"
                               wire:ignore/>
            </x-form.form-group>

            <div class="col-md-4">
                <x-form.label :required="false" title="Social Status"/>
            </div>
            <x-form.form-group col="8">
                @foreach(\App\Enums\SocialStatus::keyValue() as $socialStatus)
                    <x-form.radio-button name="social_status" wire:model="social_status"
                                         value="{{ $socialStatus['id'] }}"
                                         title="{{ $socialStatus['name'] }}"/>
                @endforeach
            </x-form.form-group>

            <div class="col-md-4">
                <x-form.label :required="false" title="Has Job ?"/>
            </div>
            <x-form.form-group col="8">
                <x-form.checkbox name="has_job" title="Yes ,I have a job"/>
            </x-form.form-group>

            <div class="col-md-4">
                <x-form.label :required="false" title="About you"/>
            </div>
            <x-form.form-group col="8">
                <x-form.textarea wire:model="about" title="about"/>
                {{--                <x-form.rich-text name="about" inital-values="{{ $about }}" wire:model.lazy="about"/>--}}
            </x-form.form-group>

            <div class="col-md-4">
                <x-form.label :required="false" title="User Avatar"/>
            </div>
            <x-form.form-group col="8">
                @if($user->avatar)
                    <x-base.avatar imageUrl="{{ $user->getAvatar() }}"/>
                @endif
                <x-form.upload-photo name="avatar"/>
            </x-form.form-group>

            <div class="col-sm-12 d-flex justify-content-end">
                <x-base.button type="submit" class="primary me-1 mb-1">
                    Submit
                </x-base.button>
            </div>
        </x-form.form>
    </x-base.card>
</div>

