@props(['model','isChangeable'=>true])

@once
    @push('script')
        <script src="{{ asset('dashboard-assets/js/map.js') }}"></script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.browser')}}&language={{App::getLocale()}}&callback=initMaps"
            async defer>
        </script>
    @endpush
@endonce

<input class="lat" name="lat" hidden type="text" title="lat"
       value="{{ $model->lat ?? ''  }}">
<input class="lng" name="lng" hidden type="text" title="lng"
       value="{{ $model->lng ?? '' }}">
<div class="map" {{$isChangeable ? 'data-can-change-location' : ''}} style="width: 100%;height: 350px;"></div>
