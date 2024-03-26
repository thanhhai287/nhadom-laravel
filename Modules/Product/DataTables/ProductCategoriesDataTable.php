<?php

namespace Modules\Product\DataTables;

use Modules\Product\Entities\Category;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductCategoriesDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                return view('product::categories.partials.actions', compact('data'));
            });
    }

    public function query(Category $model) {
        return $model->newQuery()->withCount('products');
    }

    public function html() {
        return $this->builder()
            ->setTableId('product_categories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(4)->paging(false);
    }

    protected function getColumns() {
        return [
            Column::make('category_code')
                ->addClass('text-center'),

            Column::make('category_name')
                ->addClass('text-center'),

            Column::make('products_count')
                ->addClass('text-center'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'ProductCategories_' . date('YmdHis');
    }
}
