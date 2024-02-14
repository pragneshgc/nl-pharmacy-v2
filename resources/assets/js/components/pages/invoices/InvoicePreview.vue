<template>
    <div class="content">
        <Modal class="duplicate-modal" modal-name="additem">
            <template v-slot:header>
                <h2>Add Item</h2>
            </template>
            <template v-slot:body>
                <div class="pxp-form wow fadeIn" style="height: auto !important">
                    <div class="form-row">
                        <h3 class="m-10">Information</h3>
                    </div>
                    <div class="form-column">
                        <div class="form-group form-group-2">
                            <label for="Description">Description</label>
                            <input name="Description" v-model="item.Description" autocomplete="off" type="text"
                                placeholder="Description" class="form-control" />
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group form-group-2">
                            <label for="PrescriptionID">PrescriptionID</label>
                            <input name="PrescriptionID" v-model="item.PrescriptionID" autocomplete="off" type="text"
                                placeholder="PrescriptionID" class="form-control" />
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-group form-group-2">
                            <label for="ReferenceNumber">Customer Reference</label>
                            <input name="ReferenceNumber" v-model="item.ReferenceNumber" autocomplete="off" type="text"
                                placeholder="ReferenceNumber" class="form-control" />
                        </div>
                    </div>

                    <div class="form-row">
                        <h3 class="m-10">Price</h3>
                    </div>

                    <div class="form-column">
                        <div class="form-group form-group-2">
                            <label for="UnitCost">Price</label>
                            <input name="UnitCost" v-model="item.UnitCost" autocomplete="off" type="text"
                                placeholder="UnitCost" class="form-control" />
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group form-group-2">
                            <label for="VAT">VAT
                                <span class="danger">(Value in currency {{ appInfo.currency }} amount)</span></label>
                            <input name="VAT" v-model="item.VAT" autocomplete="off" type="text" placeholder="VAT"
                                class="form-control" />
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-group form-group-2">
                            <label for="Type">Type</label>
                            <select class="browser-default custom-select" v-model="item.Type" name="Type">
                                <option :value="3">Credit/Refund</option>
                                <option :value="4">Misc Charge</option>
                            </select>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:footer>
                <button class="btn btnSize01 tertiaryBtn" @click="saveItem()">
                    Save Item
                </button>
            </template>
        </Modal>
        <section class="card">
            <div class="card-header">
                <h3>Invoice #{{ invoice.InvoiceID }} Details</h3>
            </div>
            <div class="card-body" style="
          display: flex;
          flex-direction: raw;
          justify-content: space-between;
        ">
                <div class="invoice-details">
                    <span>{{ invoice.Client }} Invoice # {{ invoice.InvoiceID }}</span>
                    <br />
                    <span>DATE COVERED: {{ invoice['Created Date'] }} -
                        {{ invoice['Created Date'] }}</span>
                    <br />
                    <span>DATE COMPLETED: {{ invoice['Completed Date'] }}</span>
                    <br />
                    <span>DATE PAID: {{ invoice['Paid Date'] }}</span>
                    <br />
                    <span>GROSS AMOUNT : {{ appInfo.currency
                    }}{{ invoice.GrossAmount }}</span>
                    <br />
                    <span>VAT : {{ appInfo.currency }}{{ (Math.floor(invoice.VAT * 100)/100).toFixed(2) }}</span>
                    <br />
                    <span>NET AMOUNT : {{ appInfo.currency }}{{ invoice.NetAmount }}</span>
                    <br />
                    <span>AMOUNT RECEIVED : {{ appInfo.currency
                    }}{{ invoice.AmountReceived }}</span>
                    <br />
                    <span>STATUS : {{ statuses[invoice.Status] }}</span>
                    <br />
                </div>
                <div class="invoice-options">
                    <button :disabled="loading" @click="emailInvoice()" v-if="invoice.Status != 0" title="Email Invoice"
                        class="btn btnSize02 secondaryBtn">
                        Email Invoice
                    </button>
                    <!-- <button v-if="invoice.Status != 0" title="Send Custom Email" class="btn btnSize02 secondaryBtn">Send Custom Email</button> -->
                    <button :disabled="loading" @click="addItem()" title="Add Item" class="btn btnSize02 secondaryBtn">
                        Add Item
                    </button>
                    <button :disabled="loading" v-if="invoice.Status == 0" @click="updateInvoiceStatus(1)"
                        title="Set Invoice as Complete" class="btn btnSize02 secondaryBtn">
                        Set Invoice as Complete
                    </button>
                    <!-- <button v-if="invoice.Status == 1" @click="updateInvoiceStatus(2)" title="Set Complete Date" class="btn btnSize02 secondaryBtn">Set Complete Date</button> -->
                    <button :disabled="loading" v-if="invoice.Status == 1" @click="updateInvoiceStatus(2)"
                        title="Set Invoice As Paid" class="btn btnSize02 secondaryBtn">
                        Set Invoice As Paid
                    </button>
                    <button :disabled="loading" @click="downloadInvoice()" title="View PDF"
                        class="btn btnSize02 secondaryBtn">
                        View Invoice
                    </button>
                    <button :disabled="loading" @click="exportCSV(invoiceItems, 'Invoice Items')" title="Download CSV"
                        class="btn btnSize02 secondaryBtn">
                        Download CSV
                    </button>

                    <div class="invoice-options mt-10">
                        <h3 class="mb-10 danger">
                            The button below applies to all currently incomplete
                            <b>{{ invoice.Client }}</b> invoices
                        </h3>
                        <button v-if="invoice.Status == 0" :disabled="loading" @click="setAllAsComplete()"
                            title="Set all as complete and email Invoice" class="btn btnSize02 primaryBtn">
                            <div v-if="loading" class="loader" style="">Loading...</div>
                            Set All incomplete {{ invoice.Client }} Invoices as complete and
                            email them
                        </button>
                        <button v-if="invoice.Status == 1" :disabled="loading" @click="setAllAsPaid()"
                            title="Set all as paid" class="btn btnSize02 primaryBtn">
                            Set All unpaid {{ invoice.Client }} Invoices as paid
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding: 0px">
                <table>
                    <thead>
                        <tr>
                            <th>Customer Reference</th>
                            <th>ESA Reference #</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>VAT</th>
                            <th>Total</th>
                            <th>Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in invoiceItems">
                            <tr :key="item.ItemID">
                                <td>{{ item.ReferenceNumber }}</td>
                                <td>{{ item.ItemID }}</td>
                                <td>{{ item.Date }}</td>
                                <td>{{ item.Description }}</td>
                                <td>{{ item.Quantity }}</td>
                                <td>{{ appInfo.currency }}{{ item.UnitCost }}</td>
                                <td>{{ appInfo.currency }}{{ item.VAT }}</td>
                                <td>{{ appInfo.currency }}{{ item.Total }}</td>
                                <td>
                                    <a v-if="[3, 4].includes(item.Type) &&
                                        item.Description != 'DISPENSING FEE'
                                        " @click="deleteItem(item.ItemID)" class="btn btn-primary waves-effect table-icon clickable"
                                        style="margin: 0px">
                                        <i aria-hidden="true" class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>

