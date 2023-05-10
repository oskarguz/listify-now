const CHECKLIST_ID_PREFIX = 'checklist-id-';

export function addChecklistId(checklistId) {
    localStorage.setItem(CHECKLIST_ID_PREFIX + checklistId, String(checklistId));
}

export function removeChecklistId(checklistId) {
    localStorage.removeItem(CHECKLIST_ID_PREFIX + checklistId);
}

export function getAllChecklistIds() {
    let ids = [];
    for (let i = 0; i < localStorage.length; i++) {
        let key = localStorage.key(i);
        if (key && key.startsWith(CHECKLIST_ID_PREFIX)) {
            ids.push(localStorage.getItem(key));
        }
    }
    return ids;
}
