<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Expense\Entities\Expense;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchasePayment;
use Modules\PurchasesReturn\Entities\PurchaseReturn;
use Modules\PurchasesReturn\Entities\PurchaseReturnPayment;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SalePayment;
use Modules\SalesReturn\Entities\SaleReturn;
use Modules\SalesReturn\Entities\SaleReturnPayment;

class HomeController extends Controller
{

    public function index() {
        $sales = Sale::completed()->sum('total_amount');
        $sale_returns = SaleReturn::completed()->sum('total_amount');
        $purchase_returns = PurchaseReturn::completed()->sum('total_amount');
        $product_costs = 0;

        foreach (Sale::completed()->with('saleDetails')->get() as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                if (!is_null($saleDetail->product)) {
                    $product_costs += $saleDetail->product->product_cost * $saleDetail->quantity;
                }
            }
        }

        $revenue = ($sales - $sale_returns) / 100;
        $profit = $revenue - $product_costs;

        $sales_today = Sale::where('status', 'Completed')->whereDate('date', Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d'))->get();
        $revenue_today = $sales_today->sum('total_amount') ;
        $revenue_today_cash = $sales_today->filter(function ($sale) {
            return $sale->payment_method == 'Cash';
        })->sum('total_amount');
        $revenue_today_bank = $sales_today->filter(function ($sale) {
            return $sale->payment_method == 'Bank Transfer';
        })->sum('total_amount');

        $revenue_month = Sale::where('status', 'Completed')
            ->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->sum('total_amount') / 100;

        $product_costs_month = 0;

        foreach (Sale::where('status', 'Completed')->whereMonth('date', date('m'))
                     ->whereYear('date', date('Y'))->with('saleDetails')->get() as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                if (!is_null($saleDetail->product)) {
                    $product_costs_month += $saleDetail->product->product_cost * $saleDetail->quantity;
                }
            }
        }

        $profit_month = $revenue_month - $product_costs_month;

