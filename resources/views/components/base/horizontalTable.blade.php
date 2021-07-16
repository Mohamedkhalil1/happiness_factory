@props(['resources','model'])
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        @foreach($resources as $resource)
            @if($resource['isVisible']?? true)
                <tr>
                    <th>{{$resource['header']}}</th>
                    <td>{{$resource['value']($model)}}</td>
                </tr>
            @endif
        @endforeach
        @isset($extraColumns)
            {{ $extraColumns }}
        @endisset
    </table>
</div>
