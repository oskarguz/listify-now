import { createApp } from "vue";
import { createPinia } from "pinia";
import ChecklistCreateForm from "../components/ChecklistCreateForm.vue";

const pinia = createPinia();

const app = createApp(ChecklistCreateForm);

app.use(pinia);
app.mount('#app');
