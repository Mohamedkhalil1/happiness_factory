@section('title', 'Register')
<x-general.auth
    title="Sign Up"
    description="Input your data to register to our website."
    footerText="Already have an account?"
    footerActionUrl="{{ route('login') }}"
    footerAction="Log in"
>
    <x-form.form action="register">
        <x-form.form-group class="position-relativ mb-4">
            <x-form.input name="email" type="email" placeholder="Email"></x-form.input>
        </x-form.form-group>
        <x-form.form-group class="position-relativ mb-4">
            <x-form.input name="name" type="text" placeholder="Name"></x-form.input>
        </x-form.form-group>
        <x-form.form-group class="position-relativ mb-4">
            <x-form.input lazy="true" name="password" type="password" placeholder="Password"></x-form.input>
        </x-form.form-group>
        <x-form.form-group class="position-relativ mb-4">
            <x-form.input lazy="true" name="passwordConfirmation" type="password" placeholder="Confirm Password"></x-form.input>
        </x-form.form-group>
        <x-base.button type="submit" class="primary btn-block btn-lg shadow-lg mt-5">
            Sign Out
        </x-base.button>
    </x-form.form>

</x-general.auth>

