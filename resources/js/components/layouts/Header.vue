<template>
    <header>
        <div class="logo"><a href="/"></a></div>
        <form @submit.prevent="search" autocomplete="on">
            <div class="formItemsGroup floatLeft" style="display: flex;justify-content: center;align-items: center;">
                <input v-model="orderID" class="tBox tBoxSize02" type="text" placeholder="ESA Order ID" />
                <input v-model="refNo" class="tBox tBoxSize02" type="text" placeholder="Client Reference Number" />
                <button title="Search for orders by reference number or ESA order ID"
                    :disabled="orderID == '' && refNo == ''" class="btn btnSize02 tertiaryBtn" type="submit">
                    Search
                </button>
            </div>
        </form>
        
        <div class="user">
            <ul>
                <li @dblclick="switchStyle()" style="user-select: none;">
                    <a href="javascript:;">
                        <span>
                            <i class="fa fa-user"></i>
                            <b>{{ userInfo.name }} {{ userInfo.surname }}</b>
                            <br>
                            Login time: {{ userInfo.loginAt }}
                        </span>
                    </a>
                </li>
                <li class="big-icon" title="App Info" style="padding: 0px;">
                    <router-link style="padding: 0 10px;" to="/info">
                        <i class="fa fa-info"></i>
                    </router-link>
                </li>
                <li class="big-icon"
                    :title="messageCount > 0 ? `${messageCount} notifications require your attention` : 'No new notifications'"
                    style="padding: 0 10px; position: relative;" @click="viewAlerts()">
                    <i class="fa fa-envelope"></i>
                    <span class="badge" v-if="messageCount > 0"
                        style="position: absolute;right: 5px;color: white;background:#ff8944;bottom: -5px; border: 1px solid transparent;
                        font-size: 14px;width: 20px;height: 20px;border-radius: 5px;display: flex; justify-content:center; align-items:center;">
                        {{ messageCount }}
                    </span>
                </li>
                <!-- <li>
                    <a href="javascript:;">
                        <i class="fa fa-question-circle"></i>
                        FAQ
                    </a>
                </li> -->
                <li class="big-icon" title="Logout">
                    <span @click="logout()" class="clickable">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                    </span>
                </li>
            </ul>
        </div>
    </header>
</template>


<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import axios from 'axios';

const userInfo = ref(userInfo);
const orderID = ref('');
const refNo = ref('');
const messageCount = ref(0);

const currentRouteName = computed(() => {
  return $route.value.name;
});

const switchStyle = () => {
  let style = 'default-style';

  if ($store.state.style == 'default-style') {
    style = 'redesign-style';
  }

  $store.commit('changeStyle', style);
  localStorage.setItem('interfacestyle', style);
};

const search = () => {
  if (orderID.value == '' && refNo.value != '') {
    getOrderID(refNo.value, () => {
      if (Array.isArray(orderID.value)) {
        $root.emit('showduplicates', { duplicateReference: refNo.value });
        postError(`Duplicate orders for reference number ${refNo.value} found. Displaying orders.`);
        orderID.value = '';
        refNo.value = '';
      } else {
        $router.push({ name: 'prescription', params: { id: orderID.value } });
        orderID.value = '';
        refNo.value = '';
      }
    });
  } else {
    $root.emit('prescriptionloading');
    $router.push({ name: 'prescription', params: { id: orderID.value } });
    orderID.value = '';
    refNo.value = '';
  }
};

const getOrderID = (refNo, cb) => {
  axios.get('/order/reference/' + refNo)
    .then((response) => {
      orderID.value = response.data.data;
      cb();
    })
    .catch((error) => {
      postError(`No orders for reference number ${refNo} found!`);
    });
};

const logout = () => {
  window.loggingOut = true;

  axios.post('/logs/page-exit')
    .then((response) => {
      axios.post('/logout')
        .then((response) => {
          location.replace('/login');
        })
        .catch((error) => {
          postError(error);
        });
    })
    .catch((error) => {
      postError(error);
    });
};

const getAlertNotifications = () => {
  axios.get('/dashboard/alerts-count')
    .then((response) => {
      messageCount.value = response.data.data;
    })
    .catch((error) => {
      postError(error.response.data.message);
    });
};

const viewAlerts = () => {
  if (currentRouteName.value == 'in tray' || currentRouteName.value == 'dashboard') {
    $root.emit('changefilter', { filter: 'ordercount' });
  } else {
    localStorage.setItem('dashboard.orderFilter', 'ordercount');
    $router.push({ name: 'in tray' });
  }
};

onMounted(() => {
  getAlertNotifications();
  $root.on('orderupdate', getAlertNotifications);
  $root.on('alertupdate', () => {
    getAlertNotifications();
  });
});

onBeforeUnmount(() => {
  $root.off('alertupdate', () => {
    getAlertNotifications();
  });
});  
</script>
