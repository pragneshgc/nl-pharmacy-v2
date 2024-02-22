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

<script>

 import { ref, onMounted, onUnmounted } from 'vue';

export default {
  props: ['modalName', 'modalClass'],
  setup(props) {
    const show = ref({
      modal: false
    });
    const loading = ref(true);

    const close = () => {
      show.value.modal = false;
    };

    const save = () => {
      show.value.modal = false;
    };

    const openModal = (name) => {
      if (name === props.modalName) {
        show.value.modal = true;
      }
    };

    const closeModal = (name) => {
      if (name === props.modalName) {
        show.value.modal = false;
      }
    };

    const closeAllModals = () => {
      show.value.modal = false;
    };

    onMounted(() => {
      const modalOpenHandler = (name) => {
        openModal(name);
      };

      const modalCloseHandler = (name) => {
        closeModal(name);
      };

      const modalCloseAllHandler = () => {
        closeAllModals();
      };

      // Register event listeners
      window.addEventListener('modal.open', modalOpenHandler);
      window.addEventListener('modal.close', modalCloseHandler);
      window.addEventListener('modal.close.all', modalCloseAllHandler);

      // Cleanup on component unmount
      onUnmounted(() => {
        window.removeEventListener('modal.open', modalOpenHandler);
        window.removeEventListener('modal.close', modalCloseHandler);
        window.removeEventListener('modal.close.all', modalCloseAllHandler);
      });
    });

    return {
      show,
      loading,
      close,
      save
    };
  }
};

</script>
