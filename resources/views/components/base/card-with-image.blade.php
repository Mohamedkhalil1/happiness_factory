@props([
    'imageUrl'              => 'assets/images/samples/origami.jpg' ,
    'title'                 => '',
    'description'           => 'Jelly-o sesame snaps cheesecake topping. Cupcake fruitcake macaroon donut
                                            pastry gummies tiramisu chocolate bar muffin. Dessert bonbon caramels
                                            brownie chocolate
                                            bar
                                            chocolate tart dragée.' ,
     'footerText'           =>' Cupcake fruitcake macaroon donut pastry gummies tiramisu chocolate bar
                                            muffin.',
      'action'              => '',
      'removeFunction'      => ''
      ])
<div class="card">
    <div class="card-content">
        <img class="card-img-top img-fluid" src="{{ asset($imageUrl) }}"
             alt="Card image cap" style="height: 20rem"/>
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            <p class="card-text">
                {{ $description }}
            </p>
            <p class="card-text">
                {{ $footerText }}
            </p>
            <button wire:click="{{ $action }}" class="btn btn-outline-primary btn-sm">
                <i class="iconly-boldCall"></i> Add To
            </button>
            <button wire:click="{{$removeFunction}}('{{$description}}')" class="btn btn-outline-danger btn-sm">
                <i class="iconly-boldCall"></i> Remove
            </button>
        </div>
    </div>
</div>
