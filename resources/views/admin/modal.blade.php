<div class='modal fade' id="selectproduct">
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="title">商品資料 </h4>
            </div>
            <div class='modal-body'>
                <div class="select2-search">
                    <div class="flex-row">
                        <div class="flex-col-xs no-padding padding-right-5">
                            <div class="input-group">
                                <input class="form-control no-border-right" placeholder="請輸入搜尋的商品" type="text">
                                <span class="input-group-btn">
                                <button class="btn btn-transparent-grey2" type="button"> <i class="fa fa-search"></i> </button>
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-orange"> 新增<i class="fa fa-plus margin-left-5"></i> </button>
                    </div>
                </div>
                <div class="select2-results footer-btn-mrgin">
                    <div class="tb-responsive">

                        <div class="fresh" style="width: 100%; height: 350px;">
                            <div class="tb-bl tb-bl-full-height tb-layout-normal tb-ltr">

                                <div class="tb-root tb-font-style" role="grid">
                                    <div class="tb-header" role="row" style="height: 30px;">
                                        <div class="tb-header-container">
                                            <div class="tb-header-cell" style="width: 33px; left: 0px;">
                                            </div>
                                            <div class="tb-header-cell" style="width: 130px; left: 33px;">品號</div>
                                            <div class="tb-header-cell" style="width: 300px; left: 163px;">品名</div>
                                            <div class="tb-header-cell" style="width: 80px; left: 463px;">單位</div>
                                            <div class="tb-header-cell" style="width: 80px; left: 543px;">庫存</div>
                                        </div>
                                    </div>
                                    <div class="tb-body" style="top: 30px; height: 320px;">
                                        <div class="tb-body-container" style="height: 720px; top: 0px; width: 837px;">
                                        

                                            <!-- Row (Even) Start-->
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
                                                        <a href="#" role="button" data-toggle="popover" data-container="#selectproduct" data-placement="bottom" data-html="true" data-content="<img src='{{ rtrim(config('admin.upload.host'), '/').'/'. $product->p_pic }}' width='150px'>">{{ $product->p_name }}</a>
                                                    @else
                                                        {{ $product->p_name }}
                                                    @endif
                                                    
                                                </div>
                                                <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 80px; left: 463px; ">{{ $product->p_unit }}</div>
                                                <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 80px; left: 543px; ">{{ $product->stock()->where('wid', Admin::user()->wid)->sum('s_stock') }}</div>
                                            </div>
                                            @endforeach

                                            <!-- Row (Even) End -->
                                            <!-- Row (Odd) Start-->
                                            <!-- <div role="row" row-id="23" class="tb-row tb-row-odd tb-row-no-animation" style="top: 30px;">
                                                <div tabindex="-1" col-id="isSelected" class="tb-cell tb-cell-no-focus text-center" style="width: 33px; left: 0px; ">
                                                    <div class="ui-grid-cell-contents">
                                                        <input class="magic-checkbox blue" name="layout" type="checkbox" id="checkbox23">
                                                        <label for="checkbox23"></label>
                                                    </div>
                                                </div>
                                                <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 130px; left: 33px; ">Z1001001-01</div>
                                                <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 300px; left: 163px; ">運費</div>
                                                <div tabindex="-1" class="tb-cell tb-cell-no-focus text-left" style="width: 80px; left: 463px; ">123</div>
                                            </div> -->
                                            <!-- Row (Odd) End -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
                <div class="select2-results">
                    <span>已選擇：25 </span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success margin-5"> 加入 <i class="fa fa-check"></i> </button>
                <button class="btn btn-danger margin-5" data-dismiss="modal" aria-hidden="true"> 取消 <i class="fa fa-times"></i> </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('[data-toggle="popover"]').popover({
            trigger: 'hover'
        });
    });
</script>