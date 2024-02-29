<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="bi bi-cart-check text-primary"></i> Xác nhận thanh toán
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="checkout-form" action="{{ route('app.pos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if (session()->has('checkout_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                <span>{{ session('checkout_message') }}</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="row" style="min-height: 30vh">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr >
                                        <th style="border: 0">Tổng {{ Cart::instance($cart_instance)->count() }} sản phẩm</th>
                                        <td  class="text-right" style="border: 0">{{ format_currency($total_amount) }}</td>
                                        <input id="total_amount" type="hidden" class="form-control" name="total_amount" value="{{ $total_amount }}" readonly required>
                                    </tr>
                                    <tr>
                                        <th style="font-weight: 500">Tổng cộng</th>
                                        @php
                                            $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping
                                        @endphp
                                        <td class="text-right">
                                           {{ format_currency($total_with_shipping) }}
                                        </td>
                                        <input id="total_amount" type="hidden" class="form-control" name="paid_amount" value="{{ $total_amount }}" readonly required>
                                    </tr>
                                    <tr>
                                        <th style="font-weight: 500; border: 0">Khách trả</th>
                                        @php
                                            $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping
                                        @endphp
                                        <td  class="text-right" style="border: 0">
                                            {{ format_currency($total_with_shipping) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #fff; color: black; min-height: 50px; width: 100px; border: 1px solid #0e873f">Quay lại</button>
                    <button type="submit" class="btn" style="background-color: #0e873f; color: #fff; min-height: 50px; width: 100px">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    th, td {
        font-weight: 400;
    }
</style>
