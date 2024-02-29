@extends('layouts.app')

@section('title', 'POS')

@section('third_party_stylesheets')

@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">POS</li>
    </ol>
@endsection
<style>
    ::-webkit-scrollbar {
        display: none;
    }
    .c-header {
        height: 56px!important;
    }

</style>
@section('content')
    <div class="container-fluid" style="padding: 0; margin: 0!important; position: fixed; top:56px; bottom: 0">
        <div class="row" style="height: 100%!important; margin: 0!important;">
            <div class="col-12">
                @include('utils.alerts')
            </div>
            <div class="col-lg-12" style="-ms-overflow-style: none;  scrollbar-width: none; padding: 0">
                <livewire:pos.product-list :categories="$product_categories"/>
            </div>
{{--            <div class="col-lg-4" style="overflow: auto">--}}
{{--                <livewire:pos.checkout :cart-instance="'sale'" :customers="$customers"/>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            window.addEventListener('showCheckoutModal', event => {
                $('#checkoutModal').modal('show');

                {{--$('#paid_amount').maskMoney({--}}
                {{--    prefix:'{{ settings()->currency->symbol }}',--}}
                {{--    thousands:'{{ settings()->currency->thousand_separator }}',--}}
                {{--    decimal:'{{ settings()->currency->decimal_separator }}',--}}
                {{--    allowZero: false,--}}
                {{--});--}}

                {{--$('#total_amount').maskMoney({--}}
                {{--    prefix:'{{ settings()->currency->symbol }}',--}}
                {{--    thousands:'{{ settings()->currency->thousand_separator }}',--}}
                {{--    decimal:'{{ settings()->currency->decimal_separator }}',--}}
                {{--    allowZero: true,--}}
                {{--});--}}

                {{--$('#paid_amount').maskMoney('mask');--}}
                {{--$('#total_amount').maskMoney('mask');--}}

                {{--$('#checkout-form').submit(function () {--}}
                {{--    var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];--}}
                {{--    $('#paid_amount').val(paid_amount);--}}
                {{--    var total_amount = $('#total_amount').maskMoney('unmasked')[0];--}}
                {{--    $('#total_amount').val(total_amount);--}}
                {{--});--}}
            });
        });
    </script>

@endpush
