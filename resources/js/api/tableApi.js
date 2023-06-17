import DataTable from "datatables.net-dt";

export function createTable(elementId) {
    return new DataTable(`#${elementId}`, {
        scrollX: true,
        dom: 'tr<"flex justify-between"ip>',
        language: {
            "decimal":        "",
            "emptyTable":     "No data available",
            "info":           "_START_ to _END_ of _TOTAL_ entries",
            "infoEmpty":      "Showing 0 to 0 of 0 entries",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Show _MENU_ entries",
            "loadingRecords": "Loading...",
            "processing":     "",
            "search":         "Search:",
            "zeroRecords":    "No matching records found",
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       ">>",
                "previous":   "<<"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });
}
