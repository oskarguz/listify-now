import DataTable from "datatables.net-dt";

export function createTable(elementId) {
    return new DataTable(`#${elementId}`, {
        scrollX: true,
        dom: 'tr<"flex justify-between"ip>',
    });
}
