{{--<form wire:submit.prevent="updateQuantity('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')">--}}
{{--        <div class="input-group">--}}
{{--            <input wire:model.defer="quantity.{{ $cart_item->id }}" style="min-width: 40px;max-width: 90px;" type="number" class="form-control" value="{{ $cart_item->qty }}" min="1">--}}
{{--            <div class="input-group-append">--}}
{{--                <button type="submit" class="btn btn-primary">--}}
{{--                    <i class="bi bi-check"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--</form>--}}

<form wire:submit.prevent="updateQuantity('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')">
    <div class="input-group d-flex justify-content-between align-items-center">
        <div class="input-group-prepend" >
            <button wire:click.prevent="decrement('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" class="btn shadow-none">
                <i class="bi bi-dash-lg"></i>
            </button>
        </div>
        <input wire:model.defer="quantity.{{ $cart_item->id }}" style="min-width: 30px;max-width: 60px; color: #2f5cdb" type="number" class="form-control text-center" value="{{ $cart_item->qty }}" min="1">
        <div class="input-group-append">
            <button wire:click.prevent="increment('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" class="btn shadow-none">
                <i class="bi bi-plus-lg " style="color: green;"></i>
            </button>
        </div>
    </div>
</form>
