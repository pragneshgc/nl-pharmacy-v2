<template>
    <transition name="fade">
        <div class="esa-modal" v-if="show.modal">
            <div v-if="backdrop" class="backdrop" @click="close()">
            </div>
            <div class="modal" id="draggable-div-prescriber">
                <transition name="fade">
                    <div class="loader" v-show="loading">Loading...</div>
                </transition>

                <div class="modal-header draggable-div-header" id="draggable-div-header-prescriber">
                    <h3>Send Message</h3>
                </div>
                <div class="modal-body">
                    <form @submit="submit()">
                        <label for="message-select">Email template <small class="danger">(required)</small></label>
                        <select v-model="prescriberForm.select" name="message-select" class="table-dropdown mb-10">
                            <option value="0" disabled>Please select a template</option>
                            <option v-for="option in prescriberForm.options" :value="option.value" :key="option.value">{{
                                option.name }}</option>
                        </select>

                        <div class="mb-10" v-if="prescriberForm.select == 3">
                            <label for="message-date">New date <small class="danger">(required)</small></label>
                            <datepicker name="return-date" v-model="prescriberForm.date" :disabled-dates="disabledDates"
                                maxlength="30"></datepicker>
                        </div>

                        <label for="message-text">Message <small class="danger">(required)</small></label>
                        <!-- <textarea v-model="prescriberForm.message" name="message-text" cols="30" rows="30"> -->
                        <vue-editor v-model="prescriberForm.message" />

                        <div v-if="errors.length > 0" class="infoBox error">
                            <p v-for="(value, key) in errors" :key="key">
                                {{ value }}
                            </p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button :disabled="loading" @click="close()" class="btn btnSize01 secondaryBtn bigButton">
                        Cancel
                    </button>
                    <button :disabled="loading" @click="submit()" class="btn btnSize01 primaryBtn">
                        Submit
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
import { VueEditor } from "vue2-editor";
import Error from '../mixins/errors';
import Datepicker from 'vuejs-datepicker';
import axios from 'axios';

export default {
    props: ['orderID'],
    components: {
        VueEditor,
        Datepicker
    },
    extends: Modal,
    setup(props) {
        const prescriberForm = reactive({
            options: [
                { name: '[REJECT] Too many ordered', value: 1, limit: 30 },
                { name: '[REJECT] Dosage problem', value: 2, limit: 30 },
                // {name: '[POSTPONE] Ordered too early', value: 3, limit: 30},
                { name: '[QUERY] Miscellaneous', value: 4, limit: 30 },
                { name: '[QUERY] Dosage problem', value: 5, limit: 30 },
                { name: '[QUERY] Potential name discrepancy', value: 6, limit: 0 },
            ],
            select: ref(0),
            message: ref(''),
            date: ref(''),
        });

        const disabledDates = ref({
            to: new Date(),
            days: [6, 0],
        });

        const errors = ref([]);
        const loading = ref(false);
        const backdrop = ref(true);
        const dragEventTriggered = ref(false);

        watch(prescriberForm.select, (newValue) => {
            if (newValue != 3) {
                prescriberForm.date = '';
            }
        });

        watch(props.show.modal, (newValue) => {
            setTimeout(() => {
                if (newValue && !dragEventTriggered.value && document.getElementById("draggable-div-prescriber")) {
                    dragElement(document.getElementById("draggable-div-prescriber"));
                    dragEventTriggered.value = true;

                    function dragElement(elmnt) {
                        var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

                        if (document.getElementById("draggable-div-header-prescriber")) {
                            document.getElementById("draggable-div-header-prescriber").onmousedown = dragMouseDown;
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
            }, 500);
        });

        const submit = () => {
            loading.value = true;
            axios.post('/mail/' + props.orderID + '/contact', { form: prescriberForm })
                .then((response) => {
                    postSuccess(response.data.message);
                    $root.$emit('tray.remove', props.orderID); //remove from tray when queried
                    resetForm();
                    props.show.modal = false;
                })
                .catch((error) => {
                    errors.value.push(error.response.data.message);
                    postError(error.response.data.message);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        const toggleBackdrop = () => {
            backdrop.value = !backdrop.value;
        };

        const resetForm = () => {
            prescriberForm.select.value = 0;
            prescriberForm.message.value = '';
            prescriberForm.date.value = '';
            errors.value = [];
        };

        onMounted(() => {
            // Any initialization logic you want to run when the component mounts
        });

        return {
            prescriberForm,
            disabledDates,
            errors,
            loading,
            backdrop,
            submit,
            toggleBackdrop,
            resetForm
        };
    }
};
</script>
