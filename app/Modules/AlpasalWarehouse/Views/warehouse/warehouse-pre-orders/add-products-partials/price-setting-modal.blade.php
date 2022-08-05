<div class="modal fade" id="priceSettingModal" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <form id="priceSettingForm">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                  {{--  Price Setting History of {{$productDetail['product']['product_name']}}
                    {{$productDetail['productVariant'] ? $productDetail['productVariant']['product_variant_name'] : ''}}--}}
                </h4>
            </div>
            <div class="modal-body">
                <div id="showFlashMessageCreateModal"></div>
                <div id="price-setting-form-modal">
                </div>
                {{-- @include('AlpasalWarehouse::warehouse.warehouse-products.show-partials.price-history-table')--}}
            </div>
            <div class="modal-footer">
                <button type="submit" id="priceSettingSubmitBtn" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
