import { defineStore, acceptHMRUpdate } from "pinia";
import { computed, reactive, ref, watch } from "vue";
import * as api from "../api/checklistApi";
import * as localStorageApi from '../api/localStorageApi';
import { useAuthStore } from "./auth";

export const useChecklistStore = defineStore('checklist', () => {
    const id = ref(null);
    const name = ref('New list');
    const createdBy = reactive({
        id: '',
        name: '',
    });
    const items = ref([]);

    const pendingRequestsCount = ref(0);
    const url = ref(window.location.href);

    const auth = useAuthStore();

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
    const isCreatedByMe = computed(() => auth.isLogged && createdBy.id && auth.getId === createdBy.id)

    // WATCHERS
    watch(id, async (newId, oldId) => {
        let newUrl;
        let origin = window.location.origin;
        if (newId && !oldId) {
            newUrl = `${origin}/checklist/${newId}`;
            window.history.pushState(null, `${name.value} - Listify Now`, newUrl);
            document.title = `${name.value} - Listify Now`;
        }
        if (!newId && oldId) {
            newUrl = `${origin}/checklist/create`;
            window.history.pushState(null, 'Create new checlist - Listify Now', newUrl);
            document.title = 'Create new checlist - Listify Now';
        }

        if (newUrl) {
            url.value = newUrl;
        }
    });

    // ACTIONS
    function $reset() {
        id.value = null;
        name.value = '';
        createdBy.id = '';
        createdBy.name = '';
        items.value = [];
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
                createdBy.id = checklist?.created_by?.id || '';
                createdBy.name = checklist?.created_by?.name || '';
                items.value = checklist.items;

                if (!auth.isLogged) {
                    localStorageApi.addChecklistId(checklist.id, checklist.name);
                }

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

                localStorageApi.addChecklistId(id.value, name.value);

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
            items.value = checklist.items;
        }
        if (checklist.hasOwnProperty('created_by')) {
            createdBy.id = checklist.created_by?.id || '';
            createdBy.name = checklist.created_by?.name || '';
        }
    }

    function createItem(description, checked) {
        return new Promise((resolve, reject) => {
            let result = { message: '' };

            const item = { description, checked };
            if (!id.value) {
                const tmpId = Date.now() + description;
                item.id = tmpId;
                items.value.push(item);

                result.message = 'Item has been created';
                return resolve(result);
            }

            api.createItem(id.value, item).then((response) => {
                let createdItem = response.data;
                items.value.push(createdItem);

                result.message = 'Item has been created';
                return resolve(result);
            }).catch((err) => {
                if (err.response) {
                    result.message = err.response.data.message;
                }
                if (!result.message) {
                    result.message = err.message || String(err);
                }

                return reject(result);
            })
        });
    }

    async function deleteChecklist() {
        if (!id.value) {
            return false;
        }
        if (!auth.isLogged) {
            return false;
        }

        try {
            await api.deleteChecklist(id.value);

            return true;
        } catch (err) {
            let error;
            if (err.response) {
                error = err.response.data.message;
            }
            if (!error) {
                error.message = err.message || String(err);
            }

            throw error;
        }
    }

    function deleteItem(itemId) {
        return new Promise((resolve, reject) => {
            let result = { message: '' };

            if (!id.value) {
                items.value = items.value.filter((el) => el.id !== itemId);

                result.message = 'Item has been deleted';
                return resolve(result);
            }

            api.deleteItem(id.value, itemId).then((response) => {
                items.value = items.value.filter((el) => el.id !== itemId);

                result.message = 'Item has been deleted';
                return resolve(result);
            }).catch((err) => {
                if (err.response) {
                    result.message = err.response.data.message;
                }
                if (!result.message) {
                    result.message = err.message || String(err);
                }

                return reject(result);
            })
        });
    }

    function updateItemDescription(itemId, description) {
        return new Promise((resolve, reject) => {
            let result = { message: '' };
            const itemIndex = items.value.findIndex((el) => el.id === itemId);

            if (!id.value) {
                items.value[itemIndex].description = description;

                result.message = 'Item has been updated';
                return resolve(result);
            }

            api.updateItemDescription(id.value, itemId, description).then((response) => {
                items.value[itemIndex].description = description;

                result.message = 'Item has been created';
                return resolve(result);
            }).catch((err) => {
                if (err.response) {
                    result.message = err.response.data.message;
                }
                if (!result.message) {
                    result.message = err.message || String(err);
                }

                return reject(result);
            })
        });
    }

    function updateItemChecked(itemId, checked) {
        return new Promise((resolve, reject) => {
            let result = { message: '' };
            const itemIndex = items.value.findIndex((el) => el.id === itemId);

            if (!id.value) {
                items.value[itemIndex].checked = checked;

                result.message = 'Item has been updated';
                return resolve(result);
            }

            api.updateItemChecked(id.value, itemId, checked).then((response) => {
                items.value[itemIndex].checked = checked;

                result.message = 'Item has been created';
                return resolve(result);
            }).catch((err) => {
                if (err.response) {
                    result.message = err.response.data.message;
                }
                if (!result.message) {
                    result.message = err.message || String(err);
                }

                return reject(result);
            })
        });
    }

    return { id, name, createdBy, items, hasPendingRequest, getUrl, isCreatedByMe, $reset, create, updateName, init, createItem, deleteChecklist, deleteItem, updateItemDescription, updateItemChecked };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useChecklistStore, import.meta.hot));
}
