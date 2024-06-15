var modal = document.getElementById("myModal");
var btn = document.getElementById("openModalBtn");
var span = document.getElementsByClassName("close")[0];

function openModal() {
    modal.classList.add("show");
    setTimeout(function() {
        document.querySelector(".modal-content").classList.add("show");
    }, 10);
}

function closeModal() {
    document.querySelector(".modal-content").classList.remove("show");
    setTimeout(function() {
        modal.classList.remove("show");
    }, 500); 
}

btn.onclick = function() {
    openModal();
}

span.onclick = function() {
    closeModal();
}

window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}
