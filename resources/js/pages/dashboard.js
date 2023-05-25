import {createTable} from "../api/tableApi";
import * as localStorageApi from '../api/localStorageApi';
import * as checklistApi from '../api/checklistApi';

const CHECKLIST_TABLE_ID = 'checklistsDatatables';
const CHECKLIST_FROM_LOCAL_STORAGE_TABLE_ID = 'localStorageChecklistsDatatable';
const CHECKLIST_FROM_LOCAL_STORAGE_TABLE_CONTAINER_ID = 'localStorageChecklistsDatatableContainer';

const createLocalStorageChecklistTable = () => {
    const checklists = localStorageApi.getAllChecklists();

    if (!checklists.size) {
        return;
    }

    let table = document.getElementById(CHECKLIST_FROM_LOCAL_STORAGE_TABLE_ID);

    let thead = document.createElement('thead');
    thead.innerHTML = `<tr><th style="text-align: center">ID</th><th style="text-align: center">Name</th><th data-sortable="false"></th></tr>`;

    let tbody = document.createElement('tbody');

    for (const [id, name] of checklists) {
        let tr = document.createElement('tr');
        tr.dataset.id = id;
        tr.classList.add('cursor-pointer');

        tr.innerHTML = `<td class="text-center">${id}</td><td class="text-center">${name}</td><td class="text-center"><button class="bg-red-500 p-2 rounded deleteBtn mx-auto"><i class="fi-br-trash align-middle"></i></button></td>`;
        tbody.appendChild(tr);
    }

    table.append(thead, tbody);

    document.getElementById(CHECKLIST_FROM_LOCAL_STORAGE_TABLE_CONTAINER_ID).style.display = 'block';

    return createTable(CHECKLIST_FROM_LOCAL_STORAGE_TABLE_ID);
}

window.addEventListener('DOMContentLoaded', (e) => {
    let checklistTable = createTable(CHECKLIST_TABLE_ID);
    let checklistTableFromLocalStorage = createLocalStorageChecklistTable();

    let checklistTableEl = document.querySelector(`#${CHECKLIST_TABLE_ID} tbody`);
    let checklistTableFromLocalStorageEl = document.querySelector(`#${CHECKLIST_FROM_LOCAL_STORAGE_TABLE_ID} tbody`);

    if (checklistTableEl) {
        checklistTableEl.addEventListener('click', function (e) {
            const isDeleteBtn = e.target.closest('.deleteBtn') != null;

            let targetRow = checklistTable.row(e.target.closest('.deleteBtn')?.closest('tr') || e.target).node();
            const id = targetRow.dataset.id;
            if (isDeleteBtn) {
                try {
                    if (confirm('Are you sure?')) { // @TODO replace with some nice alerts :)
                        checklistApi.deleteChecklist(id);
                        checklistTable.row(targetRow).remove().draw();
                    }
                } catch (e) {
                    console.error(e);
                }
                return;
            }

            window.location.replace(`/checklist/${id}`);
        });
    }

    if (checklistTableFromLocalStorageEl) {
        checklistTableFromLocalStorageEl.addEventListener('click', function (e) {
            const isDeleteBtn = e.target.closest('.deleteBtn') != null;

            let targetRow = checklistTableFromLocalStorage.row(e.target.closest('.deleteBtn')?.closest('tr') || e.target).node();
            const id = targetRow.dataset.id;
            if (isDeleteBtn) {
                try {
                    if (confirm('Are you sure?')) { // @TODO replace with some nice alerts :)
                        localStorageApi.removeChecklistId(id);
                        checklistTableFromLocalStorage.row(targetRow).remove().draw();
                    }
                } catch (e) {
                    console.error(e);
                }
                return;
            }

            window.location.replace(`/checklist/${id}`);
        });
    }
});
