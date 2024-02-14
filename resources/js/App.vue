<template>
    <Transition name="slide-down" mode="out-in">
        <div v-if="isDemo" class="infoBox warning thin-error">
            <p>Application is running in DEMO mode!</p>
        </div>
    </Transition>
    <Header />
    <Sidebar @sidebartoggle="toggleSidebar()" :sidebarVisible="sidebarVisible" />
    <Content :sidebarVisible="sidebarVisible" />
</template>
<script setup>
import { computed, ref } from 'vue';

import Header from './components/layouts/Header.vue';
import Sidebar from './components/layouts/Sidebar.vue';
import Content from './components/layouts/Content.vue';

const sidebarVisible = ref(true);

const isDemo = computed(() => {
    return true;
});

const toggleSidebar = () => {
    if (sidebarVisible.value == true) {
        sidebarVisible.value = false;
    } else {
        sidebarVisible.value = true;
    }
}
</script>
