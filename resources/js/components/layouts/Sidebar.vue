<template>
    <div class="sidebarWrapper blue-template" :class="{ 'collapsed': !sidebarVisible }">
        <ul class="sidebar">
            <li class="sidebarSection parentActive">
                <ul class="nav">
                    <li title="Shipping" style="background: #3c8aa8;" v-if="userInfo.shipping_role > 0">
                        <a class="sidebar-link" :href="appInfo.shipping">
                            <i class="fa fa-truck"></i>
                            Shipping
                        </a>
                    </li>

                    <li title="Inventory" style="background: #006855;" v-if="userInfo.inventory_role > 0">
                        <a class="sidebar-link" :href="appInfo.inventory">
                            <i class="fa fa-barcode"></i>
                            Inventory
                        </a>
                    </li>

                    <li>
                        <router-link title="In Tray" tag="li" to="/" exact v-if="userInfo.role > 4" class="sidebar-link">
                            <i class="fa fa-home"></i>
                            In Tray
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Overview" v-if="(userInfo.role == 60 || userInfo.role == 35)" tag="li"
                            class="sidebar-link" to="/overview" exact>
                            <i class="fa fa-bar-chart"></i>
                            Overview
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Prescription Pool"
                            v-if="userInfo.role == 19 || userInfo.role == 20 || userInfo.role >= 50" tag="li"
                            to="/prescription-pool" exact class="sidebar-link">
                            <i class="fa fa-files-o"></i>
                            Prescription Pool
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Reports" tag="li" to="/reports" exact class="sidebar-link"
                            v-if="userInfo.role > 4 && userInfo.role != 19">
                            <i class="fa fa-file-text-o"></i>
                            Reports
                        </router-link>
                    </li>

                    <li>
                        <router-link title="POM Register" tag="li" to="/register" exact class="sidebar-link"
                            v-if="userInfo.role > 4 && userInfo.role != 19">
                            <i class="fa fa-book" aria-hidden="true"></i>
                            POM Register
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Products"
                            v-if="userInfo.role == 4 || userInfo.role == 20 || userInfo.role >= 30" tag="li" to="/products"
                            exact class="sidebar-link">
                            <i class="fa fa-cubes"></i>
                            Products
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Dispensing Data" v-if="userInfo.role == 20 || userInfo.role >= 30" tag="li"
                            to="/dispensing-data" exact class="sidebar-link">
                            <i class="fa fa-cube"></i>
                            Dispensing Data
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Labels" v-if="canUserAccessModule(
                            appInfo.active_modules,
                            appInfo.module_roles,
                            'Labels', userInfo.pharmacy_role_id)" tag="li" to="/labels" exact class="sidebar-link"
                            style="display: flex;">
                            <i class="fa fa-tags"></i>
                            <div style="padding-left: 12px; margin-bottom: -14px; margin-top: -7px;">
                                Cautionary & Advisory Labels
                            </div>
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Additional Information" v-if="canUserAccessModule(
                            appInfo.active_modules,
                            appInfo.module_roles,
                            'Labels', userInfo.pharmacy_role_id)" tag="li" to="/additional-information" exact
                            class="sidebar-link" style="display: flex;">
                            <i class="fa fa-info-circle"></i>
                            <div style="padding-left: 12px; margin-bottom: -14px; margin-top: -7px;">
                                Additional Information
                            </div>
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Clients" v-if="canUserAccessModule(
                            appInfo.active_modules,
                            appInfo.module_roles,
                            'Clients', userInfo.pharmacy_role_id)" tag="li" to="/clients" exact class="sidebar-link">
                            <i class="fa fa-address-book-o"></i>
                            Clients
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Invoices" v-if="canUserAccessModule(
                            appInfo.active_modules,
                            appInfo.module_roles,
                            'Invoices', userInfo.pharmacy_role_id)" tag="li" to="/invoices" exact class="sidebar-link">
                            <i class="fa fa-calculator"></i>
                            Invoices
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Blacklist" v-if="canUserAccessModule(
                            appInfo.active_modules,
                            appInfo.module_roles,
                            'Blacklist', userInfo.pharmacy_role_id)" tag="li" to="/blacklist" exact
                            class="sidebar-link">
                            <i class="fa fa-ban"></i>
                            Blacklist
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Settings"
                            v-if="userInfo.role == 19 || userInfo.role == 20 || userInfo.role >= 30" tag="li" to="/settings"
                            exact class="sidebar-link">
                            <i class="fa fa-cogs"></i>
                            Settings
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Users" v-if="userInfo.role >= 50" tag="li" to="/users" exact
                            class="sidebar-link">
                            <i class="fa fa-users"></i>
                            Users
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Prescribers" v-if="userInfo.role >= 50 || userInfo.role == 35" tag="li"
                            to="/prescribers" exact class="sidebar-link">
                            <i class="fa fa-heartbeat"></i>
                            Prescribers
                        </router-link>
                    </li>

                    <li>
                        <router-link title="Logs" v-if="canUserAccessModule(
                            appInfo.active_modules,
                            appInfo.module_roles,
                            'Logs', userInfo.pharmacy_role_id)" tag="li" to="/logs" class="sidebar-link">
                            <i class="fa fa-hdd-o"></i>
                            Logs
                        </router-link>
                    </li>

                </ul>
            </li>
            <li class="collapse-menu-section">
                <a class="sidebar-link collapse-menu-link" title="Collapse menu" @click="$emit('sidebartoggle')"
                    href="javascript:;">
                    <i class="fa fa-caret-left" :class="{ 'fa-caret-right': !sidebarVisible }"></i>
                    Collapse menu
                </a>
            </li>
        </ul>

    </div>
</template>
<script setup>
import { canUserAccessModule } from '../../helpers';
const props = defineProps(['sidebarVisible']);
</script>
