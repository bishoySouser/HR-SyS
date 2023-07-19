import { createApp } from "vue/dist/vue.esm-bundler";
import EmployeeCreate from "./components/employee/create.vue";
import EmployeeEdit from "./components/employee/edit.vue";

createApp({
    components: {
        EmployeeCreate,
        EmployeeEdit
    },
    data: () => ({
        
    }),
    mounted() {
        
    },
    methods: {
       
    },
}).mount("#app");