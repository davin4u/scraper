<template>
    <div :id="modalId" class="modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <slot name="title"></slot>
                    </h5>

                    <button @click.prevent="close" class="close">&times;</button>
                </div>

                <div class="modal-body">
                    <slot></slot>
                </div>

                <div class="modal-footer">
                    <button @click.prevent="close" class="btn btn-danger">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Modal",

        props: {
            modalId: { required: true },
            show: { default: false }
        },

        methods: {
            close() {
                this.$emit('close');
            },
        },

        mounted() {
            $('#' + this.modalId).modal({
                show: this.show
            });

            $('#' + this.modalId).on('hide.bs.modal', (event) => {
                this.close();
            });
        },

        watch: {
            show: function (newValue, oldValue) {
                if (newValue === false) {
                    $('#' + this.modalId).modal('hide');
                }
                else {
                    $('#' + this.modalId).modal('show');
                }
            }
        }
    }
</script>
