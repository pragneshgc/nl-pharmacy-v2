<template>
    <transition name="fade">
        <div class="esa-modal" v-if="show.modal">
            <div v-if="backdrop" class="backdrop" @click="close()"></div>
            <div class="modal" id="draggable-div-note">
                <div class="modal-header draggable-div-header" id="draggable-div-header-note">
                    <h3>Create Note</h3>
                </div>
                <div class="modal-body">
                    <form @submit="submit()">
                        <label for="note-select">Note type <small class="danger">(required)</small></label>
                        <select v-model="form.Type" name="note-select" class="table-dropdown mb-10">
                            <option value="0" disabled>Please select a type</option>
                            <option v-for="option in options" :value="option.value" :key="option.value"
                                v-html="option.name"></option>
                        </select>

                        <input v-if="[1, 3].includes(form.Type) /* form.Type == 3 */ && !preimport"
                            :class="{ 'unchecked': !form.Alert }" :checked="form.Alert" type="checkbox" name="specific">
                        <label title="Show in a popup to other users when they view this prescription."
                            v-if="[1, 3].includes(form.Type) /* form.Type == 3 */ && !preimport" class="mb-10"
                            @click="form.Alert = !form.Alert" for="specific"><b>Alert other users</b></label>

                        <!-- <div v-if="error.ReferenceNumber" class="infoBox warning">This Reference Number is already in the system!</div> -->
                        <label v-if="preimport && !editing && form.Type != 4" for="reference-number">Reference Number <small
                                class="danger">(required)</small></label>
                        <label v-if="preimport && !editing && form.Type == 4" for="reference-number">Subscription ID <small
                                class="danger">(required)</small></label>
                        <input class="form-control tBoxSize02 mb-10" name="reference-number"
                            v-if="preimport && !editing && form.Type != 4" v-model="form.ReferenceNumber" type="text">
                        <input class="form-control tBoxSize02 mb-10" name="reference-number"
                            v-if="preimport && !editing && form.Type == 4" v-model="form.Subscription" type="text">

                        <label for="message-text">Note<small class="danger">(required)</small></label>
                        <vue-editor v-model="form.Note" />

                        <div class="infoBox warning note-error" v-if="error != ''">
                            <p>{{ this.error }}</p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button @click="close()" class="btn btnSize01 secondaryBtn bigButton">
                        Cancel
                    </button>
                    <button v-if="form.Type != 0 && form.Note != '' && !editing" @click="submit()"
                        class="btn btnSize01 primaryBtn">
                        Save
                    </button>
                    <button v-else-if="editing" @click="update()" class="btn btnSize01 primaryBtn">
                        Update
                    </button>
                </div>
                <!-- <div v-if="loading" class="loader" style="">Loading...</div> -->
                <span class="backdrop-toggle" @click="toggleBackdrop()" title="Unfocus the modal"><i
                        class="fa fa-clone"></i></span>
                <span class="close" @click="close()" title="Close the modal"><i class="fa fa-close"></i></span>
            </div>
        </div>
    </transition>
</template>


<script>

import { ref, reactive, watch, onMounted } from 'vue';
import Modal from './Modal.vue';
import Error from '../mixins/errors';
import { VueEditor } from "vue2-editor";

