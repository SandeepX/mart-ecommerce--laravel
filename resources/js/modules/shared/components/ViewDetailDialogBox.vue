<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container pa-0" style="width: 600px !important;">
                    <div class="modal-body ma-0">
                        <slot v-if="action==='view_add_purchase_schemeable_product'" name="body">
                            <div class="row pa-3 mb-3 mx-1" style="background-color: #3c8dbc">
                                <div class="subtitle-2" style="font-weight: bold!important;color: white !important;">
                                    Schemeable Product
                                </div>
                            </div>
                            <ViewSchemeProductWhileCreatingPurchaseOrder
                                :detail_to_show="detail_to_show"></ViewSchemeProductWhileCreatingPurchaseOrder>
                        </slot>
                        <slot v-if="action==='view_scheme_for_purchase_order_detail'">
                            <div class="row pa-3 mb-3 mx-1" style="background-color: #3c8dbc">
                                <div class="subtitle-2" style="font-weight: bold!important;color: white !important;">
                                    {{ title_name['product_name'] }}<span v-if="title_name['product_variant_code']"> - {{
                                        title_name['product_variant_name']
                                    }}</span>
                                </div>
                            </div>
                            <SchemeProductDetail :title_name="title_name" :detail_to_show="detail_to_show"></SchemeProductDetail>
                        </slot>
                        <slot v-if="action==='show_remarks'">
                            <div class="row pa-3 mb-3 mx-1" style="background-color: #3c8dbc">
                                <div class="subtitle-2" style="font-weight: bold!important;color: white !important;">
                                    Remarks
                                </div>
                            </div>
                            <div v-html="detail_to_show" class="row">remarks ko detail</div>
                        </slot>
                    </div>
                    <div class="modal-footer text-right py-2 mr-4">
                        <slot name="footer">
                            <button style="background-color: #3c8dbc!important;color: white !important;"
                                    class="modal-default-button" @click="$emit('close')">
                                OK
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: "ViewDetailDialogBox",
    components: {
        ViewSchemeProductWhileCreatingPurchaseOrder: () => import("@warehouse~components/dialog-box-detail/purchase-order/ViewSchemeProductWhileCreatingPurchaseOrder"),
        SchemeProductDetail: () => import("@warehouse~components/dialog-box-detail/purchase-order/SchemeProductDetail")
    },
    props: ['detail_to_show', 'action', 'title_name'],
}
</script>

<style scoped>
.subtitle-2 {
    font-size: 14px !important;
    font-weight: 500 !important;
}
</style>
