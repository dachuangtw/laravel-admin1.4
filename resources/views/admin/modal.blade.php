<div class='modal fade' id="selectproduct">
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="title">商品資料{{ $target }} </h4>
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
                    <div class="table-responsive ">
                        <div class="fresh" style="width: 100%; height: 350px;">
                            <div class="dc-bl dc-bl-full-height dc-layout-normal dc-ltr" id="borderLayout_eRootPanel">
                                <div class="dc-bl-center dc-bl-full-height-center" ref="center" style="margin-left: 0px; width: 854px;">
                                    <div class="dc-bl dc-bl-full-height" id="borderLayout_eGridPanel">
                                        <div class="dc-bl-center dc-bl-full-height-center" ref="center" style="margin-left: 0px; width: 854px;">
                                            <div class="dc-root dc-font-style" role="grid">
                                                <div class="dc-header" role="row" style="height: 30px;">
                                                    <div class="dc-pinned-left-header" style="display: none; width: 0px;">
                                                        <div class="dc-header-row" style="top: 0px; height: 30px; width: 0px;"></div>
                                                    </div>
                                                    <div class="dc-pinned-right-header" style="display: none; width: 0px;">
                                                        <div class="dc-header-row" style="top: 0px; height: 30px; width: 0px;"></div>
                                                    </div>
                                                    <div class="dc-header-viewport" style="margin-left: 0px; margin-right: 0px;">
                                                        <div class="dc-header-container">
                                                            <div class="dc-header-row" style="top: 0px; height: 30px; width: 543px;">
                                                                <div class="dc-header-cell dc-header-cell-sortable" col-id="isSelected" style="width: 33px; left: 0px;">
                                                                    <div ref="eResize" class="dc-header-cell-resize"></div>
                                                                    <span class="dc-header-select-all dc-hidden" ref="cbSelectAll">
                                                                        <span class="dc-checkbox-checked dc-hidden">
                                                                            <span class="dc-icon dc-icon-checkbox-checked"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-unchecked">
                                                                            <span class="dc-icon dc-icon-checkbox-unchecked"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-indeterminate dc-hidden">
                                                                            <span class="dc-icon dc-icon-checkbox-indeterminate"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-label"></span>
                                                                    </span>
                                                                    <app-is-free>
                                                                        <!---->

                                                                        <!---->
                                                                        <div class="ui-grid-cell-contents">
                                                                            <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126554_NaN">
                                                                            <label for="IsSelectedCell1514773126554_NaN"></label>
                                                                        </div>

                                                                        <!---->


                                                                        <!---->
                                                                    </app-is-free>
                                                                </div>
                                                                <div class="dc-header-cell dc-header-cell-sortable" col-id="ID" style="width: 130px; left: 33px;">
                                                                    <div ref="eResize" class="dc-header-cell-resize"></div>
                                                                    <span class="dc-header-select-all dc-hidden" ref="cbSelectAll">
                                                                        <span class="dc-checkbox-checked dc-hidden">
                                                                            <span class="dc-icon dc-icon-checkbox-checked"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-unchecked">
                                                                            <span class="dc-icon dc-icon-checkbox-unchecked"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-indeterminate dc-hidden">
                                                                            <span class="dc-icon dc-icon-checkbox-indeterminate"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-label"></span>
                                                                    </span>
                                                                    <div class="dc-cell-label-container dc-header-cell-sorted-desc">
                                                                        <div ref="eLabel" class="dc-header-cell-label">
                                                                            <span ref="eText" class="dc-header-cell-text" role="columnheader">品號</span>
                                                                            <span ref="eFilter" class="dc-header-icon dc-filter-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-filter"></span>
                                                                            </span>
                                                                            <span ref="eSortOrder" class="dc-header-icon dc-sort-order dc-hidden" aria-hidden="true">1</span>
                                                                            <span ref="eSortAsc" class="dc-header-icon dc-sort-ascending-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-asc"></span>
                                                                            </span>
                                                                            <span ref="eSortDesc" class="dc-header-icon dc-sort-descending-icon" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-desc"></span>
                                                                            </span>
                                                                            <span ref="eSortNone" class="dc-header-icon dc-sort-none-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-none"></span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dc-header-cell dc-header-cell-sortable" col-id="Name" style="width: 300px; left: 163px;">
                                                                    <div ref="eResize" class="dc-header-cell-resize"></div><span class="dc-header-select-all dc-hidden" ref="cbSelectAll">  <span class="dc-checkbox-checked dc-hidden"><span class="dc-icon dc-icon-checkbox-checked"></span></span>
                                                                    <span class="dc-checkbox-unchecked"><span class="dc-icon dc-icon-checkbox-unchecked"></span></span> <span class="dc-checkbox-indeterminate dc-hidden"><span class="dc-icon dc-icon-checkbox-indeterminate"></span></span>
                                                                    <span class="dc-checkbox-label"></span></span>
                                                                    <div class="dc-cell-label-container dc-header-cell-sorted-none">
                                                                        <div ref="eLabel" class="dc-header-cell-label">
                                                                            <span ref="eText" class="dc-header-cell-text" role="columnheader">品名</span>
                                                                            <span ref="eFilter" class="dc-header-icon dc-filter-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-filter"></span>
                                                                            </span>
                                                                            <span ref="eSortOrder" class="dc-header-icon dc-sort-order dc-hidden" aria-hidden="true"></span>
                                                                            <span ref="eSortAsc" class="dc-header-icon dc-sort-ascending-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-asc"></span>
                                                                            </span>
                                                                            <span ref="eSortDesc" class="dc-header-icon dc-sort-descending-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-desc"></span>
                                                                            </span>
                                                                            <span ref="eSortNone" class="dc-header-icon dc-sort-none-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-none"></span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dc-header-cell dc-header-cell-sortable text-center" col-id="StockUnit" style="width: 80px; left: 463px;">
                                                                    <div ref="eResize" class="dc-header-cell-resize"></div>
                                                                    <span class="dc-header-select-all dc-hidden" ref="cbSelectAll">
                                                                        <span class="dc-checkbox-checked dc-hidden">
                                                                            <span class="dc-icon dc-icon-checkbox-checked"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-unchecked">
                                                                            <span class="dc-icon dc-icon-checkbox-unchecked"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-indeterminate dc-hidden">
                                                                            <span class="dc-icon dc-icon-checkbox-indeterminate"></span>
                                                                        </span>
                                                                        <span class="dc-checkbox-label"></span>
                                                                    </span>
                                                                    <div class="dc-cell-label-container dc-header-cell-sorted-none">
                                                                        <div ref="eLabel" class="dc-header-cell-label">
                                                                            <span ref="eText" class="dc-header-cell-text" role="columnheader">單位</span>
                                                                            <span ref="eFilter" class="dc-header-icon dc-filter-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-filter"></span>
                                                                            </span>
                                                                            <span ref="eSortOrder" class="dc-header-icon dc-sort-order dc-hidden" aria-hidden="true"></span>
                                                                            <span ref="eSortAsc" class="dc-header-icon dc-sort-ascending-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-asc"></span>
                                                                            </span>
                                                                            <span ref="eSortDesc" class="dc-header-icon dc-sort-descending-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-desc"></span>
                                                                            </span>
                                                                            <span ref="eSortNone" class="dc-header-icon dc-sort-none-icon dc-hidden" aria-hidden="true">
                                                                                <span class="dc-icon dc-icon-none"></span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dc-header-overlay" style="height: 30px; top: 0px;"></div>
                                                </div>
                                                <div class="dc-floating-top" style="top: 30px; height: 0px;">
                                                    <div class="dc-pinned-left-floating-top" style="width: 0px;"></div>
                                                    <div class="dc-pinned-right-floating-top" style="width: 0px;"></div>
                                                    <div class="dc-floating-top-viewport">
                                                        <div class="dc-floating-top-container" style="width: 543px;"></div>
                                                    </div>
                                                    <div class="dc-floating-top-full-width-container dc-hidden"></div>
                                                </div>
                                                <div class="dc-floating-bottom" style="height: 0px; top: 350px;">
                                                    <div class="dc-pinned-left-floating-bottom" style="width: 0px;"></div>
                                                    <div class="dc-pinned-right-floating-bottom" style="width: 0px;"></div>
                                                    <div class="dc-floating-bottom-viewport">
                                                        <div class="dc-floating-bottom-container" style="width: 543px;"></div>
                                                    </div>
                                                    <div class="dc-floating-bottom-full-width-container dc-hidden"></div>
                                                </div>
                                                <div class="dc-body" style="top: 30px; height: 320px;">
                                                    <div class="dc-pinned-left-cols-viewport" style="display: none; width: 0px; height: 320px;">
                                                        <div class="dc-pinned-left-cols-container" style="height: 750px; top: 0px; width: 0px;">
                                                            <div role="row" row-index="0" row-id="24" comp-id="623" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 0px;  "></div>
                                                            <div role="row" row-index="1" row-id="23" comp-id="628" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 30px;  "></div>
                                                            <div role="row" row-index="2" row-id="22" comp-id="633" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 60px;  "></div>
                                                            <div role="row" row-index="3" row-id="21" comp-id="638" class="dc-row dc-row-odd dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 90px;  "></div>
                                                            <div role="row" row-index="20" row-id="4" comp-id="475" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 600px;"></div>
                                                            <div role="row" row-index="19" row-id="5" comp-id="477" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 570px;"></div>
                                                            <div role="row" row-index="18" row-id="6" comp-id="479" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 540px;"></div>
                                                            <div role="row" row-index="17" row-id="7" comp-id="481" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 510px;"></div>
                                                            <div role="row" row-index="16" row-id="8" comp-id="483" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 480px;"></div>
                                                            <div role="row" row-index="15" row-id="9" comp-id="485" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 450px;"></div>
                                                            <div role="row" row-index="14" row-id="10" comp-id="487" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 420px;"></div>
                                                            <div role="row" row-index="13" row-id="11" comp-id="489" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 390px;"></div>
                                                            <div role="row" row-index="12" row-id="12" comp-id="491" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 360px;  "></div>
                                                            <div role="row" row-index="11" row-id="13" comp-id="493" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 330px;"></div>
                                                            <div role="row" row-index="10" row-id="14" comp-id="495" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 300px;"></div>
                                                            <div role="row" row-index="9" row-id="15" comp-id="497" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 270px;"></div>
                                                            <div role="row" row-index="8" row-id="16" comp-id="499" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 240px;"></div>
                                                            <div role="row" row-index="7" row-id="17" comp-id="501" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 210px;"></div>
                                                            <div role="row" row-index="6" row-id="18" comp-id="503" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 180px;"></div>
                                                            <div role="row" row-index="5" row-id="19" comp-id="505" class="dc-row dc-row-odd dc-row-no-animation dc-row-level-0 dc-row-focus" style="height: 30px; top: 150px;"></div>
                                                            <div role="row" row-index="4" row-id="20" comp-id="507" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 120px;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="dc-pinned-right-cols-viewport" style="display: none; width: 0px; height: 320px;">
                                                        <div class="dc-pinned-right-cols-container" style="height: 750px; width: 0px;">
                                                            <div role="row" row-index="0" row-id="24" comp-id="623" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 0px;  "></div>
                                                            <div role="row" row-index="1" row-id="23" comp-id="628" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 30px;  "></div>
                                                            <div role="row" row-index="2" row-id="22" comp-id="633" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 60px;  "></div>
                                                            <div role="row" row-index="3" row-id="21" comp-id="638" class="dc-row dc-row-odd dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 90px;  "></div>
                                                            <div role="row" row-index="20" row-id="4" comp-id="475" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 600px;"></div>
                                                            <div role="row" row-index="19" row-id="5" comp-id="477" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 570px;"></div>
                                                            <div role="row" row-index="18" row-id="6" comp-id="479" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 540px;"></div>
                                                            <div role="row" row-index="17" row-id="7" comp-id="481" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 510px;"></div>
                                                            <div role="row" row-index="16" row-id="8" comp-id="483" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 480px;"></div>
                                                            <div role="row" row-index="15" row-id="9" comp-id="485" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 450px;"></div>
                                                            <div role="row" row-index="14" row-id="10" comp-id="487" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 420px;"></div>
                                                            <div role="row" row-index="13" row-id="11" comp-id="489" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 390px;"></div>
                                                            <div role="row" row-index="12" row-id="12" comp-id="491" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 360px;  "></div>
                                                            <div role="row" row-index="11" row-id="13" comp-id="493" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 330px;"></div>
                                                            <div role="row" row-index="10" row-id="14" comp-id="495" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 300px;"></div>
                                                            <div role="row" row-index="9" row-id="15" comp-id="497" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 270px;"></div>
                                                            <div role="row" row-index="8" row-id="16" comp-id="499" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 240px;"></div>
                                                            <div role="row" row-index="7" row-id="17" comp-id="501" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 210px;"></div>
                                                            <div role="row" row-index="6" row-id="18" comp-id="503" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 180px;"></div>
                                                            <div role="row" row-index="5" row-id="19" comp-id="505" class="dc-row dc-row-odd dc-row-no-animation dc-row-level-0 dc-row-focus" style="height: 30px; top: 150px;"></div>
                                                            <div role="row" row-index="4" row-id="20" comp-id="507" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 120px;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="dc-body-viewport-wrapper" style="margin-left: 0px; margin-right: 0px;">
                                                        <div class="dc-body-viewport" style="overflow-y: auto;">
                                                            <div class="dc-body-container" style="height: 750px; top: 0px; width: 837px;">
                                                                <div role="row" row-index="0" row-id="24" comp-id="623" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 0px;  ">
                                                                    <div tabindex="-1" role="gridcell" comp-id="799" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">次</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="624" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773170776_1">
                                                                                <label for="IsSelectedCell1514773170776_1"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="625" col-id="ID" class="dc-cell dc-cell-not-inline-editing text-left dc-cell-value dc-cell-no-focus" style="width: 130px; left: 33px; ">Z2001001-01</div>
                                                                    
                                                                    <!-- popover彈出式提示視窗 -->
                                                                    <div tabindex="-1" role="gridcell" comp-id="626" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; "> 
                                                                        <a href="#" role="button" data-toggle="popover" data-original-title="Popover title" data-container="#selectproduct" data-html="true" data-content="<img src='http://localhost/upload/product/5c870692eb89e8a9a6f7e9b528ddbb3d.png'>">服務費</a>
                                                                    </div>
                                                                    
                                                                </div>
                                                                <div role="row" row-index="1" row-id="23" comp-id="628" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 30px;  ">
                                                                    <div tabindex="-1" role="gridcell" comp-id="800" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">次</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="629" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773170777_2">
                                                                                <label for="IsSelectedCell1514773170777_2"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="630" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">Z1001001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="631" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">運費</div>
                                                                </div>
                                                                <div role="row" row-index="2" row-id="22" comp-id="633" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 60px;  ">
                                                                    <div tabindex="-1" role="gridcell" comp-id="801" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">包</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="634" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773170779_3">
                                                                                <label for="IsSelectedCell1514773170779_3"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="635" col-id="ID" class="dc-cell dc-cell-not-inline-editing text-left dc-cell-value dc-cell-no-focus" style="width: 130px; left: 33px; ">W1001002-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="636" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">一家人安全帽禮盒</div>
                                                                </div>
                                                                <div role="row" row-index="3" row-id="21" comp-id="638" class="dc-row dc-row-odd dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 90px;  ">
                                                                    <div tabindex="-1" role="gridcell" comp-id="802" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">包</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="639" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773170780_4">
                                                                                <label for="IsSelectedCell1514773170780_4"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="640" col-id="ID" class="dc-cell dc-cell-not-inline-editing text-left dc-cell-value dc-cell-no-focus" style="width: 130px; left: 33px; ">W1001001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="641" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">騎士包(全罩安全帽、內襯、鏡片、手套、雨衣、雨鞋)</div>
                                                                </div>
                                                                <div role="row" row-index="20" row-id="4" comp-id="475" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 600px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="782" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">頂</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="532" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">A1005001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="533" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">全罩安全帽</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="476" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126888_5">
                                                                                <label for="IsSelectedCell1514773126888_5"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="19" row-id="5" comp-id="477" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 570px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="783" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">頂</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="535" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">A1006001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="536" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">流線安全帽</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="478" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126890_6">
                                                                                <label for="IsSelectedCell1514773126890_6"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="18" row-id="6" comp-id="479" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 540px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="784" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">件</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="538" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">A2001001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="539" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">內襯</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="480" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126891_7">
                                                                                <label for="IsSelectedCell1514773126891_7"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="17" row-id="7" comp-id="481" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 510px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="785" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">件</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="541" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">A2001002-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="542" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">鏡片</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="482" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126892_8">
                                                                                <label for="IsSelectedCell1514773126892_8"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="16" row-id="8" comp-id="483" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 480px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="786" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">件</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="544" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">B1001001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="545" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">手套</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="484" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126893_9">
                                                                                <label for="IsSelectedCell1514773126893_9"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="15" row-id="9" comp-id="485" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 450px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="787" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">件</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="547" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">B1001002-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="548" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">雨衣</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="486" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126894_10">
                                                                                <label for="IsSelectedCell1514773126894_10"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="14" row-id="10" comp-id="487" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 420px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="788" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">件</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="550" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">B1001003-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="551" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">雨鞋</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="488" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126895_11">
                                                                                <label for="IsSelectedCell1514773126895_11"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="13" row-id="11" comp-id="489" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 390px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="789" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">個</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="553" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">C-1001001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="554" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">造型毛巾-小蜜蜂</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="490" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126896_12">
                                                                                <label for="IsSelectedCell1514773126896_12"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="12" row-id="12" comp-id="491" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 360px;  ">
                                                                    <div tabindex="-1" role="gridcell" comp-id="790" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">個</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="556" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">C-1001002-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="557" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">造型毛巾-鳳梨</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="492" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126897_13">
                                                                                <label for="IsSelectedCell1514773126897_13"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="11" row-id="13" comp-id="493" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 330px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="791" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">個</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="559" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">C-1001003-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="560" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">手工貓咪吊飾</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="494" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126898_14">
                                                                                <label for="IsSelectedCell1514773126898_14"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="10" row-id="14" comp-id="495" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 300px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="792" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">套</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="562" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">C-1001004-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="563" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">貓咪公仔六件組</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="496" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126899_15">
                                                                                <label for="IsSelectedCell1514773126899_15"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="9" row-id="15" comp-id="497" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 270px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="793" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">個</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="565" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">D-1001001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="566" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">神奇音箱</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="498" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126900_16">
                                                                                <label for="IsSelectedCell1514773126900_16"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="8" row-id="16" comp-id="499" class="dc-row dc-row-no-focus dc-row-even dc-row-no-animation dc-row-level-0" style="height: 30px; top: 240px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="794" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">件</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="568" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">D-1001002-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="569" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">Power DVD</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="500" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126901_17">
                                                                                <label for="IsSelectedCell1514773126901_17"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="7" row-id="17" comp-id="501" class="dc-row dc-row-no-focus dc-row-odd dc-row-no-animation dc-row-level-0" style="height: 30px; top: 210px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="795" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">本</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="571" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">E-1001001-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="572" col-id="Name" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 300px; left: 163px; ">字裡行間(單)</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="502" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-center dc-cell-value" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126902_18">
                                                                                <label for="IsSelectedCell1514773126902_18"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="6" row-id="18" comp-id="503" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 180px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="796" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">套</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="574" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">E-1001002-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="575" col-id="Name" class="dc-cell dc-cell-not-inline-editing text-left dc-cell-value dc-cell-no-focus" style="width: 300px; left: 163px; ">合購-得獎書</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="504" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing text-center dc-cell-value dc-cell-no-focus" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126904_19">
                                                                                <label for="IsSelectedCell1514773126904_19"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="5" row-id="19" comp-id="505" class="dc-row dc-row-odd dc-row-no-animation dc-row-level-0 dc-row-focus" style="height: 30px; top: 150px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="797" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">本</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="577" col-id="ID" class="dc-cell dc-cell-not-inline-editing text-left dc-cell-value dc-cell-no-focus" style="width: 130px; left: 33px; ">E-1001003-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="578" col-id="Name" class="dc-cell dc-cell-not-inline-editing text-left dc-cell-value dc-cell-no-focus" style="width: 300px; left: 163px; ">查令十字路84號</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="506" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing text-center dc-cell-value dc-cell-focus" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126905_20">
                                                                                <label for="IsSelectedCell1514773126905_20"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                                <div role="row" row-index="4" row-id="20" comp-id="507" class="dc-row dc-row-even dc-row-no-animation dc-row-level-0 dc-row-no-focus" style="height: 30px; top: 120px;">
                                                                    <div tabindex="-1" role="gridcell" comp-id="798" col-id="StockUnit" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 80px; left: 463px; ">本</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="580" col-id="ID" class="dc-cell dc-cell-not-inline-editing dc-cell-no-focus text-left dc-cell-value" style="width: 130px; left: 33px; ">E-1001004-01</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="581" col-id="Name" class="dc-cell dc-cell-not-inline-editing text-left dc-cell-value dc-cell-no-focus" style="width: 300px; left: 163px; ">誰在暗中眨眼睛</div>
                                                                    <div tabindex="-1" role="gridcell" comp-id="508" col-id="isSelected" class="dc-cell dc-cell-not-inline-editing text-center dc-cell-value dc-cell-no-focus" style="width: 33px; left: 0px; ">
                                                                        <app-is-free>
                                                                            <!---->

                                                                            <!---->

                                                                            <!---->
                                                                            <div class="ui-grid-cell-contents">
                                                                                <input class="magic-checkbox blue" name="layout" type="checkbox" id="IsSelectedCell1514773126906_21">
                                                                                <label for="IsSelectedCell1514773126906_21"></label>
                                                                            </div>


                                                                            <!---->
                                                                        </app-is-free>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dc-full-width-viewport dc-hidden" style="border-right: 17px solid transparent;">
                                                        <div class="dc-full-width-container" style="height: 750px; top: 0px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dc-bl-overlay" ref="overlay" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="dc-bl-overlay" ref="overlay"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="select2-results">
                    <span>已選擇：25 </span>
                </div>
            </div>
            <div class='modal-footer'>
                <button class="btn btn-success margin-5"> 加入 <i class="fa fa-check"></i> </button>
                <button class="btn btn-danger margin-5" data-dismiss="modal" aria-hidden="true"> 取消 <i class="fa fa fa-times"></i> </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>