export default {
  components: {
    VueEditor,
    Modal
  },
  props: ['orderID'],
  setup(props) {
    const show = reactive({ modal: false });
    const form = reactive({
      options: [
        { name: 'Patient Note <span style="font-style: italic;">(Patient specific notes)</span>', value: 1 },
        { name: 'Query Note', value: 2 },
        { name: 'Order Note <span style="font-size: 13px;">(Order specific notes)</span>', value: 3 },
      ],
      Type: 0,
      Note: '',
      Alert: false,
      ReferenceNumber: false,
      Subscription: false,
    });

    const userInfo = reactive(userInfo);
    const error = ref('');
    const editing = ref(false);
    const preimport = ref(false);
    const backdrop = ref(true);
    const dragEventTriggered = ref(false);

    const options = computed(() => {
      if (!preimport.value) {
        if (userInfo.role == 30) {
          return form.options;
        } else {
          return [
            { name: 'Order Note <span style="font-size: 13px;">(Order specific notes)</span>', value: 3 },
            { name: 'Patient Note <span style="font-size: 13px;">(Patient specific notes)</span>', value: 1 },
          ]
        }
      } else {
        return [
          { name: 'Subscription Note', value: 4 },
          { name: 'Order Note', value: 3 },
        ]
      }
    });

    const reset = () => {
      form.Type = 0;
      form.Note = '';
      form.PrescriptionID = 0;
      form.NoteID = 0;
      form.Alert = false;
      form.ReferenceNumber = '';
      form.Subscription = '';
      error.value = '';
      preimport.value = false;
      editing.value = false;
    };

    const close = () => {
      reset();
      show.modal = false;
    };

    const toggleBackdrop = () => {
      backdrop.value = !backdrop.value;
    };

    const submit = () => {
      if (form.Type == 0 || form.Note == '') {
        error.value = 'Please fill all of the required fields';
        return;
      } else {
        error.value = '';
      }

      if (form.Type == 3) {
        form.OrderSpecific = true;
      }

      if (!preimport.value) {
        form.PrescriptionID = props.orderID;
      } else {
        form.PrescriptionID = null;
      }

      axios.post('/note', form)
        .then((response) => {
          postSuccess(response.data.message);
          $emit('orderupdate');
          $emit('alertupdate');
          reset();
        })
        .catch((error) => {
          postError(error.response.data.message);
        })
        .finally(() => {
          show.modal = false;
        });
    };

    const update = (id) => {
      if (form.Type == 0 || form.Note == '') {
        error.value = 'Please fill all of the required fields';
        return;
      } else {
        error.value = '';
      }

      if (form.Type == 3) {
        form.OrderSpecific = true;
      }

      form.NoteID = editing.value.NoteID;

      axios.patch(`/note/${form.NoteID}`, form)
        .then((response) => {
          postSuccess(response.data.message);
          $emit('orderupdate');
          $emit('alertupdate');
          reset();
        })
        .catch((error) => {
          postError(error.response.data.message);
        })
        .finally(() => {
          show.modal = false;
        });
    };

    watch(formReferenceNumber, (newValue, oldValue) => {
      if (newValue) {
        axios.get(`/order/${newValue}/exists`)
          .then((response) => {
            if (response.data.data) {
              error.value = 'This Reference Number is already in the system!';
            } else {
              error.value = '';
            }
          })
          .catch((error) => {
            error.value = '';
          });
      }
    }, { debounce: 500 });

    watch(showModal, (newValue, oldValue) => {
      setTimeout(() => {
        if (newValue && !dragEventTriggered.value && document.getElementById("draggable-div-note")) {
          dragElement(document.getElementById("draggable-div-note"));
          dragEventTriggered.value = true;

          function dragElement(elmnt) {
            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

            if (document.getElementById("draggable-div-header-note")) {
              document.getElementById("draggable-div-header-note").onmousedown = dragMouseDown;
            } else {
              elmnt.onmousedown = dragMouseDown;
            }

            function dragMouseDown(e) {
              e = e || window.event;
              e.preventDefault();
              pos3 = e.clientX;
              pos4 = e.clientY;
              document.onmouseup = closeDragElement;
              document.onmousemove = elementDrag;
            }

            function elementDrag(e) {
              e = e || window.event;
              e.preventDefault();
              pos1 = pos3 - e.clientX;
              pos2 = pos4 - e.clientY;
              pos3 = e.clientX;
              pos4 = e.clientY;
              elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
              elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
              document.onmouseup = null;
              document.onmousemove = null;
            }
          }
        } else {
          dragEventTriggered.value = false;
          backdrop.value = true;
        }
      }, 300);
    });

    onMounted(() => {
      $root.$on('modal.open', (name, note) => {
        if (name == modalName) {
          show.modal = true;
        }

        if (note && note != 'preimport' && note.Type != 3 && note.Type != 4) {
          editing.value = note;
          form.Type = note.Type;
          form.Note = note.Note;
          form.Alert = note.Alert == 0 ? false : true;
          console.log('the note is this');
        } else if (note && (note.Type == 3 || note.Type == 4)) {
          editing.value = note;
          form.Type = note.Type;
          form.Note = note.Note;
          form.Alert = true;
          preimport.value = true;
          form.ReferenceNumber = note.ReferenceNumber;
        } else if (note == 'preimport') {
          form.Type = 4;
          form.Note = '';
          form.Alert = true;
          form.ReferenceNumber = '';
          form.Subscription = '';
          preimport.value = true;
        }
      });

      $root.$on('modal.close', (name) => {
        if (name == modalName) {
          close();
        }
      });

      $root.$on('modal.close.all', () => {
        close();
      });
    });

    return {
      show,
      form,
      options,
      userInfo,
      error,
      editing,
      preimport,
      backdrop,
      dragEventTriggered,
      reset,
      close,
      toggleBackdrop,
      submit,
      update,
    };
  },
};
</script>

</script>
