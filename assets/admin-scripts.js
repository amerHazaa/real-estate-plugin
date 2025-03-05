jQuery(document).ready(function($) {
    var currentStep = 0;
    var steps = $('.step');

    function showStep(step) {
        steps.removeClass('active');
        $(steps[step]).addClass('active');
    }

    $('.next-step').on('click', function() {
        currentStep++;
        if (currentStep >= steps.length) {
            currentStep = steps.length - 1;
        }
        showStep(currentStep);
    });

    $('.prev-step').on('click', function() {
        currentStep--;
        if (currentStep < 0) {
            currentStep = 0;
        }
        showStep(currentStep);
    });

    showStep(currentStep);
});