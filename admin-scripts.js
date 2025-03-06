jQuery(document).ready(function($) {
    var currentStep = 0;
    var steps = $('.wizard-popup .step');

    function showStep(step) {
        steps.hide();
        $(steps[step]).show();
    }

    // فتح نافذة Wizard عند الضغط على زر "إضافة برج جديد" أو "إضافة شقة جديدة"
    $('.open-wizard').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        $(target).fadeIn();
        $('body').addClass('modal-open'); // تعطيل التمرير بالخلفية
        showStep(0); // بدء الـ Wizard بالخطوة الأولى
    });

    // إغلاق النافذة عند النقر على زر الإغلاق أو خارج النافذة
    $('.wizard-popup .close-wizard, .wizard-popup-overlay').on('click', function() {
        $('.wizard-popup').fadeOut();
        $('body').removeClass('modal-open');
    });

    $('.next-step').on('click', function() {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });

    $('.prev-step').on('click', function() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // عند تحميل الصفحة، يتم عرض الخطوة الأولى فقط داخل النافذة المنبثقة
    showStep(0);
});
