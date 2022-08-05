<template>
    <div>
        <div class="row">
            <!--            <img class="my-4" height="100" src="@images/offer.jpg">-->
            <div v-if="detail_to_show['scheme_type']==='item'" class="col-lg-12">
                <div v-if="detail_to_show['scheme_detail']['scheme_type']==='same'"
                     class="text-center subtitle-1 py-8 font-weight-bold">
                    Buy {{ detail_to_show['scheme_detail']['scheme_item_quantity'] }}
                    {{ detail_to_show['scheme_detail']['scheme_packaging'] }} & Get
                    {{ detail_to_show['scheme_detail']['scheme_item_quantity'] }}
                    {{ detail_to_show['scheme_detail']['scheme_packaging'] }} Free
                </div>
                <div v-if="detail_to_show['scheme_detail']['scheme_type']==='different'">
                    Buy {{ title_name['product_name'] }} & Get
                    {{ detail_to_show['scheme_detail'['scheme_product_name']] }} Free
                </div>
                <div class="text-center">
                    <div>Product Name: {{ detail_to_show['scheme_detail']['scheme_product_name'] }}</div>
                    <div v-if="detail_to_show['scheme_detail']['scheme_product_brand_name']">Brand: {{ detail_to_show['scheme_detail']['scheme_product_brand_name'] }}</div>
                    <div v-if="detail_to_show['scheme_detail']['scheme_product_category_name'] ">Category: {{ detail_to_show['scheme_detail']['scheme_product_category_name'] }}</div>
                    <div>Taxable:
                        <span v-if=" detail_to_show['scheme_detail']['scheme_product_is_taxable'] ">Taxable</span>
                        <span v-else>Non Taxable</span>
                    </div>
                    <div v-if="detail_to_show['scheme_detail']['scheme_product_description']">Description: <span v-html="detail_to_show['scheme_detail']['scheme_product_description']"></span></div>
                    <div>Estimated Value : <span>{{detail_to_show['scheme_detail']['scheme_product_estimated_value']}}</span></div>
                </div>
            </div>
            <div class="text-center subtitle-1 py-8 font-weight-bold" v-else><span
                v-if="detail_to_show['scheme_detail']['discount_type']==='f'"> Rs. </span>{{
                    detail_to_show['scheme_detail']['discount_value']
                }}<span
                    v-if="detail_to_show['scheme_detail']['discount_type']==='p'"> % </span> discount on
                {{ detail_to_show['scheme_detail']['target_quantity'] }} quantity purchased.
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SchemeProductDetail",
    props: ['detail_to_show', 'title_name']
}
</script>

<style scoped>

</style>
