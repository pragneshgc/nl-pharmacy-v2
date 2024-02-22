import { ref } from 'vue';
import { useRoute } from 'vue-router';
// import { postError } from '../mixins/errors';
import mitt from 'mitt';

export function useStats() {
  const route = useRoute();
  const emitter = mitt();

  const checkboxStatus = ref(false);
  const countTimer = ref(null);
  const duplicateReference = ref(0);
  const loaded = ref(false);
  const loadingPendingPharmacy = ref(false);
  const mapping = {
    safety: 'safety check',
    new: 'new',
    approved: 'approved',
    dpd: 'dpd',
    ups: 'ups',
    dhl: 'dhl',
    rml: 'rml',
    awaiting: 'awaiting shipped',
    shipped: 'shipped',
    onhold: 'onhold',
    queried: 'queried',
    rejected: 'rejected',
    cancelled: 'cancelled',
    return: 'returned',
  };
  const onHoldOrders = ref([]);
  const orderFilter =
    route.query.orderFilter ||
      localStorage.getItem('dashboard.orderFilter')
      ? localStorage.getItem('dashboard.orderFilter')
      : userInfo.role == 20 || userInfo.role == 19
        ? 'approved'
        : 'new';
  const pendingOrderAlerts = ref([]);
  const pendingPrescriberCount = ref(0);
  const pendingPharmacyOrders = ref([]);
  const pendingPharmacyOrdersCount = ref(0);
  const roleVisibility = ref({
    60: [],
    50: [],
    40: [],
    35: [],
    30: [],
    29: [],
    25: [],
    20: [],
    19: [],
    10: [],
    5: [],
  });
  const statistics = ref({});


  const getStatistics = () => {
    axios
      .get('/statistics')
      .then((response) => {
        statistics.value = response.data.data;
        statistics.value.total = response.data.data.total;
        loaded.value = true;
      })
      .catch((error) => {
        postError(error.response.data.message);
      });
  }

  const getCount = (refresh = false) => {
    loadingPendingPharmacy.value = true;
    if (refresh) {
      this.getOrderAlerts();
      this.checkOrders(() => {
        this.getCount();
        this.getOnHoldOrders();
        emitter.emit('alertupdate');
      });
    } else {
      axios
        .get('/api/check-orders/results')
        .then((response) => {
          pendingPrescriberCount.value = response.data.data.pendingPrescriberCount;
          pendingPharmacyOrders.value = response.data.data.pendingPharmacyOrders;
          pendingPharmacyOrdersCount.value = response.data.data.pendingPharmacyOrdersCount;
        })
        .catch((error) => {
          postError(error.response.data.message);
        })
        .finally(() => {
          loadingPendingPharmacy.value = false;
          emitter.emit('alertupdate');
        });
    }
  }

  return {
    checkboxStatus,
    countTimer,
    duplicateReference,
    loaded,
    loadingPendingPharmacy,
    mapping,
    onHoldOrders,
    orderFilter,
    pendingOrderAlerts,
    pendingPrescriberCount,
    pendingPharmacyOrders,
    pendingPharmacyOrdersCount,
    roleVisibility,
    statistics,

    getCount,
    getStatistics,
  }
}
