@section('title','Login')
<x-general.auth
    title="Login"
    description="Input your data to register to our website."
    footerText="Don't have an account?"
    footerActionUrl="{{ route('register') }}"
    footerAction="Sign Out">
    <x-form.form action="login">
        <x-form.form-group class="position-relativ mb-4">
            <x-form.input lazy="true" name="email" type="email" placeholder="Email"/>
        </x-form.form-group>
        <x-form.form-group class="position-relativ mb-4">
            <x-form.input lazy="true" name="password" type="password" placeholder="Password"/>
        </x-form.form-group>
        <div>
            <x-form.checkbox name="rememberMe" title="Keep me logged in" inputClass="me-2" labelClass="text-gray-600"/>
        </div>
        <x-base.button type="submit" class="primary btn-block btn-lg shadow-lg mt-5">
            @lang('Login')
        </x-base.button>
    </x-form.form>
</x-general.auth>

