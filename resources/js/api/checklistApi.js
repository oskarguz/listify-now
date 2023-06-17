import axios from "axios";

export function create(checklist) {
    return axios.post('/checklist/create', checklist);
}

export function updateName(id, name) {
    return axios.post(`/checklist/${id}/update`, { name });
}

export function updateVisibility(id, visibility) {
    return axios.post(`/checklist/${id}/update`, { visibility });
}

export function createItem(checklistId, item) {
    return axios.post(`/checklist/${checklistId}/items/create`, item);
}

export function updateItemDescription(checklistId, id, description) {
    return axios.post(`/checklist/${checklistId}/items/${id}/update`, { description })
}

export function updateItemChecked(checklistId, id, checked) {
    return axios.post(`/checklist/${checklistId}/items/${id}/update`, { checked })
}

export function deleteItem(checklistId, id) {
    return axios.post(`/checklist/${checklistId}/items/${id}/destroy`);
}

export function deleteChecklist(checklistId) {
    return axios.post(`/checklist/${checklistId}/destroy`);
}
