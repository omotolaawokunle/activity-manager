import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from "vue";
import { SetupCalendar } from "v-calendar";

window.Alpine = Alpine;

Alpine.start();

import Sidebar from "@/components/Sidebar.vue";
import Alert from "@/components/Alert.vue";
import Calendar from "@/components/Calendar.vue";

const app = createApp({
    data: () => {
        return {
            sidebarOpen: false,
        };
    },
    methods: {
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        submitDelete() {
            this.$refs.deleteForm.submit();
        },
    },
});
app.use(SetupCalendar, {});
app.component("alert", Alert);
app.component("sidebar", Sidebar);
app.component("calendar", Calendar);
app.mount("#app");
