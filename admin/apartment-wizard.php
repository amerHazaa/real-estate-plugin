<div class="wrap">
    <h1>إضافة شقة جديدة</h1>
    <form id="apartment-wizard-form">
        <div class="step step-1">
            <h2>الخطوة 1: معلومات الشقة الأساسية</h2>
            <label>اسم الشقة:</label>
            <input type="text" name="apartment_name" required>
            <label>ينتمي إلى البرج:</label>
            <input type="number" name="apartment_tower" required>
            <button type="button" class="next-step">التالي</button>
        </div>
        <div class="step step-2">
            <h2>الخطوة 2: تفاصيل إضافية</h2>
            <label>عدد الغرف:</label>
            <input type="number" name="num_rooms">
            <label>عدد الحمامات:</label>
            <input type="number" name="num_bathrooms">
            <label>حجم المطبخ (متر مربع):</label>
            <input type="text" name="kitchen_size">
            <label>حجم الشرفة (متر مربع):</label>
            <input type="text" name="balcony_size">
            <label>رقم الطابق:</label>
            <input type="number" name="floor_number">
            <label>السعر:</label>
            <input type="text" name="price">
            <button type="button" class="prev-step">السابق</button>
            <button type="button" class="next-step">التالي</button>
        </div>
        <div class="step step-3">
            <h2>الخطوة 3: المعاينة والحفظ</h2>
            <p>يرجى مراجعة جميع المعلومات قبل الحفظ.</p>
            <button type="button" class="prev-step">السابق</button>
            <button type="submit">حفظ الشقة</button>
        </div>
    </form>
</div>