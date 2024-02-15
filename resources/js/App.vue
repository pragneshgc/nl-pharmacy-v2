<template>
    <Header />
    <Sidebar @sidebartoggle="toggleSidebar" :sidebarVisible="sidebarVisible" />
    <Content :sidebarVisible="sidebarVisible" />
</template>
<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

import Header from './components/layouts/Header.vue';
import Sidebar from './components/layouts/Sidebar.vue';
import Content from './components/layouts/Content.vue';

const sidebarVisible = ref(true);

const toggleSidebar = () => {
    if (sidebarVisible.value == true) {
        sidebarVisible.value = false;
    } else {
        sidebarVisible.value = true;
    }
}
</script>
