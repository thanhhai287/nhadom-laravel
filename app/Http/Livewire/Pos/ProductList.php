<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Controllers\CategoriesController;

class ProductList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'selectedCategory' => 'categoryChanged',
        'showCount'        => 'showCountChanged'
    ];

    public $categories;
    public $category_id;
    public $limit = 9;

    public function mount($categories) {
        $this->categories = $categories;
        $this->category_id = '';
    }

    public function render() {
        $products = Product::all();
        foreach ($products as $product) {
            $product->image = $product->getFirstMediaUrl('images');
        }

        return view('livewire.pos.product-list', [

            'products' =>$products,
            'categories' => Category::all()
//            ->paginate($this->limit)
        ]);
    }

    public function categoryChanged($category_id) {
        $this->category_id = $category_id;
        $this->resetPage();
    }

    public function showCountChanged($value) {
        $this->limit = $value;
        $this->resetPage();
    }

    public function selectProduct($product) {
        $this->emit('productSelected', $product);
    }
}
