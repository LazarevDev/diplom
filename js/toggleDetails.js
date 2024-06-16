function toggleDetails(rowId) {
    var row = document.getElementById(rowId);
    if (row.style.display === "none" || row.style.display === "") {
        row.style.display = "table-row";
    } else {
        row.style.display = "none";
    }
}
function updateStatus(id, status) {
var xhr = new XMLHttpRequest();
xhr.open("POST", "scripts/update_status.php", true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

xhr.onreadystatechange = function() {
if (xhr.readyState === XMLHttpRequest.DONE) {
    try {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
            // Update the status display immediately
            var statusElement = document.querySelector(`#status-${id}`);
            if (statusElement) {
                statusElement.className = 'status ' + (status === 'approved' ? 'statusApproved' : 'statusCancelled');
                statusElement.textContent = (status === 'approved' ? 'Оплачен' : 'Отменен');
            }
        } else {
        }
    } catch (e) {
    }
}
};

xhr.send("id=" + id + "&status=" + status);
}