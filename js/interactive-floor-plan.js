jQuery(document).ready(function($) {
    // Initialize the interactive floor plan builder
    $('#components li').draggable({
        helper: 'clone',
        revert: 'invalid'
    });

    $('#floor-plan-canvas').droppable({
        accept: '#components li',
        drop: function(event, ui) {
            var component = ui.helper.data('component');
            var element = $('<div class="floor-plan-element"></div>').text(component);
            $(this).append(element);
            element.draggable({
                containment: '#floor-plan-canvas'
            }).resizable();
        }
    });
});