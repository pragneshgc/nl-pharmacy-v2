<template>
    <div class="footer" v-if="user.info.role != 4">
        <div class="tray-drawer" :class="{ 'open': show.tray }">
            <div class="tray-toolbar">
                <select v-if="[30, 35, 50, 60].includes(user.info.role)" v-model="user.selected" class="table-dropdown"
                    name="users" style="margin-right: 10px;">
                    <option default selected :value="user.info.id">
                        {{ user.info.name + ' ' + user.info.surname }}
                    </option>
                    <option v-for="user in user.list" :key="user.id" :value="user.id">
                        {{ user.name + ' ' + user.surname }}
                        <span v-if="user.id != 'new'">{{ ' (' + user.count + ')' }}</span>
                    </option>
                </select>
                <button v-if="user.selected != 'new'" :title="titlesText.clearTrayHelper" class="btn btnSize02 secondaryBtn"
                    @click="clearTray()" style="margin-right: 10px;">
                    {{ titlesText.clearTray }}
                </button>
                <button title="Take over all orders in tray" v-if="!usersTray && user.selected != 'new'"
                    class="btn btnSize02 secondaryBtn" @click="takeoverTray()">
                    Take over tray
                </button>
            </div>
            <div class="tray-table">
                <table>
                    <thead>
                        <tr>
                            <th>Prescription ID</th>
                            <th>Client</th>
                            <th>Courier</th>
                            <th>Reference Number</th>
                            <th>Status</th>
                            <th>Date/Time</th>
                            <th>Products</th>
                            <th v-if="user.selected != 'new'" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :class="{ 'active': ($route.params.id == item.PrescriptionID && tray.length != 0) }"
                            v-for="item in tray" :key="item.PrescriptionID">
                            <td class="clickable" @click="selectFromTray(item.PrescriptionID)">
                                {{ item.PrescriptionID }}
                            </td>
                            <td class="clickable" @click="selectFromTray(item.PrescriptionID)">
                                {{ item.CompanyName }}
                                <span v-if="item.JVM">
                                    <br>
                                    <b>Pouch</b>
                                </span>
                            </td>
                            <td class="clickable" @click="selectFromTray(item.PrescriptionID)">
                                <div style="display: inline-flex; align-items: center;">
                                    <div
                                        style="margin-right: 5px;text-align: center;min-width: 85px; height:25px; display: inline-block;">
                                        <img height="25"
                                            :src="imgMap[item.UPSAccessPointAddress != 0 ? 70 : item.DeliveryID]" />
                                    </div>
                                    <!-- {{ couriers[item.DeliveryID] }} -->
                                </div>
                                <!-- {{ couriers[item.DeliveryID] }} -->
                            </td>
                            <td class="clickable" @click="selectFromTray(item.PrescriptionID)">
                                {{ item.ReferenceNumber }}
                            </td>
                            <td class="clickable" @click="selectFromTray(item.PrescriptionID)">
                                {{ item.Status }}
                            </td>
                            <td class="clickable" @click="selectFromTray(item.PrescriptionID)">
                                {{ item['Date/Time'] }}
                            </td>
                            <td class="clickable" @click="selectFromTray(item.PrescriptionID)">
                                <ul>
                                    <!-- <li v-for="(value, index) in item.Products" :key="index">{{ value }}</li> -->
                                    <li v-for="(value, index) in item.Products" :key="index" v-html="value" />
                                </ul>
                            </td>
                            <td v-if="user.selected != 'new'">
                                <a class="btn btn-primary waves-effect table-icon" title="Remove from tray"
                                    @click="deleteTray(item.TrayID)">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-button-wrapper">
            <div class="formItemsGroup floatLeft">
                <div v-if="$route.name != 'prescription'">
                    <button v-if="user.info.role != 40" :title="titlesText.trayHelper" :class="{ 'active': show.tray }"
                        @click="toggleTray()" class="btn btnSize01 secondaryBtn">
                        {{ titlesText.tray }} ({{ tray.length }} orders)
                    </button>
                </div>
                <div v-else>
                    <button v-if="currentOrderInTray || user.info.role >= 50" :title="titlesText.trayHelper"
                        :class="{ 'active': show.tray }" @click="toggleTray()" class="btn btnSize01 secondaryBtn">
                        {{ titlesText.tray }} ({{ tray.length }} orders)
                    </button>

                    <div :title="titlesText.trayHelper" v-else-if="user.info.role != 20 && user.info.role != 40"
                        class="button-group">
                        <button :class="{ 'active': show.tray }" style="margin-right: 0px;" @click="toggleTray()"
                            class="btn btnSize01 secondaryBtn">
                            {{ titlesText.tray }} ({{ tray.length }} orders)
                        </button>
                        <button title="Add to tray" v-if="usersTray" @click="addToTray()"
                            class="btn btnSize01 secondaryBtn">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>

                    <button v-else-if="user.info.role == 20 || user.info.role == 19" :class="{ 'active': show.tray }"
                        @click="toggleTray()" class="btn btnSize01 secondaryBtn">
                        {{ titlesText.tray }} ({{ tray.length }} orders)
                    </button>

                    <button :title="titlesText.trayHelper" v-else-if="user.info.role != 40" :class="{ 'active': show.tray }"
                        @click="toggleTray()" class="btn btnSize01 secondaryBtn">
                        {{ titlesText.tray }} ({{ tray.length }} orders)
                    </button>

                    <button v-if="prescription.Status != 8 && [29, 30, 35].includes(user.info.role)"
                        title="Change the prescription priority (save for later)" @click="saveLater()"
                        :disabled="loadingPrescription" class="btn btnSize01 secondaryBtn">
                        Save for later
                    </button>

                    <div :title="titlesText.trayHelper" class="button-group">
                        <button style="margin-right: 0px;" title="View prescription in a window"
                            class="btn btnSize01 secondaryBtn" :disabled="loadingPrescription" @click="view()">
                            View Prescription
                        </button>

                        <button style="margin-right: 0px; border-right: 0;" title="View pharmacy label in a window"
                            class="btn btnSize01 secondaryBtn" :disabled="loadingPrescription" @click="viewLabel()">
                            View Label
                        </button>

                        <button style="margin-right: 0px;" title="Download prescription PDF"
                            class="btn btnSize01 secondaryBtn" @click="prescriptionDownload()"
                            :disabled="loadingPrescription">
                            <i class="fa fa-download"></i>
                        </button>

                        <button title="Print prescription" class="btn btnSize01 secondaryBtn" @click="prescriptionPrint()"
                            :disabled="loadingPrescription">
                            <i class="fa fa-print"></i>
                        </button>
                    </div>

                    <button title="Download prescription" class="btn btnSize01 secondaryBtn" @click="xmlDownload()"
                        :disabled="loadingPrescription">
                        XML
                    </button>

                    <button title="Edit the order details" :disabled="loadingPrescription" @click="editDetails()"
                        class="btn btnSize01 secondaryBtn">
                        Edit
                    </button>

                    <button v-if="prescription.Status == 16 && !loadingPrescription" :disabled="loadingPrescription"
                        @click="redelivery()" title="Redeliver prescription"
                        class="btn btnSize01 secondaryBtn">Redelivery</button>
                </div>
            </div>

            <div class="formItemsGroup floatRight">
                <!-- DISPENSER -->
                <div v-if="[19, 20, 40, 50, 60].includes(user.info.role) && $route.name == 'prescription'"
                    style="position: relative;">
                    <div class="auto-grid" style="margin-right: 10px">
                        <div title="Pathology Request Form" :class="[printIcons.PathologyRequestForm ? 'active' : '']">
                            <b>Pathology</b>
                        </div>
                        <div title="Additional Information Page"
                            :class="[printIcons.ProductAdditionalInformation ? 'active' : '']">
                            <b>Info</b>
                        </div>
                        <div title="Product Information Leaflet"
                            :class="[printIcons.ProductInformationLeaflet ? 'active' : '']">
                            <b>PIL</b>
                        </div>
                    </div>

                    <div @click="dispenserPrint('delivery')" class="button-group" title="Print Delivery Note" v-if="!isJVM">
                        <button :disabled="loadingPrescription || loading" style="margin-right: 0px;"
                            :class="[printed.DeliveryNote ? 'primaryBtn' : 'secondaryBtn']" class="btn btnSize01">
                            {{ !isJVM ? 'Delivery Note' : 'Delivery Note & Label' }}
                        </button>
                        <button :disabled="loadingPrescription || loading" style="margin-left: 0px;"
                            :class="[printed.DeliveryNote ? 'primaryBtn' : 'secondaryBtn']" class="btn btnSize01">
                            <input :class="{ 'unchecked': !printed.DeliveryNote }" :checked="printed.DeliveryNote"
                                :disabled="loadingPrescription || loading" type="checkbox" name="checkall">
                            <label for="checkall"></label>
                        </button>
                    </div>

                    <div @click="pouchPrint()" class="button-group" title="Print Delivery Note" v-else>
                        <button :disabled="loadingPrescription || loading" style="margin-right: 0px;"
                            :class="[printed.DeliveryNote ? 'primaryBtn' : 'secondaryBtn']" class="btn btnSize01">
                            Delivery Note & Label
                        </button>
                        <button :disabled="loadingPrescription || loading" style="margin-left: 0px;"
                            :class="[printed.DeliveryNote ? 'primaryBtn' : 'secondaryBtn']" class="btn btnSize01">
                            <input :class="{ 'unchecked': !printed.DeliveryNote }" :checked="printed.DeliveryNote"
                                :disabled="loadingPrescription || loading" type="checkbox" name="checkall">
                            <label for="checkall"></label>
                        </button>
                    </div>

                    <div class="button-group" title="Print Pharmacy Label" v-if="!isJVM">
                        <button @click="dispenserPrint('label')"
                            :disabled="loadingPrescription || !printed.DeliveryNote || loading" style="margin-right: 0px;"
                            :class="[printed.PharmacyLabel ? 'primaryBtn' : 'secondaryBtn']" class="btn btnSize01">
                            Pharmacy Label
                        </button>
                        <button @click="dispenserPrint('label')"
                            :disabled="loadingPrescription || !printed.DeliveryNote || loading" style="margin-left: 0px;"
                            :class="[printed.PharmacyLabel ? 'primaryBtn' : 'secondaryBtn']" class="btn btnSize01">
                            <input :class="{ 'unchecked': !printed.PharmacyLabel }" :checked="printed.PharmacyLabel"
                                :disabled="loadingPrescription || !printed.DeliveryNote || loading" type="checkbox"
                                name="checkall">
                            <label for="checkall"></label>
                        </button>
                    </div>

                    <!-- <button class="btn btnSize01 secondaryBtn" title="Leaflet">
                        <i class="fa fa-file"></i>
                    </button> -->
                </div>
                <!-- /DISPENSER -->

                <!-- EVERYONE -->
                <div v-if="!['prescription', 'returns'].includes($route.name)">
                    <!-- <select>
                        <option value="">Download CSV (TNT) non UK</option>
                        <option value="">Download CSV (TNT) non UK</option>
                    </select>
                    <button :disabled="loadingPrescription" class="btn btnSize01 primaryBtn">Send Request</button> -->
                </div>

                <div v-else-if="$route.name == 'returns'">
                    <button :disabled="loadingPrescription" class="btn btnSize01 tertiaryBtn">Cancel Order and
                        Finish</button>
                    <button :disabled="loadingPrescription" class="btn btnSize01 primaryBtn">Cancel Order and Resend
                        New</button>
                </div>

                <!-- <div v-else-if="prescription.Status == 8 && !loadingPrescription">
                    <button :disabled="/*![8].includes(prescription.Status) || loadingPrescription*/true"
                    @click="$router.push({name: 'returns', params: {id: prescription.PrescriptionID}})"
                    title="Approve prescription"
                    class="btn btnSize01 primaryBtn">Resend</button>
                </div> -->

                <!-- <div v-else-if="prescription.Status == 8 && !loadingPrescription">
                    <button
                    :disabled="loadingPrescription"
                    @click="redelivery()"
                    title="Approve prescription"
                    class="btn btnSize01 primaryBtn">Redelivery</button>
                </div> -->

                <!-- <div v-else-if="prescription.Status == 9 && !loadingPrescription">
                    <button :disabled="/*![9].includes(prescription.Status) || loadingPrescription || loading*/true"
                    @click="changePrescriptionStatus(1)"
                    title="Approve prescription"
                    class="btn btnSize01 primaryBtn">Safe</button>
                </div> -->

                <div v-else-if="[29, 30, 35].includes(user.info.role) || user.info.role >= 50">
                    <button title="Open the prescriber contact form" :disabled="loadingPrescription || !approveable"
                        v-if="prescription.Status == 1" @click="openContact()" class="btn btnSize01 secondaryBtn">
                        Show Options
                    </button>

                    <button :disabled="prescription.Status != 1 || loadingPrescription || loading || !approveable"
                        @click="changePrescriptionStatus(2)" title="Approve prescription" class="btn btnSize01 primaryBtn">
                        Approve
                    </button>
                </div>
                <!-- /EVERYONE -->

                <button :disabled="loadingPrescription" v-if="$route.name == 'prescription' && tray.length > 1"
                    title="Go to next order" style="border: none;" @click="changePrescription('forward')"
                    class="next finish clickable">
                    Next
                </button>
                <button :disabled="loadingPrescription"
                    v-else-if="$route.name == 'prescription' && tray.length == 1 && currentOrderInTray" title="Finish"
                    style="border: none;" @click="finishTray()" class="next finish clickable">
                    {{ (user.info.role == 20 || user.info.role == 19) ? 'Finish' : 'in tray' }}
                </button>
            </div>
        </div>

        <transition name="fade">
            <EditOrderAddress @closeorder="editingOrder = !editingOrder" :products="[]"
                :orderID="prescription.PrescriptionID" :orderStatus="orderStatuses[prescription.Status]"
                v-if="editingOrder" />
        </transition>

        <PrescriberModal :orderID="prescription.PrescriptionID" modal-name="prescriber" />
        <NoteModal :orderID="prescription.PrescriptionID" modal-name="note" />

        <Modal class="duplicate-modal" modal-name="duplicate">
            <template v-slot:header>
                <h2>Same reference number was found for multiple orders. You can review them below:</h2>
            </template>
            <template v-slot:body>
                <data-table :data-url="`/orders/duplicate/${duplicateReference}`" column-class="col-lg-12"
                    table-title="Prescriptions" redirect-name="prescription" redirect-id="PrescriptionID"
                    :hidden-columns="['checked', 'disabled']" :redirect-callback="closeDuplicateModal" :column-map="{
                        'PrescriptionID': 'ID',
                        'DeliveryID': 'Courier',
                        'CompanyName': 'Client',
                        'ReferenceNumber': 'Ref',
                    }"></data-table>
            </template>
        </Modal>

        <RedeliveryModal v-if="redeliveryToggle" @closeredelivery="redeliveryToggle = !redeliveryToggle"
            class="redelivery-modal modal-sm" modal-name="redelivery" :orderID="prescription.PrescriptionID" />
    </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import OrderStatuses from '../../mixins/constants/orderStatuses';
