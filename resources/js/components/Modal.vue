<template>
    <transition name="fade">
        <div class="esa-modal" v-if="show.modal">
            <div class="backdrop" @click="close()">
            </div>
            <div class="modal" :class="modalClass">
                <div class="modal-header">
                    <slot name="header"></slot>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div class="modal-footer">
                    <button @click="close()" class="btn btnSize01 secondaryBtn bigButton">Cancel</button>
                    <slot name="footer"></slot>
                </div>
                <!-- <div v-if="loading" class="loader" style="">Loading...</div> -->
                <span class="close" @click="close()">X</span>
            </div>
        </div>
    </transition>
</template>

<script setup>
    import { ref, onMounted, onUnmounted, on } from 'vue';

    const modalName = props.modalName;
    const modalClass = props.modalClass;
    const show = ref(false);
    const loading = ref(true);

    const close = () => {
        show.value = false;
    };

    const save = () => {
        show.value = false;
    };

    onMounted(() => {
        const openHandler = (name) => {
            if (name === modalName) {
                show.value = true;
            }
        };

        const closeHandler = (name) => {
            if (name === modalName) {
                show.value = false;
            }
        };

        const closeAllHandler = () => {
            show.value = false;
        };

        on('modal.open', openHandler);
        on('modal.close', closeHandler);
        on('modal.close.all', closeAllHandler);
    });

    onUnmounted(() => {
        // Clean up event listeners if necessary
    });
</script>