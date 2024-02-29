<div id="app" style="margin-left: 0px">
    <pos :products="{{$products}}"
         :categories="{{$categories}}"
         :sale_store="{{json_encode(route("store-sale"))}}"
    >
    </pos>
</div>
