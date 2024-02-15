<template>
    <div>
        <div class="content">
            <!-- Prescription Stats-->
            <section>
                <div class="prescriptionStats flex-center">
                    <div class="title flex-align-center">Order Statistics</div>
                    <div class="list">
                        <ul v-if="loaded">
                            <li class="list-item-background" v-for="(value, key) in statistics.statistics"
                                v-if="!roleVisibility[userInfo.role].includes(key)" @click="changeOrder(key)"
                                :class="{ active: key == orderFilter }">
                                <span>{{ mapping[key] }}</span>{{ value }}
                                <!-- <a class="smallTextBtn secondaryBtn" :class="{ 'active': key == orderFilter }" href="javascript:;">View</a> -->
                            </li>
                            <li :class="[
                                'ordercount' == orderFilter ? 'active' : '',
                                pendingPharmacyOrdersCount > 0 ? 'blink_me' : '',
                            ]" class="list-item-background" @click="orderFilter = 'ordercount'"
                                v-if="!roleVisibility[userInfo.role].includes('ordercount')">
                                <span>Alert</span>{{ pendingPharmacyOrdersCount }}
                            </li>
                        </ul>
                        <ul style="overflow: hidden" v-else>
                            <li>
                                <div class="loader loader-relative" style="">Loading...</div>
                            </li>
                        </ul>
                    </div>
                    <div v-if="loaded" class="total flex-align-center clickable">
                        <span>Total</span>
                        {{ statistics.total }}
                        <a href='/orders/csv?page=1&limit=1000&f={"dashboard": true}&strict=true'
                            class="btn smallTextBtn secondaryBtn" title="Download dashboard orders">Download</a>
                    </div>
                </div>
            </section>
            <!-- End Prescription Stats-->
        </div>
    </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useStats } from '../composables/useStats';

const {
    loaded, statistics,
    getStatistics, roleVisibility,
    mapping,
    orderFilter,
    pendingPharmacyOrdersCount,
    getCount
} = useStats();

onMounted(() => {
    getStatistics();
    getCount();
})
</script>
