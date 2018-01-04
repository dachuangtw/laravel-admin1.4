 @foreach($products as $key => $product)
<div role="row" row-id="{{ $product->pid }}" class="tb-row tb-row-{{ $rowEvenOdd[$key%2] }} tb-row-no-animation" style="top: {{ $rowTop += 30 }}px;">
    <div tabindex="-1" col-id="isSelected" class="tb-cell tb-cell-no-focus text-center" style="width: 33px; left: 0px; ">
        <div class="ui-grid-cell-contents">
            <input class="magic-checkbox blue" name="layout" type="checkbox" id="checkbox{{ $product->pid }}">
            <label for="checkbox{{ $product->pid }}"></label>
        </div>
    </div>
    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 130px; left: 33px; ">{{ $product->p_number }}</div>
    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 300px; left: 163px; ">
        @if($product->p_pic)
        <a href="#" role="button" data-toggle="popover" data-container="#selectproduct" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $product->p_pic }}' width='150px'>">{{ $product->p_name }}</a>        @else {{ $product->p_name }} @endif

    </div>
    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 80px; left: 463px; ">{{ $product->p_unit }}</div>
    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 80px; left: 543px; ">{{ $product->stock()->where('wid', Admin::user()->wid)->sum('s_stock') }}</div>
</div>
@endforeach