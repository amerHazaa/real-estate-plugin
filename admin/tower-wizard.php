<div class="wrap">
    <h1>إضافة برج جديد</h1>
    <form id="tower-wizard-form">
        <div class="step step-1">
            <h2>الخطوة 1: معلومات البرج الأساسية</h2>
            <label>اسم البرج:</label>
            <input type="text" name="tower_name" required>
            <label>الموقع الوصفي:</label>
            <input type="text" name="tower_location" required>
            <button type="button" class="next-step">التالي</button>
        </div>
        <div class="step step-2">
            <h2>الخطوة 2: تفاصيل إضافية</h2>
            <label>موقع البرج على خريطة جوجل:</label>
            <input type="text" name="tower_google_map">
            <label>مزايا البرج:</label>
            <textarea name="tower_features"></textarea>
            <button type="button" class="prev-step">السابق</button>
            <button type="button" class="next-step">التالي</button>
        </div>
        <div class="step step-3">
            <h2>الخطوة 3: المعاينة والحفظ</h2>
            <p>يرجى مراجعة جميع المعلومات قبل الحفظ.</p>
            <button type="button" class="prev-step">السابق</button>
            <button type="submit">حفظ البرج</button>
        </div>
    </form>
</div>