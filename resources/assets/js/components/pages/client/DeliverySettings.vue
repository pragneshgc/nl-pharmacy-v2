<template>
    <section class="mx-auto">
        <div class="container flex">
            <div class="delivery-tabs width-15">
                <template v-for="(service, index) in services">
                    <nav class="delivery-tabs-items" :class="[tab === service ? 'active' : '']" @click="setTab(service)">
                        {{ service }} Configuration
                    </nav>
                </template>
            </div>
            <div class="width-85">

                <div class="space-y-6" v-show="tab === 'ups'">
                    <div>
                        <h3 class="text-center">UPS API Configuration</h3>
                        <hr class="m-3">
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="endpoint">
                                <span>API endpoint</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                <span>Shipper Name</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                <span>Shipper Number</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                <span>Tax Identification Number</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                <span>License Number</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                <span>Password</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6" v-show="tab === 'dpd'">
                    <div>
                        <h3 class="text-center">DPD API Configuration</h3>
                        <hr class="m-3">
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="endpoint">
                                <span>API endpoint</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                <span>Credentials</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                <span>Account Number</span>
                            </label>
                            <input class="" type="text">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6" v-show="tab === 'rml'">
                    <div>
                        <h3 class="text-center">Royal Mail API Configuration</h3>
                        <hr class="m-3">
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="endpoint">
                                <span>API endpoint</span>
                            </label>
                            <input class="" type="text" v-model="form.rml.endpoint">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="name">
                                <span>Token</span>
                            </label>
                            <input class="" type="text" v-model="form.rml.token">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6" v-show="tab === 'dhl'">
                    <div>
                        <h3 class="text-center">DHL Configuration</h3>
                        <hr class="m-3">

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="shipper_name">
                                <span>Shipper Name</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_name">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="shipper_account_number">
                                <span>DHL Shipper Account Number</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_account_number">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="billing_account_number">
                                <span>DHL Billing Account Number</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.billing_account_number">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="vat">
                                <span>VAT Number</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.vat">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="eori">
                                <span>EORI</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.eori">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <hr class="m-3">
                        <h3 class="text-center">Delivery Details</h3>
                        <hr class="m-3">

                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="shipper_name">
                                <span>Shipper Address 1</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_address_1">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="shipper_account_number">
                                <span>Shipper Address 2</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_address_2">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="billing_account_number">
                                <span>Shipper Address 3</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_address_3">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="vat">
                                <span>Shipper Address 4</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_address_4">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="eori">
                                <span>Shipper Postcode</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_postcode">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="eori">
                                <span>Shipper Country Code</span>
                            </label>
                            <select class="browser-default custom-select" v-model="form.dhl.shipper_country_id"
                                name="CountryID">
                                <option v-for="country in countries" :key="country.CountryID" :value="country.CountryID">
                                    {{ country.Name }}
                                </option>
                            </select>

                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="eori">
                                <span>Shipper Telephone</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_telephone">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="eori">
                                <span>Shipper Email</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.shipper_email">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-2">
                            <label class="block font-medium text-sm text-gray-700" for="eori">
                                <span>Contents</span>
                            </label>
                            <input class="" type="text" v-model="form.dhl.contents">
                            <div class="mt-2" style="display: none;">
                                <p class="text-sm text-red-600"></p>
                            </div>
                            <small style="text-align: left;">This tag is used for DHL shipping</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<script>
//import Error from '../../../mixins/errors';

export default {
    //mixins: [Error],
    props: ['services', 'form', 'defaultTab', 'countries'],
    data: function () {
        return {
            userInfo: userInfo,
            tab: this.defaultTab,
        };
    },
    mounted() {
        if (this.services) {
            this.setTab(this.services[0]);
        }
    },
    methods: {
        setTab(tab) {
            this.tab = tab;
        }
    }
}
</script>
