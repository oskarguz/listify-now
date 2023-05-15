const CHECKLIST_ID_PREFIX = 'checklist-id-';

export function addChecklistId(checklistId, checklistName) {
    localStorage.setItem(CHECKLIST_ID_PREFIX + checklistId, String(checklistName));
}

export function removeChecklistId(checklistId) {
    localStorage.removeItem(CHECKLIST_ID_PREFIX + checklistId);
}

export function getAllChecklists() {
    let checklists = new Map();
    for (let i = 0; i < localStorage.length; i++) {
        let key = localStorage.key(i);
        if (key && key.startsWith(CHECKLIST_ID_PREFIX)) {
            checklists.set(key.replace(CHECKLIST_ID_PREFIX, ''), localStorage.getItem(key));
        }
    }
    return checklists;
}
