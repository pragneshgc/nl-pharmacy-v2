<template>
    <transition name="fade">
        <div class="esa-modal">
            <div class="backdrop" @click="close()">

            </div>
            <div class="modal">
                <transition name="fade">
                    <div class="loader" v-show="loading">Loading...</div>
                </transition>

                <div class="modal-header">
                    <h3>Redelivery</h3>
                </div>
                <div class="modal-body">
                    <div class="redelivery-selection" v-if="!selected" style="width: 100%; text-align:center;">
                        <h3 style="text-align:center;">Please select the method of redelivery</h3>
                        <br>
                        <button class="btn btnSize01 secondaryBtn" @click="redelivery()">Redelivery to Existing
                            Address</button>
                        <button class="btn btnSize01 secondaryBtn" @click="selectAddressUpdate()">Redelivery to New
                            Address</button>
                    </div>
                    <div class="redelivery-selection" v-if="selected" style="width: 100%; align-self: flex-start;">
                        <transition name="fade">
                            <form
                                v-if="countries.length != 0 && companies.length != 0 && !loading && Object.keys(confirmationChanges).length == 0 && Object.keys(confirmationChangesUPS).length == 0"
                                @submit.prevent="save" class="pxp-form address-form mb-20">
                                <div class="form-column" style="width: 100%;">
                                    <h3>Delivery Details</h3>
                                    <div v-if="columnDelivery.includes(value)" class="form-group form-group-2"
                                        v-for="(key, value) in details.order" :key="value">
                                        <label :for="key">{{ alias[value].title }}</label>
                                        <label :class="getCounterColor(value, details.order)" class="input-count"
                                            v-if="alias[value].value" :for="key">{{ details.order[value] ?
                                                details.order[value].length : 0 }}/{{ alias[value].value }}</label>
                                        <input :disabled="disabledFields.includes(value)"
                                            v-if="!['JVM', 'UPSAccessPointAddress', 'CountryCode', 'DCountryCode', 'DeliveryID', 'Notes'].includes(value)"
                                            class="" :name="key" :placeholder="''" v-model="details.order[value]">
                                        <select v-else-if="['DCountryCode', 'CountryCode'].includes(value)"
                                            v-model="details.order[value]"
                                            :class="[details.order[value] && details.order[value] != '' ? 'select-dropdown-active' : '']">
                                            <option v-for="country in countries" :value="country.CountryID">{{ country.Name
                                            }}
                                            </option>
                                        </select>
                                        <select v-else-if="['DeliveryID'].includes(value)" v-model="details.order[value]"
                                            :class="[details.order[value] && details.order[value] != '' ? 'select-dropdown-active' : '']">
                                            <option v-for="company in companies" :value="company.SettingID">{{ company.Name
                                            }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </transition>

                        <transition name="fade">
                            <div v-if="(Object.keys(confirmationChanges).length > 0 || Object.keys(confirmationChangesUPS).length > 0) && !loading"
                                class="pxp-form mb-20" style="height: auto;">
                                <div class="infoBox warning">
                                    <p>Please review and confirm all the changes in the order before saving!</p>
                                </div>

                                <DiffTableAddress :old-object="confirmationOld" :new-object="confirmationChanges"
                                    :old-object-u-p-s="confirmationOldUPS" :new-object-u-p-s="confirmationChangesUPS"
                                    :countries-prop="countries" :companies-prop="companies" />
                            </div>
                        </transition>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btnSize01 tertiaryBtn bigButton" @click="save()" :disabled="loading"
                        v-if="!isEqual(details.order, details.oldOrder) || !isEqual(details.ups, details.oldUPS) && selected && !loading">
                        <span v-if="!saveConfirmation">
                            Review
                        </span>
                        <span v-else>
                            Save and Redeliver
                        </span>
                    </button>

                    <button
                        v-if="(!isEqual(details.order, details.oldOrder) || !isEqual(details.ups, details.oldUPS)) && saveConfirmation"
                        :disabled="loading" @click="back()" class="btn btnSize01 tertiaryBtn bigButton">
                        <span>
                            Back
                        </span>
                    </button>

                    <button :disabled="loading" @click="close()" class="btn btnSize01 secondaryBtn bigButton">
                        Cancel
                    </button>
                    <!-- <button :disabled="loading" @click="submit()" class="btn btnSize01 primaryBtn">
                        Submit
                    </button> -->
                </div>
                <!-- <div v-if="loading" class="loader" style="">Loading...</div> -->
                <span class="close" @click="close()">X</span>
            </div>
        </div>
    </transition>
</template>

<script>
import { ref, reactive, watch, onMounted } from 'vue';
import ErrorMixin from '../mixins/errors';
import Modal from './Modal.vue';
import DiffTableAddress from './pages/DiffTableAddress.vue';
import PrescriptionColumns from './../mixins/constants/prescriptionColumns';
import axios from 'axios';

export default {
  extends: Modal,
  props: ['orderID'],
  components: { DiffTableAddress },
  setup(props) {
    const selected = ref(false);
    const loading = ref(false);
    const watch = ref(false);
    const countries = ref([]);
    const companies = ref([]);
    const details = reactive({
      order: {},
      oldOrder: {},
      ups: {},
      oldUps: {},
      details: {},
    });
    const saveConfirmation = ref(false);
    const confirmationChanges = ref({});
    const confirmationChangesUPS = ref({});
    const confirmationOld = ref({});
    const confirmationOldUPS = ref({});
    const disabledFields = ref([]);

    onMounted(() => {});

    const columnDelivery = ['DAddress1', 'DAddress2', 'DAddress3', 'DAddress4', 'DPostcode', 'DCountryCode', 'DeliveryID'];

    watch(() => details.order.DCountryCode, (newValue, oldValue) => {
      if (watch.value) {
        getDeliveryCompany();
      }
    });

    watch(() => details.order.DeliveryID, (newValue, oldValue) => {
      if (watch.value) {
        getPostcodeFormatting();
      }
    });

    const getOrderDetails = (newAddress = false) => {
      watch.value = false;

      axios.get('/order-edit/' + props.orderID)
        .then((response) => {
          details = response.data.data;

          if (newAddress) {
            details.order.DAddress1 = '';
            details.order.DAddress2 = '';
            details.order.DAddress3 = '';
            details.order.DAddress4 = '';
            details.order.DPostcode = '';
          }

          loading.value = false;
        })
        .catch((error) => {
          postError(error.response.data.message);
          loading.value = false;
        })
        .finally(() => {
          watch.value = true;
        });
    };

    const close = () => {
      saveConfirmation.value = false;
      confirmationChanges.value = {};
      confirmationOld.value = {};
      confirmationOld.value = {};
      confirmationOldUPS.value = {};
      details = { order: {}, oldOrder: {}, ups: {}, oldUps: {} }; // clean up after
      emit('closeredelivery');
    };

    const back = () => {
      saveConfirmation.value = false;
      confirmationChanges.value = {};
      confirmationChangesUPS.value = {};
    };

    const redelivery = () => {
      loading.value = true;
      axios.post(`/order/${props.orderID}/redeliver`)
        .then((response) => {
          postSuccess(response.data.message);
          show.modal = false;
          $root.$emit('orderupdate');
          close();
        })
        .catch((error) => {
          postError(error.response.data.message);
        })
        .finally(() => {
          loading.value = false;
        });
    };

    const selectAddressUpdate = () => {
      selected.value = true;
      getCountries();
      getCompanies();
      getOrderDetails(true);
    };

    const updateAddress = () => {};

    const getCountries = () => {
      axios.get('/countries')
        .then((response) => {
          countries.value = response.data.data;
        })
        .catch((error) => {
          postError(error.response.data.message);
        });
    };

    const getCompanies = () => {
      axios.get('/delivery-companies')
        .then((response) => {
          companies.value = response.data.data;
        })
        .catch((error) => {
          postError(error.response.data.message);
        });
    };

    const validateAddress = (callback = false) => {
      loadingValidation.value = true;

      axios.post(`/api/validate-address/${currentOrderID.value}`)
        .then((response) => {
          postSuccess(response.data.message);
          if (callback) {
            callback();
          }
        })
        .catch((error) => {
          postError(error.response.data.message);
        })
        .finally(() => {
          loadingValidation.value = false;
          search();
        });
    };

    const getCounterColor = (value, object) => {
      if (object[value] != null && alias[value].value) {
        if (object[value].length > 0 && alias[value].combined && object[alias[value].combined] != null) {
          if ((object[value].length + object[alias[value].combined].length) <= alias[value].value) {
            return 'input-count-success';
          } else {
            return 'input-count-danger';
          }
        } else if (object[value].length > 0 && object[value].length <= alias[value].value) {
          return 'input-count-success';
        } else if (object[value].length > alias[value].value) {
          return 'input-count-danger';
        }
      }

      return '';
    };

    const getDeliveryCompany = () => {
      axios.post(`/order-edit/${props.orderID}/delivery-company`, details.order)
        .then((response) => {
          let data = response.data.data;

          if (data.DeliveryID) {
            details.order.DeliveryID = data.DeliveryID;
          }

          if (data.CountryCode) {
            details.order.CountryCode = data.CountryCode;
          }

          getPostcodeFormatting();
          postSuccess('Delivery company updated');
        })
        .catch((error) => {
          postError(error.response.data.message);
        });
    };

    const getPostcodeFormatting = () => {
      if (details.order.DeliveryID == 10) {
        axios.post(`/order-edit/${props.orderID}/postcode-formatting`, details.order)
          .then((response) => {
            let data = response.data.data;

            if (data.Postcode) {
              details.order.Postcode = data.Postcode;
            }

            if (data.DPostcode) {
              details.order.DPostcode = data.DPostcode;
            }
          })
          .catch((error) => {
            postError(error.response.data.message);
          });
      }
    };

    const save = (validate = false) => {
      if (saveConfirmation.value) {
        submit(validate);
      } else {
        let orderDetails = JSON.parse(JSON.stringify(details.order));
        delete orderDetails.ClientID;

        axios.post(`/order-edit/check/${props.orderID}`, { order: orderDetails, ups: details.ups })
          .then((response) => {
            if (Object.keys(response.data.data.changes).length > 0 || Object.keys(response.data.data.changesUPS).length) {
              confirmationChanges.value = response.data.data.changes;
              confirmationChangesUPS.value = response.data.data.changesUPS;
              confirmationOld.value = response.data.data.old;
              confirmationOldUPS.value = response.data.data.oldUPS;
              saveConfirmation.value = true;
            } else {
              saveConfirmation.value = false;
            }
          })
          .catch((error) => {
            saveConfirmation.value = false;
            postError(error);
          });
      }
    };

    const submit = (validate = false) => {
      let orderDetails = JSON.parse(JSON.stringify(details.order));
      delete orderDetails.ClientID;

      loading.value = true;

      axios.post(`/api/validate-address/${props.orderID}`, { addressChange: orderDetails })
        .then((response) => {
          if (response.data.success) {
            postSuccess('Address Validated');
            axios.post('/order-edit/' + props.orderID, { order: orderDetails, ups: details.ups })
              .then((response) => {
                postSuccess('Saved');
                redelivery();
              })
              .catch((error) => {
                postError(error);
              })
              .finally(() => {
                saveConfirmation.value = false;
                loading.value = false;
              });
          } else {
            postError('Could not validate address');
          }
        })
        .catch((error) => {
          postError(error);
          loading.value = false;
        });
    };

    return {
      selected,
      loading,
      watch,
      countries,
      companies,
      details,
      saveConfirmation,
      confirmationChanges,
      confirmationChangesUPS,
      confirmationOld,
      confirmationOldUPS,
      disabledFields,
      columnDelivery,
      getOrderDetails,
      close,
      back,
      redelivery,
      selectAddressUpdate,
      updateAddress,
      getCountries,
      getCompanies,
      validateAddress,
      getCounterColor,
      getDeliveryCompany,
      getPostcodeFormatting,
      save,
      submit,
    };
  },
};

</script>
