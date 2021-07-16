@props([
    'title'           => 'login' ,
    'description'     => 'Input your data to register to our website.',
    'footerText'      => '',
    'footerActionUrl' => '',
    'footerAction'    => ''
    ])
<div id="auth">
    <div class="row h-100">
        <div class="col-md-7 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="#"><img src="{{asset('assets/images/logo/logo.png')}}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">{{ $title }}</h1>
                <p class="auth-subtitle mb-5">{{ $description }}</p>
                {{ $slot }}
                <div class="text-center mt-5 text-lg fs-4">
                    <p class='text-gray-600'>{{ $footerText }}
                        <a href="{{ $footerActionUrl }}" class="font-bold">
                            {{ $footerAction }}
                        </a>.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-5 d-none d-lg-block">
            <div id="auth-right">
            </div>
        </div>
    </div>

</div>
