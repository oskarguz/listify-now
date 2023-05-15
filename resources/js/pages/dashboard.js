import {createTable} from "../api/tableApi";
import * as localStorageApi from '../api/localStorageApi';

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
    thead.innerHTML = `<tr><th style="width: 50%">ID</th><th>Name</th></tr>`;

    let tbody = document.createElement('tbody');

    for (const [id, name] of checklists) {
        let tr = document.createElement('tr');
        tr.dataset.id = id;
        tr.classList.add('cursor-pointer');

        tr.innerHTML = `<td>${id}</td><td>${name}</td>`;
        tbody.appendChild(tr);
    }

    table.append(thead, tbody);

    document.getElementById(CHECKLIST_FROM_LOCAL_STORAGE_TABLE_CONTAINER_ID).style.display = 'block';

    return createTable(CHECKLIST_FROM_LOCAL_STORAGE_TABLE_ID);
}

window.addEventListener('DOMContentLoaded', (e) => {
    let checklistTable = createTable(CHECKLIST_TABLE_ID);
    let checklistTableFromLocalStorage = createLocalStorageChecklistTable();

    document.querySelector(`#${CHECKLIST_TABLE_ID} tbody`)
        .addEventListener('click', function (e) {
            const id = checklistTable.row( e.target ).node().dataset.id;

            window.location.href = `/checklist/${id}`;
        });

    document.querySelector(`#${CHECKLIST_FROM_LOCAL_STORAGE_TABLE_ID} tbody`)
        .addEventListener('click', function (e) {
            const id = checklistTableFromLocalStorage.row( e.target ).node().dataset.id;

            window.location.href = `/checklist/${id}`;
        });
});
