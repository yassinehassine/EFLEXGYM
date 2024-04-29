document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed.");
    // Désactiver les gestionnaires d'événements AJAX pour le spinner de chargement
    $(document).off('ajaxStart ajaxStop');

    // Cacher le spinner de chargement s'il a l'ID "loading-spinner"
    $('#loading-spinner').hide();
});