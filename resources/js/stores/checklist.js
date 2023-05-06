import { defineStore, acceptHMRUpdate } from "pinia";
import {computed, ref, watch} from "vue";
import * as api from "../api/checklistApi";

export const useChecklistStore = defineStore('checklist', () => {
    const id = ref(null);
    const name = ref('New list');
    const items = ref(new Map());

    const pendingRequestsCount = ref(0);
    const url = ref(window.location.href);

    // PRIVATE METHODS
    function modifyPendingRequestsCount(value) {
        pendingRequestsCount.value = pendingRequestsCount.value + value;
        if (pendingRequestsCount.value < 0) {
            pendingRequestsCount.value = 0;
        }
    }

    // GETTERS
    const hasPendingRequest = computed(() => pendingRequestsCount.value > 0)
    const getUrl = computed(() => url.value);

    // WATCHERS
    watch(id, async (newId, oldId) => {
        let newUrl;
        let origin = window.location.origin;
        if (newId && !oldId) {
            newUrl = `${origin}/checklist/${newId}`;
            window.history.pushState({}, `${name} - Listify Now`, newUrl);
        }
        if (!newId && oldId) {
            newUrl = `${origin}/checklist/create`;
            window.history.pushState({}, 'Create new checlist - Listify Now', newUrl);
        }

        if (newUrl) {
            url.value = newUrl;
        }
    });

    // ACTIONS
    function $reset() {
        id.value = null;
        name.value = '';
        items.value = new Map();
        pendingRequestsCount.value = 0;
    }

    function create() {
        return new Promise((resolve, reject) => {
            let result = { message: '' };

            modifyPendingRequestsCount(1);
            api.create({
                name: name.value,
                items: items.value,
            }).then((response) => {
                modifyPendingRequestsCount(-1);

                let checklist = response.data;

                id.value = checklist.id;
                // @TODO save id somewhere on client device

                result.message = 'List has been created';
                return resolve(result);
            }).catch((err) => {
                modifyPendingRequestsCount(-1);

                if (err.response) {
                    result.message = err.response.data.message;
                }
                if (!result.message) {
                    result.message = err.message || String(err);
                }

                return reject(result);
            });
        });
    }

    function updateName(_name) {
        return new Promise((resolve, reject) => {
            let result = { message: '' };

            name.value = _name;
            if (!id.value) {
                result.message = 'Name has been updated';
                return resolve(result);
            }

            modifyPendingRequestsCount(1);
            api.updateName(id.value, name.value).then(() => {
                modifyPendingRequestsCount(-1);

                result.message = 'Name has been updated';
                return resolve(result);
            }).catch((err) => {
                modifyPendingRequestsCount(-1);

                if (err.response) {
                    result.message = err.response.data.message;
                }
                if (!result.message) {
                    result.message = err.message || String(err);
                }

                return reject(result);
            });
        });
    }

    function init(checklist) {
        if (checklist.hasOwnProperty('id')) {
            id.value = checklist.id;
        }
        if (checklist.hasOwnProperty('name')) {
            name.value = checklist.name;
        }
        if (checklist.hasOwnProperty('items')) {
            items.value = new Map(checklist.items);
        }
    }

    return { id, name, items, hasPendingRequest, getUrl, $reset, create, updateName, init };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useChecklistStore, import.meta.hot));
}
