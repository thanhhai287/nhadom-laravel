<!--
<div>
    <div class="card border-0 shadow-sm" style="height: 75vh!important;" >
        <div class="card-body" style="padding: 0">
            <div class="d-flex justify-content-around" style="max-height: 55vh!important; overflow: auto">
                @if (session()->has('message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <span>{{ session('message') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
            <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        @if($cart_items->isNotEmpty())
                            @foreach($cart_items as $cart_item)
                                <tr>
                                    <td class="align-middle d-flex flex-column">
                                        <div style="font-weight: 600;">
                                            {{ $cart_item->name }}
                                        </div>
                                        <div class="d-flex justify-content-around mt-3">
                                            <div class="d-flex flex-column">
                                                <a href="#" wire:click.prevent="removeItem('{{ $cart_item->rowId }}')">
                                                    <i class="bi bi-trash font-xl text-dark mr-2"></i>
                                                </a>
                                                @include('livewire.includes.product-cart-modal')
                                                 </div>
                                            @include('livewire.includes.product-cart-quantity')
                                            <span style="color: #2f5cdb; font-weight: 500">    {{ format_currency($cart_item->price) }}</span>

                                        </div>

                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">
                        <span class="text-danger">
                           Bắt đầu lên đơn với phần sản phẩm đã có bên trái
                        </span>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @if($total_amount != 0)
                <div class="d-flex flex-column"  style="position: absolute; bottom: 10px; margin: 0; width: 100%; text-align: center">
                    <div style="padding: 30px 0px; border-top: 5px solid #ebedef">
                        <div class="form-group d-flex justify-content-between flex-wrap mb-0" style="width: 100%">
                            <span style="border: 0; margin-left: 15px">Tổng {{ Cart::instance($cart_instance)->count() }} sản phẩm</span>
                            <span  class="text-right" style="border: 0; margin-right: 15px">{{ format_currency($total_amount) }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group d-flex justify-content-center flex-wrap mb-0" style="width: 100%">
                            <button style="background-color: #0e873f; color: #fff; min-height: 50px; width: 85%; font-size: 1.1rem" wire:loading.attr="disabled" wire:click="proceed" type="button" class="btn btn-pill" {{  $total_amount == 0 ? 'disabled' : '' }}>Thanh toán<br>{{format_currency($total_amount)}}</button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @include('livewire.pos.includes.checkout-modal')

</div>

-->
