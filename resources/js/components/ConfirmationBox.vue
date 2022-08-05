<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container pa-0" style="width: 600px !important;">
                    <div class="modal-body ma-0">
                        <slot name="body">
                            <div class="row pa-3">Are you sure you want to {{ message }} ? Once it is done, it cannot be
                                undone.
                            </div>
                        </slot>
                    </div>

                    <div class="modal-footer text-right py-2 mr-4">
                        <slot name="footer">
                            <button style="background-color: #3c8dbc!important;color: white !important;"
                                    class="modal-default-button ml-3" @click="$emit('confirm')">
                                Confirm
                            </button>
                            <button style="background-color: #3c8dbc!important;color: white !important;"
                                    class="modal-default-button" @click="$emit('close')">
                                Close
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
    name: "ConfirmationBox",
    props: ['message'],
}
</script>

<style scoped>

</style>
