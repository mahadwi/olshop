import 'flowbite';
import './bootstrap'
import '../css/app.css';
import { router } from '@inertiajs/vue3'
import { initFlowbite } from 'flowbite';
import { usePage } from '@inertiajs/vue3';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PrimeVue from 'primevue/config';

router.on('success', (event) => {
  initFlowbite()
})

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Olshop';

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VueTailwindDatepicker)
            .use(VueDatePicker)
            .use(PrimeVue)
            .mixin({
                methods: {
                    // can: function (permissions) {
                    //     var allPermissions = this.$page.props.auth.can;
                    //     var hasPermission = false;
                    //     permissions.forEach(function (item) {
                    //         if (allPermissions[item]) hasPermission = true;
                    //     });
                    //     return hasPermission;
                    // },
                    lang: function () {
                        return usePage().props.language.original;
                    }
                },
            })
            .mount(el)
    },
});