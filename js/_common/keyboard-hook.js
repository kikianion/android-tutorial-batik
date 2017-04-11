$(document).keydown(function(e) {
    switch(e.which) {
        case 116: // f5
            reload_route();
            e.preventDefault(); // prevent the default action (scroll / move caret)
            break;

        case 115: //f4
            $("#toogle-left-pane").click();
            break;

        default: return; // exit this handler for other keys
    }
});
