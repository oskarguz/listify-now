import axios from "axios";

export function create(checklist) {
    return axios.post('/checklist/create', checklist);
}

export function updateName(id, name) {
    return axios.post(`/checklist/${id}/update`, { name });
}
