@if(!empty($products->toArray()))
@foreach($products as $key => $product)
<div role="row" row-id="{{ $product->pid }}" class="tb-row tb-row-{{ $rowEvenOdd[$key%2] }} tb-row-no-animation" style="top: {{ $rowTop += 30 }}px;">
    <div tabindex="-1" col-id="isSelected" class="tb-cell tb-cell-no-focus text-center" style="width: 33px; left: 0px; ">
        <div class="ui-grid-cell-contents">        
            <input class="magic-checkbox blue" name="layout" type="checkbox" id="checkbox{{ $product->pid }}" value="{{ $product->pid }}" {{ in_array($product->pid,$selected)?'checked':'' }}>
            <label for="checkbox{{ $product->pid }}"></label>
        </div>
    </div>
    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 130px; left: 33px; ">{{ $product->p_number }}</div>
    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 300px; left: 163px; ">
        @if($product->p_pic)
        <a href="#" role="button" data-toggle="popover" data-container="#selectproduct" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $product->p_pic }}' width='150px'>">{{ $product->p_name }}</a>        @else {{ $product->p_name }} @endif

    </div>
    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 463px; ">{{ $product->p_unit }}</div>
    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-right" style="width: 80px; left: 543px; ">{{ $product->stock()->where('wid', Admin::user()->wid)->sum('st_stock') }}</div>

    <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 200px; left: 623px; "></div>
</div>
@endforeach

<script>
$(function() {
    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });    

    $('#selectproduct input.magic-checkbox').on('click', function () {
        /**
         * 如果pid不在陣列中則把pid塞入陣列，如果pid在陣列中則移除它
         */
        var inArrayIndex = selectResultArray.indexOf($(this).val());
        if( inArrayIndex === -1){
            selectResultArray.push($(this).val());    
        }else{
            selectResultArray.splice(inArrayIndex,1);
        }
        $('#selectproduct .select2-results span').text(selectResultArray.length);
    });
});
</script>
@endif