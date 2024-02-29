<div>
    <div>
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
        <div class="table-responsive position-relative">
            <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <table class="table table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th class="align-middle">Product</th>
                    <th class="align-middle text-center">Quantity</th>
                    <th class="align-middle text-center">Sub Total</th>
                    @can('access_user_management')
                    <th class="align-middle text-center">Action</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                    @if($cart_items->isNotEmpty())
                        @foreach($cart_items as $cart_item)
                            <tr>
                                <td class="align-middle">
                                    {{ $cart_item->name }}
                                </td>

                                @can('access_user_management')
                                <td class="align-middle">
                                    @include('livewire.includes.product-cart-quantity')
                                </td>
                                @else
                                    <td class="align-middle text-center">
                                      {{$cart_item->qty}}
                                    </td>
                                @endcan

                                <td class="align-middle text-center">
                                    {{ format_currency($cart_item->options->sub_total) }}
                                </td>

                                @can('access_user_management')
                                <td class="align-middle text-center">
                                    <a href="#" wire:click.prevent="removeItem('{{ $cart_item->rowId }}')">
                                        <i class="bi bi-x-circle font-2xl text-danger"></i>
                                    </a>
                                </td>
                                @endcan
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                        <span class="text-danger">
                            Please search & select products!
                        </span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-md-end">
        <div class="col-md-4 d-flex justify-content-end">
            <span class="font-weight-bold text-right">Tổng cộng giá trị đơn hàng: <span class="text-danger">{{ format_currency(Cart::instance($cart_instance)->total()) }}</span></span>


        </div>
    </div>

    <input type="hidden" name="total_amount" value="{{ Cart::instance($cart_instance)->total() }}">

    <div class="form-row">
        <div class="col-lg-4">
            <div class="form-group">
                <input wire:model.lazy="global_tax" type="hidden" class="form-control" name="tax_percentage" min="0" max="100" value="{{ $global_tax }}" required>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <input wire:model.lazy="global_discount" type="hidden" class="form-control" name="discount_percentage" min="0" max="100" value="{{ $global_discount }}" required>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <input wire:model.lazy="shipping" type="hidden" class="form-control" name="shipping_amount" min="0" value="0" required step="0.01">
            </div>
        </div>
    </div>
</div>
