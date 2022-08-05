<template>
<div>
    <div class="row">
        <div class="col-md-4" style="height: 140px">
            <img v-if="detail_to_show['product_image']" height="130"
                 :src="detail_to_show['product_image']">
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
                    {{ detail_to_show['product_name'] }}
                </div>
            </div>
            <div class="row mb-4 subtitle-2">
                <div class="col-lg-5">Estimated Value :</div>
                <div class="col-lg-7">
                    Rs. {{ detail_to_show['estimated_value'] }}
                </div>
            </div>
            <div v-if="detail_to_show['brand_name']" class="row mb-4 subtitle-2">
                <div class="col-lg-5">Brand Name :</div>
                <div class="col-lg-7">
                    {{ detail_to_show['brand_name'] }}
                </div>
            </div>
            <div v-if="detail_to_show['category_name']" class="row mb-4 subtitle-2">
                <div class="col-lg-5">Category Name :</div>
                <div class="col-lg-7">
                    {{ detail_to_show['category_name'] }}
                </div>
            </div>
            <div class="row mb-4 subtitle-2">
                <div class="col-lg-5">Taxable :</div>
                <div class="col-lg-7">
                    <div v-if="detail_to_show['is_taxable']">
                        <button type="button" class="btn btn-info btn-xs">Taxable
                        </button>
                    </div>
                    <div v-else>
                        <button type="button" class="btn btn-danger btn-xs">Non-Taxable
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="detail_to_show['product_description']" class="row mb-4 subtitle-2">
                <div class="col-lg-5">Description :</div>
                <div class="col-lg-7">
                    <span v-html="detail_to_show['product_description']"></span>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "ViewSchemeProductWhileCreatingPurchaseOrder",
    props:['detail_to_show']
}
</script>

<style scoped>

</style>
