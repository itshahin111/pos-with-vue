import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import "bootstrap/dist/css/bootstrap.css";
import "./Assets/css/main.css";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
import NProgress from "nprogress";
import { router } from "@inertiajs/vue3";

// Import Vue Toastification
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found!');
}


createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.use(Toast); // Register Vue Toastification
        app.component("EasyDataTable", Vue3EasyDataTable);

        app.mount(el);
    },
});

// Appear @ Route Start
router.on("start", () => {
    NProgress.start();
});

// Appear @ Route Finish
router.on("finish", () => {
    NProgress.done();
});
