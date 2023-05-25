import {acceptHMRUpdate, defineStore} from "pinia";
import { computed, ref } from "vue";
import { getLoggedUser } from "../api/authApi";

export const useAuthStore = defineStore('auth', () => {
   const id = ref('');
   const name = ref('');
   const avatar = ref('');

   const getId = computed(() => id.value);
   const getName = computed(() => name.value);
   const getAvatar = computed(() => avatar.value);
   const isLogged = computed(() => id.value.length > 0);

   function logout() {
       id.value = '';
       name.value = '';
   }

   async function refreshState() {
       try {
           const user = await getLoggedUser();

           id.value = user.id || '';
           name.value = user.name || '';
           avatar.value = user.avatar || '';

           return id.value;
       } catch (error) {
           return '';
       }
   }

   return { getId, getName, getAvatar, isLogged, logout, refreshState };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useAuthStore, import.meta.hot));
}
