{{--<div>--}}
{{--    <div class="form-row">--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="form-group">--}}
{{--                <nav>--}}
{{--                    <div class="nav nav-tabs" id="nav-tab" role="tablist" wire:model="category" style="border: 0; font-size: 1rem; line-height: 1.5rem">--}}
{{--                        <a style="width: 100%; padding-left: 35px; border: 0" class="nav-item nav-link text-dark" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true" wire:click.prevent="selectCategory(null)">Tất cả</a>--}}
{{--                        @foreach($categories as $category)--}}
{{--                            <a style="width: 100%; padding-left: 35px; border: 0" class="nav-item nav-link text-dark" id="nav-{{ $category->id }}-tab" data-toggle="tab" href="#nav-{{ $category->id }}" role="tab" aria-controls="nav-{{ $category->id }}" aria-selected="false" wire:click.prevent="selectCategory('{{ $category->id }}')">{{ $category->category_name }}</a>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </nav>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--</div>--}}
<style>
    /*.active {*/
    /*    background-color: #ebedef;*/
    /*    font-weight: 600;*/
    /*}*/
</style>
@push('page_scripts')
    <script>
        $(document).ready(function () {
            window.addEventListener('categoryNavChanged', event => {
                console.log('categoryNavChanged')
               if (event.detail != null) {}
                $('#nav-all-tab').removeClass('active');
                $('#nav-' + event.detail + '-tab').addClass('active');
            });

            $('#nav-all-tab').addClass('active');
        });
    </script>
@endpush

