document.getElementById('searchIcon').addEventListener('mouseover', function() {
    var searchInput = document.getElementById('searchInput');
    searchInput.classList.add('active');
});




$(document).ready(function() {
    $(".mySelect").on("click", function(event) {
        event.preventDefault();
    });
});
