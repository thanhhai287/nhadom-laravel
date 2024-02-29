<div class="d-flex ">
    <a href="{{ route('sales.show', $data->id) }}" class=" dropdown-item"  style="padding: 0px!important;">
        <i class="bi bi-eye text-info" style="line-height: 1;"></i>     </a>
    @can('edit_sales')
        <a href="{{ route('sales.edit', $data->id) }}" class=" dropdown-item" style="padding: 0px!important;">
            <i class="bi bi-pencil mr-2 text-primary" style="line-height: 1;"></i>
        </a>
    @endcan
    @can('delete_sales')
        <button id="delete" class="dropdown-item"  style="padding: 0px!important;" onclick="
            event.preventDefault();
            if (confirm('Are you sure? It will delete the data permanently!')) {
            document.getElementById('destroy{{ $data->id }}').submit()
            }">
            <i class="bi bi-trash mr-2 text-danger" style="line-height: 1;"></i>
            <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('sales.destroy', $data->id) }}" method="POST">
                @csrf
                @method('delete')
            </form>
        </button>
    @endcan

</div>