<script>
import Error from '../../../mixins/errors';
import CSV from '../../../mixins/csv';

export default {
    mixins: [Error, CSV],
    components: {
        Modal: () => import('../../Modal.vue'),
    },
    data() {
        return {
            loading: false,
            statuses: {
                0: 'INCOMPLETE',
                1: 'UNPAID',
                2: 'PAID',
                3: 'CREDITNOTE',
                4: 'DELETED',
            },
            item: false,
            invoice: {},
            invoiceItems: [],
            userInfo: userInfo,
            appInfo: appInfo,
        };
    },
    mounted() {
        this.getInvoice();
    },
    methods: {
        addItem() {
            this.item = {
                UnitCost: '',
                VAT: 0,
                Description: '',
                ReferenceNumber: '',
                PrescriptionID: 0,
                Type: 3,
            };

            this.$root.$emit('modal.open', 'additem');
            // this.notesConfirmed = true;
            // this.$root.$emit('modal.close', 'quicktraynotes');
        },
        deleteItem(id) {
            this.$swal({
                title: `Delete Item #${id}`,
                html: `Are you sure you want to delete this item?`,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff5151',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes! Delete it!',
            }).then((result) => {
                if (result.value) {
                    this.loading = true;

                    axios
                        .delete(`/invoice/${this.$route.params.id}/item/${id}`)
                        .then((response) => {
                            this.getInvoice();
                            this.postSuccess(response.data.message);
                        })
                        .catch((error) => {
                            this.postError(error.response.data.message);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            });
        },
        saveItem() {
            this.loading = true;

            axios
                .post(`/invoice/${this.$route.params.id}/item`, this.item)
                .then((response) => {
                    this.item = false;
                    this.getInvoice();
                    this.postSuccess(response.data.message);
                    this.$root.$emit('modal.close', 'additem');
                })
                .catch((error) => {
                    this.postError(error.response.data.message);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        getInvoice() {
            this.loading = true;

            axios
                .get(`/invoice/${this.$route.params.id}`)
                .then((response) => {
                    this.invoice = response.data.data.invoice;
                    this.invoiceItems = response.data.data.invoiceItems;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        downloadInvoice() {
            window.open(
                `/invoice/${this.$route.params.id}/view?token=${this.userInfo.token}`,
                '_blank'
            );
        },
        updateInvoiceStatus(status, date = false) {
            this.loading = true;

            axios
                .post(`/invoice/${this.$route.params.id}/status`, {
                    status: status,
                    date: date,
                })
                .then((response) => {
                    this.getInvoice();
                    this.postSuccess(response.data.message);
                })
                .catch((error) => {
                    this.postError(error.response.data.message);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        emailInvoice() {
            this.loading = true;

            axios
                .post(
                    `/invoice/${this.$route.params.id}/email?token=${this.userInfo.token}`
                )
                .then((response) => {
                    this.postSuccess(response.data.message);
                })
                .catch((error) => {
                    this.postError(error.response.data.message);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        setAllAsComplete() {
            this.$swal({
                title: `Set all ${this.invoice.Client} invoices as complete`,
                html: `This will set all ${this.invoice.Client} invoices as complete and email them to the email set in the client field. Are you sure?`,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff5151',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
            }).then((result) => {
                if (result.value) {
                    this.loading = true;
                    
                    axios
                        .post(
                            `/invoice/${this.invoice.ClientID}/set-all-complete?token=${this.userInfo.token}`
                        )
                        .then((response) => {
                            this.getInvoice();
                            this.postSuccess(response.data.message);
                        })
                        .catch((error) => {
                            this.postError(error.response.data.message);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            });
        },
        setAllAsPaid() {
            this.$swal({
                title: `Set all unpaid ${this.invoice.Client} invoices as paid`,
                html: `This will set all unpaid ${this.invoice.Client} invoices as paid. Are you sure?`,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff5151',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
            }).then((result) => {
                if (result.value) {
                    this.loading = true;

                    axios
                        .post(
                            `/invoice/${this.invoice.ClientID}/set-all-paid?token=${this.userInfo.token}`
                        )
                        .then((response) => {
                            this.getInvoice();
                            this.postSuccess(response.data.message);
                        })
                        .catch((error) => {
                            this.postError(error.response.data.message);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            });
        },
        downloadInvoiceCSV() { },
    },
};
</script>
