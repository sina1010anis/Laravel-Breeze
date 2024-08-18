import './bootstrap';

import {createApp, h} from 'vue/dist/vue.esm-bundler';
import TestVue from "./Components/TestVue.vue";
import 'bootstrap/dist/css/bootstrap.css'

import { createInertiaApp } from '@inertiajs/vue3'
createInertiaApp({
    id: 'app',
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
        .use(plugin)
        .mount(el)
    },
})

const app = createApp({

    data : ()=>({

        test_vue : 'Hello as Vue...!'

    }),

    components:{

        TestVue:TestVue

    }

})


app.mount('#app')