        $statistics_day = collect(DB::select('WITH sales_temp AS (SELECT * FROM sale_details WHERE created_at >= ? and created_at <= ?)
	SELECT p.product_name,  IFNULL(SUM(s.quantity), 0) as count, price FROM sales_temp s RIGHT JOIN products p on p.id = s.product_id GROUP BY p.product_name ORDER BY count DESC;'
            , [Carbon::now('Asia/Ho_Chi_Minh')->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')->endOfDay()]));

        $statistics_month = collect(DB::select('WITH sales_temp AS (SELECT * FROM sale_details WHERE created_at >= ? and created_at <= ?)
	SELECT p.product_name,  IFNULL(SUM(s.quantity), 0) as count FROM sales_temp s RIGHT JOIN products p on p.id = s.product_id GROUP BY p.product_name ORDER BY count DESC;'
            , [Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth(), Carbon::now('Asia/Ho_Chi_Minh')->endOfMonth()]));

        return view('home', [
            'revenue'          => $revenue,
            'sale_returns'     => $sale_returns / 100,
            'purchase_returns' => $purchase_returns / 100,
            'profit'           => $profit,
            'revenue_today'    => $revenue_today,
            'revenue_today_bank'    => $revenue_today_bank,
            'revenue_today_cash'    => $revenue_today_cash,
            'profit_month'    => $profit_month,
            'revenue_month'    => $revenue_month,
            'total_statistics_day' => $statistics_day->sum('count'),
            'total_statistics_month' => $statistics_month->sum('count'),
            'statistics_day' => $statistics_day,
            'statistics_month' => $statistics_month,
        ]);
    }


    public function currentMonthChart() {
        // Hàm được đổi lại thành Tổng quan trong hôm nay
        abort_if(!request()->ajax(), 404);

//        $currentMonthSales = Sale::where('status', 'Completed')->whereMonth('date', date('m'))
//                ->whereYear('date', date('Y'))
//                ->sum('total_amount') / 100;
//        $currentMonthPurchases = Purchase::where('status', 'Completed')->whereMonth('date', date('m'))
//                ->whereYear('date', date('Y'))
//                ->sum('total_amount') / 100;
//        $currentMonthExpenses = Expense::whereMonth('date', date('m'))
//                ->whereYear('date', date('Y'))
//                ->sum('amount') / 100;
//
//        return response()->json([
//            'sales'     => $currentMonthSales,
//            'purchases' => $currentMonthPurchases,
//            'expenses'  => $currentMonthExpenses
//        ]);
        $today = Sale::where('status', 'Completed')->whereDate('date', Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d'))->get();
        $morning =  $today->where('created_at',">=", Carbon::now('Asia/Ho_Chi_Minh')->startOfDay()->format('Y-m-d H:i:s'))
                                ->where('created_at',"<", Carbon::createFromTime(12, 0, 0, 'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'))
                                ->sum('total_amount');
        $afternoon =  $today->where('created_at',">=", Carbon::createFromTime(12, 0, 0, 'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'))
                                    ->where('created_at',"<", Carbon::createFromTime(17, 0, 0, 'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'))
                                    ->sum('total_amount');
        $night =  $today->where('created_at',">=", Carbon::createFromTime(17, 0, 0, 'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'))
                                ->where('created_at',"<=", Carbon::now('Asia/Ho_Chi_Minh')->endOfDay()->format('Y-m-d H:i:s'))
                                ->sum('total_amount');


        $currentMonth = Sale::where('status', 'Completed')->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))->get();
        $morningMonth = $currentMonth->filter(function ($sale) {
            return Carbon::parse($sale->created_at)->format('H:i:s') >= Carbon::now('Asia/Ho_Chi_Minh')->startOfDay()->format('H:i:s')
            and Carbon::parse($sale->created_at)->format('H:i:s') < Carbon::createFromTime(12, 0, 0, 'Asia/Ho_Chi_Minh')->format('H:i:s');
        })->sum('total_amount');
        $afternoonMonth = $currentMonth->filter(function ($sale) {
            return Carbon::parse($sale->created_at)->format('H:i:s') >= Carbon::createFromTime(12, 0, 0, 'Asia/Ho_Chi_Minh')->format('H:i:s')
                and Carbon::parse($sale->created_at)->format('H:i:s') < Carbon::createFromTime(17, 0, 0, 'Asia/Ho_Chi_Minh')->format('H:i:s');
        })->sum('total_amount');
        $nightMonth = $currentMonth->filter(function ($sale) {
            return Carbon::parse($sale->created_at)->format('H:i:s') >= Carbon::createFromTime(17, 0, 0, 'Asia/Ho_Chi_Minh')->format('H:i:s')
                and Carbon::parse($sale->created_at)->format('H:i:s') <= Carbon::createFromTime(23, 59, 59, 'Asia/Ho_Chi_Minh')->format('H:i:s');
        })->sum('total_amount');
        return response()->json([
            'morning'     => $morning,
            'afternoon' => $afternoon,
            'night'  => $night,
            'morningMonth'  => $morningMonth,
            'afternoonMonth'  => $afternoonMonth,
            'nightMonth'  => $nightMonth,
        ]);
    }


    public function salesPurchasesChart() {
        abort_if(!request()->ajax(), 404);

        $sales = $this->salesChartData();

        return response()->json(['sales' => $sales]);
    }


    public function paymentChart() {
        abort_if(!request()->ajax(), 404);

        $dates = collect();
        foreach (range(-11, 0) as $i) {
            $date = Carbon::now('Asia/Ho_Chi_Minh')->addMonths($i)->format('m-Y');
            $dates->put($date, 0);
        }

        $date_range = Carbon::today()->subYear()->format('Y-m-d');

        $sale_payments = SalePayment::where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $sale_return_payments = SaleReturnPayment::where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $purchase_payments = PurchasePayment::where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $purchase_return_payments = PurchaseReturnPayment::where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $expenses = Expense::where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $payment_received = array_merge_numeric_values($sale_payments, $purchase_return_payments);
        $payment_sent = array_merge_numeric_values($purchase_payments, $sale_return_payments, $expenses);

        $dates_received = $dates->merge($payment_received);
        $dates_sent = $dates->merge($payment_sent);

        $received_payments = [];
        $sent_payments = [];
        $months = [];

        foreach ($dates_received as $key => $value) {
            $received_payments[] = $value;
            $months[] = $key;
        }

        foreach ($dates_sent as $key => $value) {
            $sent_payments[] = $value;
        }

        return response()->json([
            'payment_sent' => $sent_payments,
            'payment_received' => $received_payments,
            'months' => $months,
        ]);
    }

    public function salesChartData() {
        $query = collect(DB::select('WITH sales_temp AS (SELECT * FROM sales where month(date) = 02 and year(date) = 2024)
        select date, SUM(total_amount) AS count, payment_method from sales_temp group by date, payment_method order by date asc;'));

        $data = [];
        foreach ($query as $item) {
            array_push($data, $item);
        }

        return response()->json(['data' => $data]);
    }


    public function purchasesChartData() {
        $dates = collect();
        foreach (range(-6, 0) as $i) {
            $date = Carbon::now('Asia/Ho_Chi_Minh')->addDays($i)->format('d-m-y');
            $dates->put($date, 0);
        }

        $date_range = Carbon::today()->subDays(6);

        $purchases = Purchase::where('status', 'Completed')
            ->where('date', '>=', $date_range)
            ->groupBy(DB::raw("DATE_FORMAT(date,'%d-%m-%y')"))
            ->orderBy('date')
            ->get([
                DB::raw(DB::raw("DATE_FORMAT(date,'%d-%m-%y') as date")),
                DB::raw('SUM(total_amount) AS count'),
            ])
            ->pluck('count', 'date');

        $dates = $dates->merge($purchases);

        $data = [];
        $days = [];
        foreach ($dates as $key => $value) {
            $data[] = $value / 100;
            $days[] = $key;
        }

        return response()->json(['data' => $data, 'days' => $days]);

    }

    public function productStatistics()
    {
        $statistics_day = collect(DB::select('`SELECT p.product_name, COUNT(price) FROM sale_details s RIGHT JOIN products p on p.id = s.product_id
	GROUP BY p.product_name;`'));

        return response()->json(['statistics_day' => $statistics_day]);
    }
}
