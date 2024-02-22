<template>
    <table class="table table-hover table-diff mt-20">
        <thead>
            <tr>
                <th>
                    Field
                </th>
                <th>
                    Current Value
                </th>
                <th>
                    New Value
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(change, key) in newObject" :key="key">
                <td :class="[oldObject[key] == newObject[key] || (oldObject[key] == null && newObject[key] == '') ? '' : 'row-danger']">
                    {{ alias[key] ? alias[key].title : '' }} 
                </td>
                <td :class="[oldObject[key] == newObject[key] || (oldObject[key] == null && newObject[key] == '') ? '' : 'row-danger']">
                    <span v-if="key == 'DeliveryID'">{{ getCompanyTitle(oldObject[key]) }}</span>
                    <span v-else-if="key == 'DCountryCode'">{{ getCountryTitle(oldObject[key]) }}</span>
                    <span v-else-if="key == 'CountryCode'">{{ getCountryTitle(oldObject[key]) }}</span>
                    <span v-else>{{ oldObject[key] }}</span>
                </td>
                <td :class="[oldObject[key] == newObject[key] || (oldObject[key] == null && newObject[key] == '') ? '' : 'row-danger']">
                    <span v-if="key == 'DeliveryID'">{{ getCompanyTitle(newObject[key]) }}</span>
                    <span v-else-if="key == 'DCountryCode'">{{ getCountryTitle(newObject[key]) }}</span>
                    <span v-else-if="key == 'CountryCode'">{{ getCountryTitle(newObject[key]) }}</span>
                    <span v-else>{{ newObject[key] }}</span>
                </td>
            </tr>

            <tr v-if="newObjectUPS && newObjectUPS.length != 0">
                <td colspan="3">
                    <h3>UPS Access Point Details</h3> 
                </td>
            </tr>

            <tr v-for="(change, key) in newObjectUPS" :key="key">
                <td :class="[oldObjectUPS[key] != newObjectUPS[key] || !(oldObjectUPS[key] == null && newObjectUPS[key] == '') ? 'row-danger' : '']">
                    AP {{ alias[key] ? alias[key].title : '' }}
                </td>
                <td :class="[oldObjectUPS[key] != newObjectUPS[key] || !(oldObjectUPS[key] == null && newObjectUPS[key] == '') ? 'row-danger' : '']">
                    <span v-if="key == 'DeliveryID'">{{ getCompanyTitle(oldObjectUPS[key]) }}</span>
                    <span v-else-if="key == 'DCountryCode'">{{ getCountryTitle(oldObjectUPS[key]) }}</span>
                    <span v-else-if="key == 'CountryCode'">{{ getCountryTitle(oldObjectUPS[key]) }}</span>
                    <span v-else-if="key == 'APNotificationLanguage'">{{ getCountryTitle(oldObjectUPS[key]) }}</span>
                    <span v-else>{{ oldObjectUPS[key] }}</span>
                </td>
                <td :class="[oldObjectUPS[key] != newObjectUPS[key] || !(oldObjectUPS[key] == null && newObjectUPS[key] == '') ? 'row-danger' : '']">
                    <span v-if="key == 'DeliveryID'">{{ getCompanyTitle(newObjectUPS[key]) }}</span>
                    <span v-else-if="key == 'DCountryCode'">{{ getCountryTitle(newObjectUPS[key]) }}</span>
                    <span v-else-if="key == 'CountryCode'">{{ getCountryTitle(newObjectUPS[key]) }}</span>
                    <span v-else-if="key == 'APNotificationLanguage'">{{ getCountryTitle(newObjectUPS[key]) }}</span>
                    <span v-else>{{ newObjectUPS[key] }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    import { ref, reactive, computed, watch, onCreated } from 'vue';
    import PrescriptionColumns from '../../mixins/constants/prescriptionColumns';
    import axios from 'axios';

    export default {
        props: ['oldObject', 'oldObjectUPS', 'newObject', 'newObjectUPS', 'countriesProp', 'companiesProp', 'getDetails'],
        setup(props, { emit }) {
            const countries = ref([]);
            const companies = ref([]);
            const loadingCountries = ref(true);
            const loadingCompanies = ref(true);

            onCreated(() => {
              if (props.getDetails) {
                getCountries();
                getCompanies();
              }
            });

            const countriesArray = computed(() => {
              return props.getDetails ? countries.value : props.countriesProp;
            });

            const companiesArray = computed(() => {
              return props.getDetails ? companies.value : props.companiesProp;
            });

            const loaded = computed(() => {
              return !loadingCountries.value && !loadingCompanies.value;
            });

            watch(loaded, (newValue, oldValue) => {
              if (newValue) {
                emit('difftable.loaded');
              }
            });

            const getCountries = () => {
              axios.get('/countries')
                .then((response) => {
                  countries.value = response.data.data;
                })
                .catch((error) => {
                  console.warn(error.response.data.message);
                  emit('difftable.error');
                })
                .finally(() => {
                  loadingCountries.value = false;
                });
            };

            const getCompanies = () => {
              axios.get('/delivery-companies')
                .then((response) => {
                  companies.value = response.data.data;
                })
                .catch((error) => {
                  console.warn(error.response.data.message);
                  emit('difftable.error');
                })
                .finally(() => {
                  loadingCompanies.value = false;
                });
            };

            /**
             * Fetches country title by country id
             */
            const getCountryTitle = (id, countries = false) => {
              let title = 'Unknown';

              if (!countries) {
                countries = countriesArray.value;
              }

              // this is not very efficient
              countries.forEach((country) => {
                if (country.CountryID == id) {
                  title = country.Name;
                }
              });

              return title;
            };

            /**
             * Fetches company title by SettingID id
             */
            const getCompanyTitle = (id, companies = false) => {
              let title = 'Unknown';

              if (!companies) {
                companies = companiesArray.value;
              }

              companies.forEach((company) => {
                if (company.SettingID == id) {
                  title = company.Name;
                }
              });

              return title;
            };

            return {
              countries,
              companies,
              loadingCountries,
              loadingCompanies,
              countriesArray,
              companiesArray,
              loaded,
              getCountries,
              getCompanies,
              getCountryTitle,
              getCompanyTitle,
            };        
        },
    };

</script>