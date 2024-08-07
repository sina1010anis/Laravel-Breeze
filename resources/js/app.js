import './bootstrap';

import {createApp} from 'vue/dist/vue.esm-bundler';
import TestVue from "./Components/TestVue.vue";
import 'bootstrap/dist/css/bootstrap.css'

const app = createApp({

    data : ()=>({

        test_vue : 'Hello as Vue...!'

    }),

    components:{

        TestVue:TestVue

    }

})


app.mount('#app')
