<?php

namespace Modules\Sale\Http\Controllers;

use Carbon\Carbon;
use Modules\Sale\DataTables\SalesDataTable;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\People\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Http\Requests\StoreSaleRequest;
use Modules\Sale\Http\Requests\UpdateSaleRequest;
use Illuminate\Http\Request;
class SaleController extends Controller
{

    public function index(SalesDataTable $dataTable) {
        abort_if(Gate::denies('access_sales'), 403);
        $start_date = request()->start_date ?  Carbon::parse(request()->start_date)->startOfDay()->format('Y-m-d')  : Carbon::now('Asia/Ho_Chi_Minh')->startOfDay()->format('Y-m-d');
        $end_date = request()->end_date ? Carbon::parse(request()->end_date)->endOfDay()->format('Y-m-d') : Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        return $dataTable->render('sale::index', compact(['start_date', 'end_date']));
    }


    public function create() {
        abort_if(Gate::denies('create_sales'), 403);

        Cart::instance('sale')->destroy();

        return view('sale::create');
    }


    public function store(StoreSaleRequest $request) {
        DB::transaction(function () use ($request) {
            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount == $request->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            $sale = Sale::create([
                'date' => $request->date,
                'customer_id' => 1,
                'customer_name' => Customer::findOrFail(1)->customer_name,
                'tax_percentage' => 0,
                'discount_percentage' => 0,
                'shipping_amount' => 0 * 100,
                'paid_amount' => $request->paid_amount * 100,
                'total_amount' => $request->total_amount * 100,
                'due_amount' => $due_amount * 100,
                'status' => $request->status,
                'payment_status' => $payment_status,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'tax_amount' => Cart::instance('sale')->tax() * 100,
                'discount_amount' => Cart::instance('sale')->discount() * 100,
            ]);

            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price * 100,
                    'unit_price' => $cart_item->options->unit_price * 100,
                    'sub_total' => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax * 100,
                ]);

                if ($request->status == 'Shipped' || $request->status == 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }
            }

            Cart::instance('sale')->destroy();

            if ($sale->paid_amount > 0) {
                SalePayment::create([
                    'date' => $request->date,
                    'reference' => 'INV/'.$sale->reference,
                    'amount' => $sale->paid_amount,
                    'sale_id' => $sale->id,
                    'payment_method' => $request->payment_method
                ]);
            }
        });

        toast('Sale Created!', 'success');

        return redirect()->route('sales.index');
    }


    public function show(Sale $sale) {
        abort_if(Gate::denies('access_sales'), 403);

        $customer = Customer::findOrFail($sale->customer_id);

        return view('sale::show', compact('sale', 'customer'));
    }


    public function edit(Sale $sale) {
        abort_if(Gate::denies('edit_sales'), 403);

        $sale_details = $sale->saleDetails;

        Cart::instance('sale')->destroy();

        $cart = Cart::instance('sale');

        foreach ($sale_details as $sale_detail) {
            $cart->add([
                'id'      => $sale_detail->product_id,
                'name'    => $sale_detail->product_name,
                'qty'     => $sale_detail->quantity,
                'price'   => $sale_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount' => $sale_detail->product_discount_amount,
                    'product_discount_type' => $sale_detail->product_discount_type,
                    'sub_total'   => $sale_detail->sub_total,
                    'code'        => $sale_detail->product_code,
                    'stock'       => Product::findOrFail($sale_detail->product_id)->product_quantity,
                    'product_tax' => $sale_detail->product_tax_amount,
                    'unit_price'  => $sale_detail->unit_price
                ]
            ]);
        }

        return view('sale::edit', compact('sale'));
    }


    public function update(UpdateSaleRequest $request, Sale $sale) {
        DB::transaction(function () use ($request, $sale) {
            $due_amount = $request->total_amount - $request->paid_amount;
            if ($due_amount == $request->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            foreach ($sale->saleDetails as $sale_detail) {
                if ($sale->status == 'Shipped' || $sale->status == 'Completed') {
                    $product = Product::findOrFail($sale_detail->product_id);
                    $product->update([
                        'product_quantity' => $product->product_quantity + $sale_detail->quantity
                    ]);
                }
                $sale_detail->delete();
            }

            $sale->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'customer_id' => 1,
                'customer_name' => Customer::findOrFail(1)->customer_name,
                'tax_percentage' => 0,
                'discount_percentage' => 0,
                'shipping_amount' => 0 * 100,
                'paid_amount' => $request->paid_amount * 100,
                'total_amount' => $request->total_amount * 100,
                'due_amount' => 0 * 100,
                'status' => $request->status,
                'payment_status' => $payment_status,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'tax_amount' => 0 * 100,
                'discount_amount' => 0* 100,
            ]);

            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price * 100,
                    'unit_price' => $cart_item->options->unit_price * 100,
                    'sub_total' => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax * 100,
                    'created_at' => $sale->created_at,
                    'updated_at' => $sale->updated_at,
                ]);

                if ($request->status == 'Shipped' || $request->status == 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }
            }

            Cart::instance('sale')->destroy();
        });

        toast('Sale Updated!', 'info');

        return redirect()->route('sales.index');
    }


    public function destroy(Sale $sale) {
        abort_if(Gate::denies('delete_sales'), 403);

        $sale->delete();

        toast('Sale Deleted!', 'warning');

        return redirect()->route('sales.index');
    }

    public function storeSale(Request $request)
    {
        DB::transaction(function () use ($request) {
            $payment_status = 'Paid';
            $data_request = $request->json()->all()[0];
            if ($data_request != null) {
                $sale = Sale::create([
                    'date' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                    'customer_id' => 1,
                    'customer_name' => Customer::findOrFail(1)->customer_name,
                    'tax_percentage' => 0,
                    'discount_percentage' => 0,
                    'shipping_amount' => 0 * 100,
                    'paid_amount' => $data_request['paid_amount'] * 100,
                    'total_amount' => $data_request['total_amount'] * 100,
                    'due_amount' => 0 * 100,
                    'payment_status' => $payment_status,
                    'payment_method' => $data_request['payment_method'],
                    'tax_amount' => 0,
                    'status' => 'Completed',
                    'discount_amount' => 0,
                     'created_at' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                     'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                ]);

                foreach ($data_request['shopItems'] as $cart_item) {
                    SaleDetails::create([
                        'sale_id' => $sale->id,
                        'product_id' => $cart_item['id'],
                        'product_name' => $cart_item['product_name'],
                        'product_code' => $cart_item['product_name'],
                        'quantity' => $cart_item['quantity'],
                        'price' => $cart_item['product_price'] * 100,
                        'unit_price' => 0 * 100,
                        'sub_total' => $cart_item['product_price'] * 100,
                        'product_discount_amount' => 0 * 100,
                        'product_discount_type' => 'fixed',
                        'product_tax_amount' => 0 * 100,
                        'created_at' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                    ]);
                }

                if ($sale->paid_amount > 0) {
                    SalePayment::create([
                        'date' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                        'reference' => 'INV/'.$sale->reference,
                        'amount' => $sale->paid_amount,
                        'sale_id' => $sale->id,
                        'payment_method' => 'Cash',
                        'created_at' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                    ]);
                }
            }

        });

        toast('Sale Created!', 'success');

        return response()->json(['success'=> 'Sale Created!']);
    }
}
