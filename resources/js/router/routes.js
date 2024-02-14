import { canUserAccessModule } from "../helpers";

let hidden = appInfo.hidden;

const routes = [
    {
        path: "/",
        name: "in tray",
        // component: Dashboard
        meta: { minRole: 5 },
        component: () =>
            import(
                /* webpackChunkName: "Dashboard" */ "../pages/Dashboard.vue"
            ),
    },
];

export default routes;