import Error from '../../mixins/errors';
import DownloadMixin from '../../mixins/download';
import PrintMixin from '../../mixins/print';
import PDFMixin from '../../mixins/pdf';
import deliveryImgMap from '../../mixins/constants/deliveryImgMap';
import { storeToRefs } from "pinia";
import mitt from 'mitt';
import axios from 'axios';

const emitter = mitt();

export default {
    mixins: [OrderStatuses, Error, DownloadMixin, PrintMixin, deliveryImgMap, PDFMixin],
    components: {
        'EditOrderAddress': () => import('../../pages/EditOrderAddress.vue'),
        'PrescriberModal': () => import('../PrescriberModal.vue'),
        'NoteModal': () => import('../NoteModal.vue'),
        'Modal': () => import('../Modal.vue'),
        'RedeliveryModal': () => import('../RedeliveryModal.vue'),
    },
    setup() {
        // Reactive variables
        const loadingPrescription = ref(true);
        const loading = ref(false);
        const prescription = ref(false);
        const editingOrder = ref(false);
        const redeliveryToggle = ref(false);
        const approveable = ref(false);
        const showTray = ref(false);
        const duplicateReference = ref(0);
        const user = reactive({
            info: userInfo,
            selected: userInfo.id,
            list: []
        });
        const printed = reactive({
            DeliveryNote: 0,
            PharmacyLabel: 0,
        });
        const printIcons = reactive({
            EveAdamLetter: 0,
            PathologyRequestForm: 0,
            ProductAdditionalInformation: 0,
            ProductInformationLeaflet: 0,
        });

        // Computed properties
        const tray = computed(() => {
            return $store.state.tray;
        });
        const currentOrderInTray = computed(() => {
            return tray.value.some(prescription => prescription.PrescriptionID == $route.params.id)
        });
        const usersTray = computed(() => {
            return user.info.id == user.selected;
        });
        const titlesText = computed(() => {
            return {
                tray: user.info.role != 20 ? 'My Tray' : 'Assigned',
                trayHelper: user.info.role != 20 ? 'Open your tray' : 'Show assigned orders',
                clearTray: user.info.role != 20 ? 'Clear Tray (release)' : 'Release Orders',
                clearTrayHelper: user.info.role != 20 ? 'Release a tray' : 'Release all assigned orders'
            };
        });
        const isJVM = computed(() => {
            let check = false;

            if (typeof prescription.value.Products == 'undefined') {
                return check;
            }

            for (let product of prescription.value.Products) {
                if (product.JVM == 2) {
                    check = false;
                    break;
                }

                if (product.JVM == 0) {
                    if (prescription.value.JVM == 1) {
                        check = true;
                    }
                } else if (product.JVM == 1) {
                    if (prescription.value.ClientID == 51) {
                        check = true;
                    }
                }
            }

            return check;
        });

        // Methods
        const finishTray = () => {
           if (user.info.role == 20 || user.info.role == 19) {
                checkIfPrintFinished(() => {
                    let currentStatus = JSON.parse(JSON.stringify(prescription.Status));

                    if (printed.DeliveryNote && printed.PharmacyLabel && currentStatus == 2 && currentOrderInTray && !isJVM) {
                        changePrescriptionStatus(7);
                        deleteTrayByPrescription(prescription.PrescriptionID);
                        prescription = false;
                    } else if (printed.DeliveryNote && currentStatus == 2 && currentOrderInTray && isJVM) {
                        sendJVMOrder(prescription.PrescriptionID);
                        changePrescriptionStatus(7);
                        deleteTrayByPrescription(prescription.PrescriptionID);
                    } else {
                        $router.push({ name: 'prescription pool' });
                    }
                }, true);
            } else {
                prescription = false;
                $router.push({ name: 'in tray' });
            }
        };

        const changePrescription = (direction, skip = false) => {
             // let currentPrescriptionId = $route.params.id;
            if (tray.length == 0) {
                // localStorage.setItem('dashboard.orderFilter', 'new');//reset dashboard tray to new to show new orders
                if (user.info.role == 20 || user.info.role == 19) {
                    $router.push({ name: 'prescription pool' });
                } else {
                    $router.push({ name: 'in tray' });
                }
                return;
            }

            if (!loadingPrescription) {
                loadingPrescription = true;
                let currentStatus = JSON.parse(JSON.stringify(prescription.Status));

                let index = 0;
                if (currentOrderInTray) {
                    index = tray.findIndex(p => p.PrescriptionID == $route.params.id);

                    switch (direction) {
                        case 'forward':
                            if (index + 1 < tray.length) {
                                index = index + 1;
                            } else {
                                index = 0;
                            }

                            break;
                        case 'back':
                            if (index - 1 >= 0 && index - 1 < tray.length) {
                                index = index - 1;
                            } else {
                                index = tray.length - 1;
                            }

                            break;
                        default:
                            index = 0;
                            break;
                    }
                } else {
                    index = 0;
                }

                if (parseInt(tray[index].PrescriptionID) != parseInt($route.params.id)) {
                    checkIfPrintFinished(() => {
                        //needs to be refactored
                        if (printed.DeliveryNote && printed.PharmacyLabel && (user.info.role == 20 || user.info.role == 19) && currentStatus == 2 && currentOrderInTray) {
                            if (isJVM) {
                                sendJVMOrder(prescription.PrescriptionID);
                            }
                            changePrescriptionStatus(7);

                            deleteTrayByPrescription(prescription.PrescriptionID);
                        } else if (printed.DeliveryNote && (user.info.role == 20 || user.info.role == 19) && currentStatus == 2 && currentOrderInTray && isJVM) {
                            sendJVMOrder(prescription.PrescriptionID);
                            changePrescriptionStatus(7);
                            deleteTrayByPrescription(prescription.PrescriptionID);
                        }

                        prescription = false;
                        $router.push({ name: 'prescription', params: { id: parseInt(tray[index].PrescriptionID) } });
                    }, false, skip);
                } else {
                    prescription = false;
                    localStorage.setItem('dashboard.orderFilter', (user.info.role == 20 || user.info.role == 19) ? 'approved' : 'new');//reset dashboard tray to new to show new orders
                    $root.$emit('orderupdate');
                    if (user.info.role == 20 || user.info.role == 19) {
                        $router.push({ name: 'prescription pool' });
                    } else {
                        $router.push({ name: 'in tray' });
                    }
                    loadingPrescription = false;
                }

            }
        };

        const sendJVMOrder = (id) => {
            axios.post(`/jvm/${id}/send`)
            .then((response) => {
                postSuccess(response.data.message);
            })
            .catch((error) => {
                postError(error.response.data.message);
            })
        };

        const checkApprovable = (id) => {
            axios.get(`/tray/${id}/check`)
            .then((response) => {
                if (!response.data.data) {
                    approveable = true;
                } else {
                    approveable = false;
                }
            })
            .catch((error) => {
                postError(error.response.data.message);
            })
        };

        const getPharmacists = () => {
            let type = 'pharmacists';

            if (user.info.role == 20 || user.info.role == 19) {
                type = 'dispensers';
            }

            axios.get(`/user/${type}`)
            .then((response) => {
                user.list = response.data.data;
            })
            .catch((error) => {
                postError(error.response.data.message);
            })
        };

        const toggleTray = () => {
            showTray.value = !showTray.value;
        };

        const editDetails = () => {
            editingOrder.value = !editingOrder.value;
        };

        const getTray = (id = false) => {
            let parameter = '';

            if (id) {
                parameter = `/${id}`;
            }

            axios.get(`/tray${parameter}`)
            .then((response) => {
                refreshTray(response.data.data);
            })
            .catch((error) => {
                postError(error.response.data.message);
            })
        };

        const addToTray = (id) => {
            axios.post('/tray', { PrescriptionID: [$route.params.id] })
                .then((response) => {
                    postSuccess(response.data.message);
                    $root.emit('tray.refresh');
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
        };

        const selectFromTray = (id) => {
            $router.push({ name: 'prescription', params: { id: id } });
            show.tray = false;
        };

        //Clear a user tray (remove all attached orders)
        const clearTray = () => {
            let parameter = '';

            if (!usersTray) {
                parameter = `/${user.selected}`;
            }

            axios.delete(`/tray/clear${parameter}`)
                .then((response) => {
                    clearTray();
                    postSuccess(response.data.message);
                    $root.emit('table.refresh'); // this should only be called if there is a table present
                    $root.emit('tray.clear'); // this event is used by the prescription pool
                    getPharmacists();
                    user.selected = user.info.id;
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
        };

        //Take over a user tray (remove attached orders and add to authenticated user tray)
        const takeoverTray = () => {
            axios.post(`/tray/${user.selected}/takeover`)
                .then((response) => {
                    postSuccess(response.data.message);
                    getPharmacists();
                    user.selected = user.info.id; // switching the user here will trigger a tray refresh
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
        };

        // delete tray item by id
        const deleteTray = (id) => {
            if (id) {
                axios.delete(`/tray/${id}`)
                    .then((response) => {
                        console.log('tray deleted', id);
                        removeTray(response.data.data);
                        postSuccess(response.data.message);
                        $root.emit('table.refresh'); // this should only be called if there is a table present
                        $root.emit('tray.clear'); // this event is used by the prescription pool
                    })
                    .catch((error) => {
                        postError(error.response.data.message);
                    })
            }
        };

        // delete a tray item by prescription id
        const deleteTrayByPrescription = (id, skip = false) => {
            let trayId = getTrayId(id);
            console.log('id', id);
            console.log('tray id', trayId);
            deleteTray(trayId);
            changePrescription('forward', skip);
        };

        //use prescription id to get the tray id
        const getTrayId = (id) => {
            let trayItem = tray[tray.map(function (item) { return item.PrescriptionID; }).indexOf(parseInt(id))];

            if (typeof trayItem != 'undefined') {
                return trayItem.TrayID;
            } else {
                return false;
            }
        };

        //change the status of prescription
        const changePrescriptionStatus = (status) => {
            // $root.emit('prescriptionloading');
            loading = true;

            axios.post('/order-edit/' + prescription.PrescriptionID + '/status', { status: status })
                .then((response) => {
                    if (response.status == 200) {
                        $root.emit('statistic.update');
                        deleteTrayByPrescription(prescription.PrescriptionID);
                        postSuccess(response.data.message);
                    }
                })
                .catch((error) => {
                    $root.emit('orderupdate');
                    postError(error.response.data.message);
                })
                .finally(() => {
                    loading = false;
                })
        };

        //open contact modal
        const openContact = () => {
            $root.emit('modal.open', 'prescriber');
        }

        //save later - change the priority of tray item
        const saveLater = () => {
            axios.patch(`/tray/${getTrayId(prescription.PrescriptionID)}/lower-priority`)
                .then((response) => {
                    postSuccess('Order saved for later!');
                    $root.emit('tray.refresh');
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
        };

        /**
         * View prescription in popup
         */
        const view = () => {
            axios.get(`/order/${prescription.PrescriptionID}/view`)
                .then((response) => {
                    let url = `${response.data.data.url}?token=${user.info.token}`;

                    //a hack around in case the PDF was not generated
                    if (response.data.data.type == 'html') {
                        url = `https://esasys.co.uk/?showFile&PRESCRIPTIONID=${prescription.PrescriptionID}`;
                    }

                    window.open(url, '_blank', 'toolbar=0,location=0,menubar=0');
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
        };


        /**
         * View label in a popup
         */
        const viewLabel = () => {
            axios.get(`/order/${prescription.PrescriptionID}/label`)
                .then((response) => {
                    let url = `${response.data.data.url}?token=${user.info.token}`;

                    window.open(url, '_blank', 'toolbar=0,location=0,menubar=0');
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
        };

        const prescriptionDownload = () => {
            axios.get(`/order/${prescription.PrescriptionID}/view`)
                .then((response) => {
                    let url = response.data.data.url;
                    let type = response.data.data.type;
                    downloadURI(`${url}?token=${user.info.token}`, `${prescription.PrescriptionID}.${type}`);
                })
                .catch((error) => {
                    postError(error.response.data.message);
                });
        };

        const xmlDownload = () => {
            //axios.get(`/order/${prescription.PrescriptionID}/xml?token=${user.info.token}`);
            downloadURI(`/order/${prescription.PrescriptionID}/xml?token=${user.info.token}`, `${prescription.PrescriptionID}.xml`);
        };

        const closeDuplicateModal = () => {
            $root.emit('modal.close', 'duplicate');
        };

        const prescriptionPrint = () => {
            axios.get(`/order/${prescription.PrescriptionID}/view`)
                .then((response) => {
                    let url = response.data.data.url;
                    let type = response.data.data.type;

                    if (type == 'pdf') {
                        printPage(`${url}?token=${user.info.token}&print=true`, true);
                        // printUrl(url);
                    } else {
                        printPage(`${url}?token=${user.info.token}&print=true`);
                    }
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
        };

        /*DISPENSER SPECIFIC*/
        const checkIfPrintFinished = (callback = false, finish = false, skip = false) => {
            if ((printed.DeliveryNote && printed.PharmacyLabel && !isJVM) || (printed.DeliveryNote && isJVM) || user.info.role != 20 || skip) {
                callback();
            } else {
                let text = "Are you sure you want to go to another order, the current print has not been finished!";

                if (finish) {
                    text = "Are you sure you want to return to the prescription pool? The current print has not been finished!"
                }
                $swal({
                    title: 'Printing not finished!',
                    text: text,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue!'
                }).then((result) => {
                    if (result && callback && !result.dismiss) {
                        callback();
                    } else if (result && result.dismiss) {
                        loadingPrescription = false;
                        loading = false;
                    }
                })
            }
        };

        const pouchPrint = () => {
            // if(prescription.JVM == 1){
            printed.PharmacyLabel = true;

            axios.get(`/order/${prescription.PrescriptionID}/id-label`)
                .then((response) => {
                    let url = response.data.data.url;
                    let type = response.data.data.type;
                    let printer = false;

                    if (localStorage.getItem('settings.application')) {
                        printer = JSON.parse(localStorage.getItem('settings.application')).labelPrinter;
                    }

                    printUrl(`${url}?token=${user.info.token}&print=true&label`, () => {
                        dispenserPrint('delivery');
                    }, 'pdf', printer, true);
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
            // }
        };


        const dispenserPrint = (type) => {
            //print routine here
            if (type == 'delivery') {
                printed.DeliveryNote = true;

                axios.get(`/order/${prescription.PrescriptionID}/view`)
                    .then((response) => {
                        let url = response.data.data.url;
                        let type = response.data.data.type;
                        let printer = false;

                        if (localStorage.getItem('settings.application')) {
                            let deliveryNotePrinter = JSON.parse(localStorage.getItem('settings.application')).deliveryNotePrinter;

                            if (deliveryNotePrinter != '') {

                            }
                            printer = deliveryNotePrinter;
                        }

                        if (type == 'pdf') {
                            printUrl(`${url}?token=${user.info.token}&print=true`, () => {
                                $root.emit('orderupdate');
                            }, 'pdf', printer);
                        } else {
                            //test and delete this as necessary
                            //quick hack to redirect to esa in case a prescription is not found
                            let url = `https://esasys.co.uk/?showFile&PRESCRIPTIONID=${prescription.PrescriptionID}`;

                            printUrl(url, () => {
                                axios.get(`/prescription/${prescription.PrescriptionID}/log-print?token=${user.info.token}`)
                                    .then((response) => {
                                        $root.emit('orderupdate');
                                    })
                                    .catch((error) => {
                                        postError(error.response.data.message);
                                    })
                            }, 'pdf', printer);
                        }
                    })
                    .catch((error) => {
                        postError(error.response.data.message);
                    })
            } else if (type == 'label') {
                printed.PharmacyLabel = true;

                axios.get(`/order/${prescription.PrescriptionID}/label`)
                    .then((response) => {
                        let url = response.data.data.url;
                        let type = response.data.data.type;
                        let printer = false;

                        if (localStorage.getItem('settings.application')) {
                            printer = JSON.parse(localStorage.getItem('settings.application')).labelPrinter;
                        }

                        printUrl(`${url}?token=${user.info.token}&print=true`, () => {
                            $root.emit('orderupdate');
                        }, 'pdf', printer, true);
                    })
                    .catch((error) => {
                        postError(error.response.data.message);
                    })
            }
        };

        //get details on which files will be printed
        const getPrintDetails = () => {
            axios.get(`/order/${prescription.PrescriptionID}/print-details`)
                .then((response) => {
                    loading = true;
                    if (response.data.data != null) {
                        printIcons = response.data.data;
                    }
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
                .finally(() => {
                    loading = false;
                })
        };

        //check which files were printed
        const getPrintRecord = () => {
            axios.get(`/order/${prescription.PrescriptionID}/print-record`)
                .then((response) => {
                    loading = true;
                    if (response.data.data != null) {
                       printed = response.data.data;
                    }
                })
                .catch((error) => {
                    postError(error.response.data.message);
                })
                .finally(() => {
                    loading = false;
                })
        };

        const redelivery = () => {
            redeliveryToggle = true;
            // $root.emit('modal.open', 'redelivery');
        };

        // Other methods go here...

        // Lifecycle hooks
        onMounted(() => {
            emitter.on('tray.toggle', () => {
                toggleTray();
            });

            emitter.on('tray.refresh', (e) => {
                getTray();
            });

            emitter.on('tray.changeprescriptionstatus', (e) => {
                prescription = { PrescriptionID: e.id }
                changePrescriptionStatus(e.status);
            });

            emitter.on('tray.remove', (id) => {
                deleteTrayByPrescription(id);
            });

            emitter.on('tray.remove.skip', (id) => {
                deleteTrayByPrescription(id, true);
            });

            emitter.on('prescriptionloaded', (e) => {
                loadingPrescription = false;

                if (typeof e.prescription.PrescriptionID == 'undefined') {
                    postError('Prescription not loaded correctly. Please report to tech support and refresh the page!');
                    loadingPrescription = true;
                }

                prescription = e.prescription;

                if (!currentOrderInTray) {
                    checkApprovable(prescription.PrescriptionID);
                } else {
                    approveable = true;
                }

                // get print records in case the user is a dispenser, customer support or admin
                if ([20, 40, 50, 60].includes(user.info.role)) {
                    getPrintRecord();
                    getPrintDetails();
                }
            });

            emitter.on('prescriptionloading', (e) => {
                loadingPrescription = true;
                approveable = false;
                printed = {
                    DeliveryNote: 0,
                    PharmacyLabel: 0,
                };
                printIcons = {
                    EveAdamLetter: 0,
                    PathologyRequestForm: 0,
                    ProductAdditionalInformation: 0,
                    ProductInformationLeaflet: 0,
                };
            });

            emitter.on('prescription.edit', (e) => {
                editDetails();
            });

            emitter.on('showduplicates', (e) => {
                duplicateReference = e.duplicateReference;
                $root.emit('modal.open', 'duplicate');
            });

            document.onkeydown = (e) => {
                if ($router.name != 'prescription') {
                    return;
                }

                switch (e.keyCode) {
                    case 37:
                        if (document.activeElement.nodeName == 'BODY') {
                            changePrescription('back');
                            $root.emit('modal.close.all');
                        }
                        break;
                    case 39:
                        if (document.activeElement.nodeName == 'BODY') {
                            changePrescription('forward');
                            $root.emit('modal.close.all');
                        }
                        break;
                }
            };

            //get tray objects here
            getTray();
            if ([29, 30, 35, 50, 60].includes(user.info.role)) {
                getPharmacists();
            }
            checkApprovable($route.params.id);

        });

        onBeforeUnmount(() => {
            document.onkeydown = null;
            emitter.off('prescription.edit');
            emitter.off('prescriptionloaded');
            emitter.off('prescriptionloading');
            emitter.off('showduplicates');
            emitter.off('tray.open');
            emitter.off('tray.refresh');
            emitter.off('tray.remove');
            emitter.off('tray.remove.skip');
            emitter.off('tray.changeprescriptionstatus');
        });

        watch(() => user.selected, (newValue, oldValue) => {
            // Implementation
        });

        return {
            loadingPrescription,
            loading,
            prescription,
            editingOrder,
            redeliveryToggle,
            approveable,
            showTray,
            duplicateReference,
            user,
            printed,
            printIcons,
            tray,
            currentOrderInTray,
            usersTray,
            titlesText,
            isJVM,
            finishTray,
            changePrescription,
            sendJVMOrder,
            checkApprovable,
            getPharmacists,
            toggleTray,
            editDetails,
            getTray,           
        };
    }
};
</script>
