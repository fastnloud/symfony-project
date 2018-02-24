$(document).ready(function() {
    var grid = $('.grid').masonry({
        itemSelector    : '.grid-item',
        columnWidth     : '.grid-sizer',
        horizontalOrder : true
    });

    grid.imagesLoaded( function() {
        grid.masonry('layout');
    });
});