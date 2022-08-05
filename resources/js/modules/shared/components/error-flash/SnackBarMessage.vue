<template>
    <div class="alert fade in" :class="success?'alert-success':'alert-danger'"
         style="position:fixed !important;bottom:0 !important;right:10px !important">
        <span class="glyphicon glyphicon-info-sign mr-3" style="font-size: 18px;"></span>{{
            message
        }}
    </div>
</template>

<script>
export default {
    name: "SnackBarMessage",
    props: ["success","message"]
}
</script>

<style scoped>

</style>
