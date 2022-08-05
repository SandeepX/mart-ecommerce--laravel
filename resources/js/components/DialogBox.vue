<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container pa-0" style="width: 600px !important;">
                    <div class="modal-body ma-0">
                        <slot name="body">
                            <div class="row pa-3 mb-3 mx-1" style="background-color: #3c8dbc">
                                <div class="subtitle-2" style="font-weight: bold!important;color: white !important;">
                                    Schemeable Product
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4" style="height: 140px">
                                    <img v-if="offered_scheme['product_image']" height="130"
                                         :src="offered_scheme['product_image']">
                                    <div v-else style="background-color: #e5e5e5 !important;height: 130px;">
                                        <div class="small"
                                             style="position: absolute;top: 40%;left:35%;font-weight: 600;">No Preview
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row mb-4 subtitle-2">
                                        <div class="col-lg-5">Product Name :</div>
                                        <div class="col-lg-7">
                                            {{ offered_scheme['product_name'] }}
                                        </div>
                                    </div>
                                    <div class="row mb-4 subtitle-2">
                                        <div class="col-lg-5">Estimated Value :</div>
                                        <div class="col-lg-7">
                                            Rs. {{ offered_scheme['estimated_value'] }}
                                        </div>
                                    </div>
                                    <div v-if="offered_scheme['brand_name']" class="row mb-4 subtitle-2">
                                        <div class="col-lg-5">Brand Name :</div>
                                        <div class="col-lg-7">
                                            {{ offered_scheme['brand_name'] }}
                                        </div>
                                    </div>
                                    <div v-if="offered_scheme['category_name']" class="row mb-4 subtitle-2">
                                        <div class="col-lg-5">Category Name :</div>
                                        <div class="col-lg-7">
                                            {{ offered_scheme['category_name'] }}
                                        </div>
                                    </div>
                                    <div class="row mb-4 subtitle-2">
                                        <div class="col-lg-5">Taxable :</div>
                                        <div class="col-lg-7">
                                            <div v-if="offered_scheme['is_taxable']">
                                                <button type="button" class="btn btn-info btn-xs">Taxable
                                                </button>
                                            </div>
                                            <div v-else>
                                                <button type="button" class="btn btn-danger btn-xs">Non-Taxable
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="offered_scheme['product_description']" class="row mb-4 subtitle-2">
                                        <div class="col-lg-5">Description :</div>
                                        <div class="col-lg-7">
                                            <span v-html="offered_scheme['product_description']"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    name: "DialogBox",
    props: ['offered_scheme'],
}
</script>

<style scoped>
.subtitle-2 {
    font-size: 14px !important;
    font-weight: 500 !important;
}
</style>
