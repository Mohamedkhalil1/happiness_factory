<div {{ $attributes }}>
    <input class="form-control" type="date" x-data @change="$dispatch('input',$event.target.value)"/>
</div>
@error($name) <span class="text-danger"> {{ $message }}  </span>@enderror
