<template>
    <div class="alert alert-danger alert-dismissible fade in">
                        <span v-for="(error,index) in errors" :key="index"
                              class="glyphicon glyphicon-info-sign mb-1"
                              style="font-size: 18px;">{{ error['message'] }}</span>
    </div>
</template>

<script>
export default {
    name: "ErrorMessage422",
    props: ['errors']
}
</script>

<style scoped>

</style>
