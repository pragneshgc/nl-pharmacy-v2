<template>
   content
</template>
<script>
import Footer from './Footer.vue'

export default {
    props: ['sidebarVisible'],
    components: {
        Footer
    },
    data: function () {
        return {
            appInfo: appInfo
        }
    },
    computed: {
        isDemo() {
            return this.appInfo.mode == 'local' || this.appInfo.mode == 'staging' || this.appInfo.mode == 'demo';
        }
    },
}
</script>
