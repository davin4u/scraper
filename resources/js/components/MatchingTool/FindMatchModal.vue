<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <Modal :show="showModal" :modal-id="'find-match-modal'" @close="onClose">
        <template v-slot:title>Find a match</template>

        <div>Modal content</div>
    </Modal>
</template>

<script>
    import Modal from "../Base/Layout/Modal";

    export default {
        name: "FindMatchModal",

        components: {Modal},

        props: ['product'],

        computed: {
            showModal() {
                return !_.isNull(this.product) && !_.isUndefined(this.product);
            }
        },

        methods: {
            onClose() {
                this.$emit('close');
            }
        }
    }
</script>
