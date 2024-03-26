<?php

namespace Modules\Sale\DataTables;

use Carbon\Carbon;
use Modules\Sale\Entities\Sale;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalesDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('total_amount', function ($data) {
                return format_currency($data->total_amount);
            })
            ->addColumn('paid_amount', function ($data) {
                return format_currency($data->paid_amount);
            })
            ->addColumn('payment_method', function ($data) {
                return $data->payment_method === 'Bank Transfer' ? 'Chuyển khoản' : 'Tiền mặt' ;
            })
//            ->addColumn('payment_status', function ($data) {
//                return view('sale::partials.payment-status', compact('data'));
//            })
            ->addColumn('date', function ($data) {
                return $data->created_at;
            })
            ->addColumn('detail', function ($data) {
                return view('sale::partials.actions', compact('data'));
            })
            ->removeColumn('customer_name')
            ->removeColumn('reference');
    }

    public function query(Sale $model) {
        $start_date = request()->start_date ?  Carbon::parse(request()->start_date)->startOfDay()->format('Y-m-d H:i:s')  : Carbon::now('Asia/Ho_Chi_Minh')->startOfDay()->format('Y-m-d H:i:s');
        $end_date = request()->end_date ? Carbon::parse(request()->end_date)->endOfDay()->format('Y-m-d H:i:s') : Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');

        return $model->newQuery()->where('created_at', '>=', $start_date)
                                 ->where('created_at', '<=', $end_date)
                                 ->orderBy('created_at', 'desc');
    }

    public function html() {
        return $this->builder()
            ->setTableId('sales-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)->paging(false);

    }

    protected function getColumns() {
        return [
            Column::computed('total_amount')
                ->className('text-center align-middle'),

            Column::computed('paid_amount')
                ->className('text-center align-middle'),

            Column::computed('payment_method')
                ->className('text-center align-middle'),

//            Column::computed('payment_status')
//                ->className('text-center align-middle'),
            Column::computed('date')
                ->className('text-center align-middle'),
            Column::computed('detail')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'Sales_' . date('YmdHis');
    }
}
