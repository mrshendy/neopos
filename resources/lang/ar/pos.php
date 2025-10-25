<?php

return [

    // =========================
    // عناوين الصفحات (Customers)
    // =========================
    'title_customers_index' => 'العملاء',
    'title_customers_create' => 'إنشاء عميل',
    'title_customers_edit' => 'تعديل عميل',
    'title_customers_show' => 'عرض بيانات العميل',

    // بطاقات/أقسام في عرض العميل
    'card_profile' => 'الملف العام',
    'card_summary' => 'المعاملات والأرصدة',
    'card_transactions' => 'آخر المعاملات',
    'desc_profile' => 'تفاصيل عامة عن العميل ومعلومات الاتصال والموقع الجغرافي.',
    'desc_summary' => 'ملخص سريع لحالة الرصيد وحد الائتمان والمعاملات الأخيرة.',

    // =========================
    // رؤوس الجداول (Index/Show)
    // =========================
    'th_code' => 'الكود',
    'th_name' => 'الاسم',
    'th_type' => 'النوع',
    'th_channel' => 'القناة',
    'th_phone' => 'الهاتف',
    'th_city' => 'المدينة',
    'th_area' => 'المنطقة',
    'th_price_category' => 'فئة السعر',
    'th_credit_limit' => 'حد الائتمان',
    'th_balance' => 'الرصيد',
    'th_available' => 'المتاح',
    'th_open_invoices' => 'فواتير مفتوحة',
    'th_status' => 'الحالة',
    'th_actions' => 'إجراءات',
    'th_reference' => 'المرجع',
    'th_date' => 'التاريخ',
    'th_value' => 'القيمة',

    // =========================
    // حقول النموذج (Create/Edit)
    // =========================
    'f_code' => 'كود العميل',
    'f_legal_name_ar' => 'الاسم القانوني (عربي)',
    'f_legal_name_en' => 'الاسم القانوني (إنجليزي)',
    'f_trade_name_ar' => 'الاسم التجاري (عربي)',
    'f_trade_name_en' => 'الاسم التجاري (إنجليزي)',
    'f_type' => 'نوع العميل',
    'f_channel' => 'القناة',
    'f_country' => 'الدولة',
    'f_governorate' => 'المحافظة',
    'f_city_select' => 'المدينة (قائمة)',
    'f_area' => 'المنطقة',
    'f_city_text' => 'المدينة (نص)',
    'f_phone' => 'الهاتف',
    'f_tax' => 'الرقم الضريبي',
    'f_price_category' => 'فئة السعر',
    'f_credit_limit' => 'حد الائتمان',
    'f_account_status' => 'حالة الحساب',
    'f_sales_rep' => 'المندوب',

    // =========================
    // نصوص مساعدة و Placeholders
    // =========================
    'ph_search_name' => 'ابحث بالاسم',
    'ph_code' => 'أدخل كود فريد',
    'ph_phone' => 'أدخل رقم الهاتف',
    'ph_tax' => 'أدخل الرقم الضريبي',
    'ph_country' => 'اختر الدولة',
    'ph_governorate' => 'اختر المحافظة',
    'ph_city_select' => 'اختر المدينة',
    'ph_area' => 'اختر المنطقة',
    'ph_price_category' => 'اختر فئة السعر',
    'ph_city_text' => 'اكتب المدينة يدويًا (اختياري)',

    'h_code' => 'الكود يستخدم للتعريف السريع.',
    'h_legal_ar' => 'سيظهر في التقارير العربية.',
    'h_legal_en' => 'سيظهر في التقارير الإنجليزية.',
    'h_type' => 'اختر نوع العميل.',
    'h_channel' => 'قناة البيع الأساسية.',
    'h_country' => 'تستخدم للتقارير حسب الدولة.',
    'h_governorate' => 'تصفية المدن التابعة.',
    'h_city_select' => 'يعتمد على المحافظة.',
    'h_area' => 'تعتمد على المدينة.',
    'h_city_text' => 'إن أردت كتابة المدينة كنص حر.',
    'h_phone' => 'للتواصل والتنبيهات.',
    'h_tax' => 'مطلوب للفواتير الضريبية.',
    'h_price_category' => 'تؤثر على تسعير المنتجات.',
    'h_credit_limit' => 'الحد الأقصى للمبيعات الآجلة.',
    'h_account_status' => 'تفعيل/إيقاف/تعليق.',
    'preview' => 'معاينة',

    // =========================
    // الأزرار العامة
    // =========================
    'btn_new_customer' => '+ عميل جديد',
    'btn_save' => 'حفظ',
    'btn_update' => 'تحديث',
    'btn_cancel' => 'إلغاء',
    'btn_edit' => 'تعديل',
    'btn_view' => 'عرض',
    'btn_delete' => 'حذف',
    'btn_toggle' => 'تغيير الحالة',
    'btn_import' => 'استيراد',
    'btn_export' => 'تصدير',
    'btn_merge' => 'دمج',
    'btn_columns' => 'عرض الأعمدة',
    'btn_invoice' => 'إنشاء فاتورة',
    'btn_statement' => 'كشف حساب',
    'btn_activate' => 'تفعيل',
    'btn_deactivate' => 'إيقاف',
    'btn_back' => 'رجوع',

    // =========================
    // حالات/قوائم
    // =========================
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',
    'status_suspended' => 'معلق',
    'any_status' => 'أي حالة',
    'none' => 'لا شيء',
    'no_data' => 'لا توجد بيانات',
    'no_transactions' => 'لا توجد معاملات حتى الآن',

    // =========================
    // رسائل نجاح/خطأ
    // =========================
    'msg_created' => '✅ تم إنشاء العميل بنجاح.',
    'msg_updated' => '✅ تم تحديث بيانات العميل بنجاح.',
    'msg_deleted' => '🗑️ تم حذف العميل بنجاح.',
    'msg_status_updated' => '🔁 تم تحديث حالة الحساب بنجاح.',
    'msg_cannot_delete_has_tx' => '⚠️ لا يمكن حذف عميل لديه معاملات — استخدم الإيقاف.',
    'msg_error' => '❌ حدث خطأ أثناء العملية. حاول مرة أخرى.',

    // =========================
    // التحقق (Validation)
    // =========================
    'val_code_required' => 'كود العميل مطلوب.',
    'val_code_unique' => 'الكود مستخدم من قبل.',
    'val_legal_ar_required' => 'الاسم القانوني بالعربية مطلوب.',
    'val_legal_en_required' => 'الاسم القانوني بالإنجليزية مطلوب.',
    'val_credit_limit_min' => 'حد الائتمان لا يقل عن صفر.',
    'val_area_exists' => 'المنطقة المحددة غير صحيحة.',
    'val_country_exists' => 'الدولة المحددة غير صحيحة.',
    'val_governorate_exists' => 'المحافظة المحددة غير صحيحة.',
    'val_city_exists' => 'المدينة المحددة غير صحيحة.',
    'val_price_category_exists' => 'فئة السعر المحددة غير صحيحة.',

    // =========================
    // تنبيهات SweetAlert (حذف)
    // =========================
    'alert_delete_title' => 'تحذير',
    'alert_delete_text' => '⚠️ هل أنت متأكد أنك تريد الحذف؟ لا يمكن التراجع بعد تنفيذ العملية!',
    'alert_confirm' => 'نعم، احذف',
    'alert_cancel' => 'إلغاء',
    'alert_deleted_title' => 'تم الحذف!',
    'alert_deleted_text' => '✅ تم الحذف بنجاح.',

    // =========================
    // فلاتر ومناظير محفوظة
    // =========================
    'filters' => 'فلاتر',
    'apply_filters' => 'تطبيق الفلاتر',
    'reset_filters' => 'إعادة التعيين',
    'save_filter_view' => 'حفظ منظور الفلاتر',
    'saved_views' => 'المناظير المحفوظة',
    'view_name' => 'اسم المنظور',

    // =========================
    // اعتماد/موافقات (اختياري)
    // =========================
    'approvals' => 'الموافقات',
    'approve' => 'اعتماد',
    'reject' => 'رفض',
    'hold' => 'تعليق',
    'reason_required' => 'سبب القرار (إلزامي)',
    'approval_sent' => 'تم إرسال طلب الموافقة.',
    'approval_updated' => 'تم تحديث الاعتماد.',
    // العناوين والأزرار
    'title_customers_create' => 'إنشاء عميل',
    'btn_save' => 'حفظ',
    'btn_cancel' => 'إلغاء',
    'btn_back' => 'رجوع',

    // الأقسام
    'section_basic' => 'البيانات الأساسية',
    'section_location' => 'الموقع',
    'section_contact_finance' => 'التواصل والمالية',

    // الحقول / الملاحظات / العنواين المساعدة
    'f_code' => 'كود',
    'ph_code' => 'كود تلقائي أو مخصص',
    'h_code' => 'مرجع فريد للعميل (يسمح بالتلقائي).',

    'f_legal_name_ar' => 'الاسم القانوني (عربي)',
    'ph_legal_name_ar' => 'أدخل الاسم القانوني بالعربية',
    'h_legal_ar' => 'الاسم الرسمي الذي يظهر على الفواتير (عربي).',

    'f_legal_name_en' => 'الاسم القانوني (إنجليزي)',
    'ph_legal_name_en' => 'أدخل الاسم القانوني بالإنجليزية',
    'h_legal_en' => 'الاسم الرسمي الذي يظهر على الفواتير (إنجليزي).',

    'f_type' => 'النوع',
    'h_type' => 'حدّد نوع العلاقة.',
    'opt_individual' => 'فرد',
    'opt_company' => 'شركة',

    'f_channel' => 'القناة',
    'h_channel' => 'مكان تقديم الخدمة للعميل.',
    'opt_retail' => 'تجزئة',
    'opt_wholesale' => 'جملة',
    'opt_online' => 'أونلاين',
    'opt_pharmacy' => 'صيدلية',

    'f_country' => 'الدولة',
    'ph_country' => 'اختر الدولة',
    'h_country' => 'تستخدم لتحديد العنوان وقواعد الضرائب.',

    'f_governorate' => 'المحافظة',
    'ph_governorate' => 'اختر المحافظة',
    'h_governorate' => 'منطقة إدارية داخل الدولة.',

    'f_city_select' => 'المدينة (قائمة)',
    'ph_city_select' => 'اختر المدينة',
    'h_city_select' => 'اختر مدينة موجودة من القائمة.',

    'f_area' => 'المنطقة',
    'ph_area' => 'اختر المنطقة',
    'h_area' => 'الحَي / الحيّز الجغرافي.',

    'f_city_text' => 'المدينة (نص)',
    'ph_city_text' => 'اكتب اسم المدينة إن لم تكن بالقائمة',
    'h_city_text' => 'تُستخدم عندما لا تتوفر المدينة ضمن القائمة المعرفة مسبقًا.',

    'f_phone' => 'الهاتف',
    'ph_phone' => 'أدخل رقم الهاتف',
    'h_phone' => 'رقم التواصل الأساسي.',

    'f_tax' => 'الرقم الضريبي',
    'ph_tax' => 'أدخل رقم الضريبة/القيمة المضافة',
    'h_tax' => 'رقم المكلّف بالضريبة إن وُجد.',

    'f_price_category' => 'فئة السعر',
    'ph_price_category' => 'اختر قائمة الأسعار',
    'h_price_category' => 'تحدد الأسعار الافتراضية / الخصومات.',

    'f_credit_limit' => 'حد الائتمان',
    'h_credit_limit' => 'أقصى رصيد مستحق مسموح به.',

    'f_account_status' => 'حالة الحساب',
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',
    'status_suspended' => 'معلّق',
    'h_account_status' => 'تتحكم في إمكانية إجراء المعاملات.',

    // متفرقات
    'loading' => 'جارٍ الحفظ…',

    // الأزرار والعناوين العامة
    'btn_save' => 'حفظ',
    'btn_cancel' => 'إلغاء',
    'btn_close' => 'إغلاق',
    'btn_back' => 'رجوع',
    'btn_edit' => 'تعديل',
    'btn_update' => 'تحديث',
    'btn_print' => 'طباعة',
    'btn_new_invoice' => 'فاتورة جديدة',

    // الأقسام والحقول
    'section_basic' => 'البيانات الأساسية',
    'section_location' => 'الموقع',
    'section_contact_finance' => 'التواصل والمالية',

    'f_code' => 'كود',
    'ph_code' => 'كود تلقائي أو مخصص',
    'h_code' => 'مرجع فريد للعميل (يسمح بالتلقائي).',

    'f_legal_name_ar' => 'الاسم القانوني (عربي)',
    'ph_legal_name_ar' => 'أدخل الاسم القانوني بالعربية',
    'h_legal_ar' => 'الاسم الرسمي الذي يظهر على الفواتير (عربي).',

    'f_legal_name_en' => 'الاسم القانوني (إنجليزي)',
    'ph_legal_name_en' => 'أدخل الاسم القانوني بالإنجليزية',
    'h_legal_en' => 'الاسم الرسمي الذي يظهر على الفواتير (إنجليزي).',

    'f_type' => 'النوع',
    'opt_individual' => 'فرد',
    'opt_company' => 'شركة',
    'h_type' => 'حدّد نوع العلاقة.',

    'f_channel' => 'القناة',
    'opt_retail' => 'تجزئة',
    'opt_wholesale' => 'جملة',
    'opt_online' => 'أونلاين',
    'opt_pharmacy' => 'صيدلية',
    'h_channel' => 'مكان تقديم الخدمة للعميل.',

    'f_country' => 'الدولة',
    'ph_country' => 'اختر الدولة',
    'h_country' => 'تُستخدم لتحديد العنوان وقواعد الضرائب.',

    'f_governorate' => 'المحافظة',
    'ph_governorate' => 'اختر المحافظة',
    'h_governorate' => 'منطقة إدارية داخل الدولة.',

    'f_city_select' => 'المدينة (قائمة)',
    'ph_city_select' => 'اختر المدينة',
    'h_city_select' => 'اختر مدينة موجودة من القائمة.',

    'f_area' => 'المنطقة',
    'ph_area' => 'اختر المنطقة',
    'h_area' => 'الحي/المنطقة الجغرافية.',

    'f_city_text' => 'المدينة (نص)',
    'ph_city_text' => 'اكتب اسم المدينة إن لم تكن بالقائمة',
    'h_city_text' => 'تُستخدم عندما لا تتوفر المدينة ضمن القائمة.',

    'f_phone' => 'الهاتف',
    'ph_phone' => 'أدخل رقم الهاتف',
    'h_phone' => 'رقم التواصل الأساسي.',

    'f_tax' => 'الرقم الضريبي',
    'ph_tax' => 'أدخل رقم الضريبة/القيمة المضافة',
    'h_tax' => 'رقم المكلّف بالضريبة إن وُجد.',

    'f_price_category' => 'فئة السعر',
    'ph_price_category' => 'اختر قائمة الأسعار',
    'h_price_category' => 'تحدد الأسعار الافتراضية/الخصومات.',

    'f_credit_limit' => 'حد الائتمان',
    'h_credit_limit' => 'أقصى رصيد مستحق مسموح به.',

    'f_account_status' => 'حالة الحساب',
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',
    'status_suspended' => 'معلّق',
    'h_account_status' => 'تتحكم في إمكانية إجراء المعاملات.',

    // صفحة العرض
    'title_customers_show' => 'ملف العميل',

    // البطاقات
    'card_profile' => 'الملف العام',
    'desc_profile' => 'معلومات عامة والعنوان.',
    'card_summary' => 'الملخص المالي',
    'desc_summary' => 'نظرة على الائتمان وآخر الأنشطة.',
    'card_activity' => 'النشاط',
    'card_contacts' => 'جهات الاتصال',
    'desc_contacts' => 'الأشخاص المسؤولون عن التواصل.',
    'card_notes' => 'ملاحظات داخلية',
    'desc_notes' => 'ملاحظات خاصة تظهر للموظفين فقط.',
    'no_notes' => 'لا توجد ملاحظات.',

    // العناوين
    'th_code' => 'الكود',
    'th_name' => 'الاسم',
    'th_phone' => 'الهاتف',
    'th_status' => 'الحالة',
    'th_country' => 'الدولة',
    'th_governorate' => 'المحافظة',
    'th_city' => 'المدينة',
    'th_area' => 'المنطقة',
    'th_price_category' => 'فئة السعر',
    'th_balance' => 'الرصيد',
    'th_credit_limit' => 'حد الائتمان',
    'th_available' => 'المتاح',
    'th_open_invoices' => 'الفواتير المفتوحة',
    'th_type' => 'النوع',
    'th_reference' => 'المرجع',
    'th_date' => 'التاريخ',
    'th_value' => 'القيمة',
    'th_person' => 'الشخص',
    'th_role' => 'الصفة',
    'th_email' => 'البريد',

    // التبويبات والحالات الفارغة
    'tab_invoices' => 'الفواتير',
    'tab_receipts' => 'التحصيلات',
    'tab_returns' => 'المرتجعات',
    'tab_latest' => 'الأحدث',
    'no_transactions' => 'لا توجد معاملات.',
    'no_invoices' => 'لا توجد فواتير.',
    'no_receipts' => 'لا توجد تحصيلات.',
    'no_returns' => 'لا توجد مرتجعات.',
    'no_contacts' => 'لا توجد جهات اتصال.',

    // متفرقات
    'credit_used' => 'نسبة استخدام الائتمان',
    'tt_copy' => 'نسخ إلى الحافظة',

    // عناوين الصفحات
    'supplier_title' => 'المورّدين',
    'supplier_list' => 'قائمة المورّدين',
    'supplier_create' => 'إنشاء مورّد',
    'supplier_edit' => 'تعديل مورّد',
    'supplier_show' => 'عرض بيانات المورّد',

    // الحقول الأساسية
    'supplier_code' => 'كود المورّد',
    'supplier_name' => 'اسم المورّد',
    'name_ar' => 'الاسم بالعربية',
    'name_en' => 'الاسم بالإنجليزية',
    'commercial_register' => 'السجل التجاري',
    'tax_number' => 'الرقم الضريبي',
    'category' => 'التصنيف',
    'payment_term' => 'شروط الدفع',

    // الجغرافيا
    'country' => 'الدولة',
    'governorate' => 'المحافظة',
    'city' => 'المدينة',
    'area' => 'المنطقة',

    // الحالة والعمليات
    'status' => 'الحالة',
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',
    'actions' => 'العمليات',

    // أزرار
    'btn_save' => 'حفظ',
    'btn_update' => 'تحديث',
    'btn_back' => 'رجوع',
    'btn_create' => 'إضافة مورّد',
    'btn_show' => 'عرض',
    'btn_edit' => 'تعديل',
    'btn_delete' => 'حذف',
    'btn_toggle' => 'تغيير الحالة',

    // نصوص عامة
    'no_data' => 'لا توجد بيانات',
    'search_supplier' => 'ابحث بالاسم/الكود',

    // نجاح/تنبيهات
    'created_success' => '✅ تم إنشاء المورّد بنجاح.',
    'updated_success' => '✅ تم تحديث بيانات المورّد بنجاح.',
    'deleted_success' => '✅ تم حذف السجل بنجاح.',
    'status_changed' => 'تم تغيير الحالة بنجاح.',
    'warn_inactive_supplier' => 'تنبيه: المورّد غير نشط — لن يُسمح بالشراء منه حتى يتم تفعيله.',

    // Placeholders + Descriptions
    'ph_code' => 'أدخل كود فريد للمورّد',
    'desc_code' => 'يُستخدم الكود للربط مع باقي الوحدات.',
    'ph_name_ar' => 'أدخل الاسم بالعربية',
    'desc_name_ar' => 'الاسم الرسمي كما في الوثائق.',
    'ph_name_en' => 'Enter English name',
    'desc_name_en' => 'Official supplier name in English.',
    'ph_cr' => 'رقم السجل التجاري (اختياري)',
    'desc_cr' => 'لأغراض التحقق القانوني.',
    'ph_tax' => 'الرقم الضريبي (اختياري)',
    'desc_tax' => 'مطلوب للفواتير الضريبية.',
    'ph_category' => 'اختر التصنيف',
    'desc_category' => 'لتسهيل التقارير والفلترة.',
    'ph_payment_term' => 'اختر شرط الدفع',
    'desc_payment_term' => 'عدد أيام الاستحقاق والسماح بالدفعات الجزئية.',
    'desc_status' => 'يمكنك إيقاف المورد مؤقتًا دون حذفه.',

    'ph_country' => 'اختر الدولة',
    'desc_country' => 'الدولة التي يتبع لها المورد.',
    'ph_governorate' => 'اختر المحافظة',
    'desc_governorate' => 'المحافظة ضمن الدولة.',
    'ph_city' => 'اختر المدينة',
    'desc_city' => 'المدينة التابعة للمحافظة.',
    'ph_area' => 'اختر المنطقة',
    'desc_area' => 'المنطقة داخل المدينة.',

    // فلاتر
    'filter_category' => 'تصفية بالتصنيف',
    'filter_governorate' => 'تصفية بالمحافظة',
    'filter_city' => 'تصفية بالمدينة',
    'filter_status' => 'تصفية بالحالة',

    // تنبيهات الحذف (SweetAlert2)
    'alert_delete_title' => 'تحذير',
    'alert_delete_text' => '⚠️ هل أنت متأكد من الحذف؟ لا يمكن التراجع!',
    'alert_delete_confirm' => 'نعم، احذف',
    'alert_delete_cancel' => 'إلغاء',
    'alert_deleted' => 'تم الحذف!',
    'alert_deleted_text' => '✅ تم الحذف بنجاح.',

    // Titles
    'products_index_title' => 'إدارة المنتجات',
    'product_create_title' => 'إنشاء منتج',
    'price_lists_title' => 'قوائم الأسعار',

    // Common
    'search' => 'بحث',
    'all' => 'الكل',
    'choose' => 'اختر',
    'actions' => 'الإجراءات',
    'no_data' => 'لا توجد بيانات.',
    'status' => 'الحالة',
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',

    // Fields
    'sku' => 'SKU',
    'barcode' => 'الباركود',
    'name' => 'الاسم',
    'name_ar' => 'الاسم (عربي)',
    'name_en' => 'الاسم (إنجليزي)',
    'description_ar' => 'الوصف (عربي)',
    'description_en' => 'الوصف (إنجليزي)',
    'unit' => 'الوحدة',
    'category' => 'الفئة',
    'tax_rate' => 'نسبة الضريبة %',
    'opening_stock' => 'الرصيد الافتتاحي',

    // Placeholders/Hints
    'ph_search_sku_barcode_name' => 'ابحث بالـ SKU/باركود/الاسم…',
    'ph_sku' => 'أدخل كود المنتج الداخلي',
    'ph_barcode' => 'أدخل الباركود (إن وجد)',
    'ph_name_ar' => 'أدخل الاسم بالعربية',
    'ph_name_en' => 'أدخل الاسم بالإنجليزية',
    'ph_desc_ar' => 'وصف مختصر بالعربية',
    'ph_desc_en' => 'Brief English description',
    'hint_search_products' => 'استخدم الكلمات المفتاحية للوصول السريع.',
    'hint_sku' => 'SKU فريد لمنع التكرار.',
    'hint_barcode' => 'لا يمكن تكرار الباركود.',
    'hint_name_ar' => 'سيظهر في الواجهات العربية.',
    'hint_name_en' => 'سيظهر في الواجهات الإنجليزية.',
    'hint_unit' => 'اختر وحدة قياس مناسبة.',
    'hint_category' => 'اختيار فئة يُحسّن التقارير.',
    'hint_tax_rate' => 'النطاق 0 – 100.',
    'hint_opening_stock' => 'قيمة أول المدة في المخزون.',
    'hint_status' => 'يمكن تغييره لاحقًا من الأكشنز.',

    // Filters
    'filter_category' => 'تصفية بالفئة',
    'filter_unit' => 'تصفية بالوحدة',
    'filter_status' => 'تصفية بالحالة',

    // Buttons
    'btn_new_product' => 'منتج جديد',
    'btn_save' => 'حفظ',
    'btn_back' => 'رجوع',

    // Alerts/SweetAlert2
    'alert_title' => 'تحذير',
    'alert_text' => '⚠️ هل أنت متأكد من الحذف؟ لا يمكن التراجع!',
    'alert_confirm' => 'نعم، احذف',
    'alert_cancel' => 'إلغاء',
    'deleted' => 'تم الحذف',
    'msg_deleted_ok' => '✅ تم الحذف بنجاح.',
    'msg_saved_ok' => '✅ تم الحفظ بنجاح.',
    'msg_status_changed' => 'تم تغيير الحالة.',

    // Pricing errors
    'err_price_conflict' => 'تعارض أسعار — الرجاء ضبط الفترة أو الأولوية.',
    'err_price_conflict_banner' => 'تعارض أسعار لنفس المنتج ضمن نفس القائمة في نفس الفترة.',
    'err_price_negative' => 'السعر لا يمكن أن يكون أقل من 0.',

    // ... ترجماتك السابقة

    'valid' => 'ساري',
    'invalid' => 'غير ساري',
    'valid_from' => 'ساري من تاريخ',
    'valid_to' => 'ساري حتى تاريخ',
    'valid_date' => 'تاريخ ساري',
    'valid_input' => 'إدخال ساري',
    'please_enter_valid_value' => 'من فضلك أدخل قيمة صالحة',

    /*
    |--------------------------------------------------------------------------
    | أقسام النظام (الأصناف / الفئات)
    |--------------------------------------------------------------------------
    */

    'category_title' => 'الأقسام',
    'categories_index_title' => 'قائمة الأقسام',
    'category_create_title' => 'إضافة قسم جديد',
    'category_edit_title' => 'تعديل القسم',

    // الحقول
    'name' => 'الاسم',
    'name_ar' => 'الاسم بالعربية',
    'name_en' => 'الاسم بالإنجليزية',
    'description' => 'الوصف',
    'description_ar' => 'الوصف بالعربية',
    'description_en' => 'الوصف بالإنجليزية',
    'status' => 'الحالة',
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',

    // الفلاتر
    'search' => 'بحث',
    'filter_status' => 'تصفية حسب الحالة',
    'ph_search_category' => 'ابحث باسم القسم...',
    'hint_search_category' => 'يمكنك البحث باسم القسم بالعربية أو الإنجليزية',
    'hint_status' => 'اختر حالة القسم لعرض النتائج المناسبة',

    // الأزرار والإجراءات
    'actions' => 'الإجراءات',
    'btn_save' => 'حفظ',
    'btn_back' => 'رجوع',
    'btn_new_category' => 'قسم جديد',
    'btn_edit' => 'تعديل',
    'btn_delete' => 'حذف',

    // التلميحات تحت الحقول
    'hint_name_ar' => 'أدخل اسم القسم باللغة العربية',
    'hint_name_en' => 'أدخل اسم القسم باللغة الإنجليزية',
    'hint_description_ar' => 'الوصف بالعربية (اختياري)',
    'hint_description_en' => 'الوصف بالإنجليزية (اختياري)',

    // التنبيهات والرسائل
    'msg_saved_ok' => '✅ تم حفظ البيانات بنجاح.',
    'msg_deleted_ok' => '🗑️ تم حذف القسم بنجاح.',
    'msg_status_changed' => '🔄 تم تغيير حالة القسم بنجاح.',
    'no_data' => 'لا توجد بيانات حالياً.',
    'all' => 'الكل',

    // الرسائل التحذيرية
    'alert_title' => 'تحذير',
    'alert_text' => '⚠️ هل أنت متأكد من تنفيذ هذه العملية؟ لا يمكن التراجع!',
    'alert_confirm' => 'نعم، تأكيد',
    'alert_cancel' => 'إلغاء',
    'deleted' => 'تم الحذف',

    'print' => 'طباعة',
    'ph_barcode' => 'أدخل/امسح الباركود...',
    'hint_barcode' => 'اكتب أو امسح الباركود لمعاينة الملصق.',
    'products_title' => 'إدارة المنتجات',
    'products_management_sub' => 'إدارة بيانات المنتجات وإعداداتها الأساسية.',
    // عناوين عامة
    'price_lists_title' => 'قوائم الأسعار',
    'price_lists_create_title' => 'إنشاء قائمة أسعار',
    'price_lists_edit_title' => 'تعديل قائمة أسعار',
    'basic_info' => 'البيانات الأساسية',

    // الحقول العامة
    'name' => 'الاسم',
    'name_ar' => 'الاسم (عربي)',
    'name_en' => 'الاسم (إنجليزي)',

    // Placeholders & Hints
    'ph_name_ar' => 'اكتب الاسم بالعربية',
    'ph_name_en' => 'اكتب الاسم بالإنجليزية',
    'hint_name_ar' => 'سيظهر هذا الاسم للمستخدمين باللغة العربية.',
    'hint_name_en' => 'سيظهر هذا الاسم للمستخدمين باللغة الإنجليزية.',

    // التواريخ
    'valid_from' => 'من تاريخ',
    'valid_to' => 'إلى تاريخ',
    'hint_valid_from' => 'تاريخ بداية صلاحية القائمة.',
    'hint_valid_to' => 'اتركه فارغًا لغير محددة.',
    'validity' => 'الصلاحية',

    // الحالة
    'status' => 'الحالة',
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',
    'hint_status' => 'حالة تفعيل/تعطيل القائمة.',

    // بنود الأسعار
    'price_products' => 'بنود الأسعار',
    'add_row' => 'إضافة بند',
    'product' => 'المنتج',
    'price' => 'السعر',
    'min_qty' => 'الحد الأدنى',
    'max_qty' => 'الحد الأقصى',

    // أزرار وإجراءات
    'actions' => 'الإجراءات',
    'btn_new' => 'جديد',
    'btn_edit' => 'تعديل',
    'btn_delete' => 'حذف',
    'btn_save' => 'حفظ',
    'btn_back' => 'رجوع',
    'choose' => 'اختر',

    // فلاتر وبحث
    'search' => 'بحث',
    'ph_search' => 'ابحث بالاسم...',
    'all' => 'الكل',
    'per_page' => 'عدد الصفوف',
    'products_count' => 'عدد البنود',

    // رسائل نظام
    'no_data' => 'لا توجد بيانات',
    'showing' => 'عرض',
    'of' => 'من',
    'msg_saved_ok' => 'تم الحفظ بنجاح.',
    'msg_updated_ok' => 'تم تحديث البيانات بنجاح.',
    'msg_deleted_ok' => 'تم الحذف بنجاح.',
    'show' => 'عرض',

    // ===== Common / Global =====
    'inventory_title' => 'المخزون',
    'inventory_dashboard_title' => 'لوحة تحكم المخزون',

    'search' => 'بحث',
    'status' => 'الحالة',
    'all' => 'الكل',
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'yes' => 'نعم',
    'no' => 'لا',
    'none' => 'بدون',
    'choose' => 'اختر',
    'actions' => 'الإجراءات',
    'no_data' => 'لا توجد بيانات',
    'notes' => 'ملاحظات',
    'hint_notes' => 'يمكن كتابة سبب أو ملاحظة عامة هنا',
    'date_from' => 'من تاريخ',
    'date_to' => 'إلى تاريخ',

    // Buttons
    'btn_save' => 'حفظ',
    'btn_cancel' => 'إلغاء',
    'btn_back' => 'رجوع',
    'btn_new' => 'إضافة',
    'btn_edit' => 'تعديل',
    'btn_delete' => 'حذف',
    'btn_refresh' => 'تحديث',
    'btn_export' => 'تصدير',
    'btn_print' => 'طباعة',

    // Alerts / Flash
    'saved_success' => 'تم الحفظ بنجاح',
    'deleted' => 'تم الحذف',
    'deleted_success' => '✅ تم الحذف بنجاح.',
    'alert_title' => 'تحذير',
    'alert_delete_text' => '⚠️ هل أنت متأكد؟ هذا الإجراء لا يمكن التراجع عنه!',
    'alert_confirm_yes' => 'نعم، احذف',
    'alert_confirm_cancel' => 'إلغاء',

    // Statuses / Workflow
    'draft' => 'مسودة',
    'posted' => 'مرحّلة',
    'cancelled' => 'ملغاة',

    // ===== products =====
    'inventory_products_title' => 'إدارة الأصناف',
    'btn_new_item' => 'إضافة صنف',
    'add_new_item' => 'إضافة صنف جديد',

    'item_name' => 'اسم الصنف',
    'item_name_ar' => 'اسم الصنف (عربي)',
    'item_name_en' => 'اسم الصنف (إنجليزي)',
    'sku' => 'الكود (SKU)',
    'uom' => 'الوحدة',
    'track_batch' => 'تتبع الدُفعات',
    'track_serial' => 'تتبع السيريال',

    'ph_search_sku_name' => 'ابحث بالاسم أو الكود',
    'ph_item_name_ar' => 'مثال: بنادول',
    'ph_item_name_en' => 'e.g., Panadol',
    'ph_sku' => 'مثال: PND-001',
    'ph_uom' => 'مثال: علبة',

    'hint_item_name' => 'اكتب اسمًا واضحًا للصنف',
    'hint_uom' => 'أدخل وحدة القياس المناسبة',
    'hint_sku' => 'أدخل كودًا فريدًا للصنف',
    'hint_filter_status' => 'فلترة حسب الحالة',

    'inventory_deleted_success' => 'تم حذف الصنف بنجاح',
    'inventory_item_status_changed' => 'تم تغيير حالة الصنف بنجاح',

    // Validations (products)
    'val_item_name_ar_required' => 'الاسم بالعربية مطلوب',
    'val_item_name_en_required' => 'الاسم بالإنجليزية مطلوب',
    'val_item_sku_required' => 'كود الصنف (SKU) مطلوب',
    'val_item_sku_unique' => 'كود الصنف مستخدم من قبل',
    'val_item_uom_required' => 'وحدة القياس مطلوبة',

    // ===== Warehouses =====
    'inventory_warehouses_title' => 'إدارة المخازن',
    'btn_new_warehouse' => 'إضافة مخزن',
    'warehouse' => 'المخزن',
    'warehouse_code' => 'كود المخزن',
    'warehouse_branch' => 'الفرع',

    'warehouse_deleted_success' => 'تم حذف المخزن بنجاح',
    'warehouse_status_changed' => 'تم تغيير حالة المخزن بنجاح',

    // Validations (Warehouses)
    'val_wh_name_ar_required' => 'اسم المخزن بالعربية مطلوب',
    'val_wh_name_en_required' => 'اسم المخزن بالإنجليزية مطلوب',
    'val_wh_code_required' => 'كود المخزن مطلوب',
    'val_wh_code_unique' => 'كود المخزن مستخدم من قبل',

    // ===== Transactions =====
    'inventory_transactions_title' => 'إدارة الحركات',
    'inventory_transactions_create' => 'إنشاء حركة مخزون',
    'btn_new_transaction' => 'حركة جديدة',
    'add_new_transaction' => 'إضافة حركة جديدة',

    'trx_no' => 'رقم الحركة',
    'type' => 'النوع',
    'trx_date' => 'تاريخ الحركة',
    'from' => 'من',
    'to' => 'إلى',
    'from_warehouse' => 'من مخزن',
    'to_warehouse' => 'إلى مخزن',

    'ph_search_trx_no' => 'ابحث برقم الحركة',

    'lines' => 'بنود الحركة',
    'add_line' => 'إضافة بند',
    'item' => 'الصنف',
    'qty' => 'الكمية',
    'reason' => 'السبب',

    'hint_trx_type' => 'اختر نوع الحركة',
    'hint_trx_date' => 'تاريخ ووقت الحركة',
    'hint_from_wh' => 'اختر مخزن المصدر (اختياري)',
    'hint_to_wh' => 'اختر مخزن الوجهة (اختياري)',
    'hint_item' => 'اختر الصنف',
    'hint_qty' => 'أدخل الكمية المطلوبة',

    // Transaction Types
    'trx_sales_issue' => 'صرف بيع',
    'trx_sales_return' => 'مرتجع بيع',
    'trx_adjustment' => 'تسوية',
    'trx_transfer' => 'تحويل',
    'trx_purchase_receive' => 'استلام مشتريات',

    // Transaction messages
    'cannot_post_non_draft' => 'لا يمكن ترحيل حركة ليست مسودة',
    'posted_success' => 'تم ترحيل الحركة بنجاح',
    'cannot_cancel_posted' => 'لا يمكن إلغاء حركة مُرحّلة',
    'cancelled_success' => 'تم إلغاء الحركة بنجاح',
    'cannot_delete_posted' => 'لا يمكن حذف حركة مُرحّلة',

    // Validations (Transactions)
    'val_trx_type_required' => 'نوع الحركة مطلوب',
    'val_trx_date_required' => 'تاريخ الحركة مطلوب',
    'val_trx_item_required' => 'يجب اختيار الصنف',
    'val_trx_qty_min' => 'الكمية يجب أن تكون أكبر من صفر',
    'val_trx_uom_required' => 'وحدة القياس مطلوبة',

    // ===== Counts (Stock Count) =====
    'inventory_counts_title' => 'الجردات',
    'count_start' => 'بدء جرد',
    'count_policy' => 'نوع الجرد',
    'count_policy_periodic' => 'جرد دوري',
    'count_policy_spot' => 'جرد مفاجئ',
    'count_started' => 'تم بدء الجرد بنجاح',
    'count_open' => 'مفتوح',
    'count_review' => 'قيد المراجعة',
    'count_approved' => 'تم الاعتماد',
    'count_rejected' => 'مرفوض',
    'count_system_qty' => 'الكمية النظامية',
    'count_counted_qty' => 'الكمية الفعلية',
    'count_difference_qty' => 'فرق الكمية',
    'count_approve' => 'اعتماد الجرد',
    'count_approved_success' => 'تم اعتماد الجرد وتحديث الأرصدة بنجاح',
    'count_reject' => 'رفض الجرد',
    'count_rejected_success' => 'تم رفض الجرد',

    // Count validations/messages
    'val_count_warehouse_required' => 'اختَر المخزن للجرد',
    'val_count_policy_required' => 'نوع الجرد مطلوب',
    'val_count_policy_invalid' => 'نوع الجرد غير صحيح',

    // ===== Alerts =====
    'inventory_alerts_title' => 'تنبيهات المخزون',
    'reorder_alerts' => 'تنبيهات إعادة الطلب',
    'expiry_alerts' => 'تنبيهات قرب الانتهاء',
    'expired_stock' => 'عناصر منتهية الصلاحية',
    'reorder_suggest' => 'اقتراح إنشاء طلب شراء أو تحويل',
    'expiry_days_left' => 'أيام متبقية للانتهاء',
    'alert_generated' => 'تم إصدار التنبيه',

    // ===== Reports =====
    'inventory_reports_title' => 'تقارير المخزون',
    'report_stock_movements' => 'تقرير حركة المخزون',
    'report_counts_diff' => 'تقرير الجرد والفروقات',
    'report_batches' => 'تقرير الدُفعات',
    'report_alerts' => 'تقرير التنبيهات',
    'report_transfers' => 'تقرير التحويلات بين الفروع',
    'report_negative' => 'تقرير الأصناف ذات مخزون سالب',

    // ===== Settings =====
    'inventory_settings_title' => 'إعدادات المخزون',
    'negative_stock_policy' => 'سياسة السالب',
    'negative_stock_block' => 'منع السالب نهائيًا',
    'negative_stock_warn' => 'السماح بالسالب مع تحذير',
    'expiry_alert_days' => 'أيام تنبيه الانتهاء',
    'transaction_sequences' => 'ترقيم الحركات',
    'sequence_pattern' => 'نمط الترقيم (مثال: INV-TRX-{YYYY}-{####})',

    // Negative stock system messages
    'neg_blocked' => 'منع سالب المخزون – الكمية المطلوبة تتجاوز المتاح.',
    'neg_allowed_warn' => 'سُمح بالسالب مع تحذير وتسجيل في سجل الفروقات.',

    // ===== Filters / Headings helpers =====
    'warehouse_filter' => 'فلتر المخزن',
    'from_warehouse_filter' => 'من المخزن',
    'to_warehouse_filter' => 'إلى المخزن',
    // العناوين العامة
    'inventory_products_title' => 'الأصناف',
    'inventory_warehouses_title' => 'المخازن',
    'inventory_transactions_title' => 'الحركات',
    'inventory_counts_title' => 'الجردات',
    'inventory_alerts_title' => 'التنبيهات',
    'inventory_settings_title' => 'الإعدادات',
    'inventory_reports_title' => 'التقارير',

    // الأزرار العامة
    'btn_new_item' => 'إضافة صنف جديد',
    'btn_save' => 'حفظ',
    'btn_cancel' => 'إلغاء',
    'btn_edit' => 'تعديل',
    'btn_delete' => 'حذف',

    // الرسائل
    'no_data' => 'لا توجد بيانات متاحة.',
    'saved_success' => 'تم الحفظ بنجاح ✅',
    'deleted_success' => 'تم الحذف بنجاح ✅',
    'status_changed' => 'تم تغيير الحالة بنجاح',
    'export_in_progress' => 'جاري تجهيز ملف التصدير...',
    'alert_title' => 'تحذير',
    'alert_delete_text' => '⚠️ هل أنت متأكد من الحذف؟ لا يمكن التراجع عن العملية!',
    'alert_confirm_yes' => 'نعم، احذفها',
    'alert_confirm_cancel' => 'إلغاء',
    'deleted' => 'تم الحذف!',

    // الحقول
    'item_name' => 'اسم الصنف',
    'sku' => 'كود الصنف (SKU)',
    'uom' => 'وحدة القياس',
    'track_batch' => 'تتبع الدُفعات',
    'track_serial' => 'تتبع الأرقام التسلسلية',
    'status' => 'الحالة',
    'actions' => 'الإجراءات',

    // الحالات
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'yes' => 'نعم',
    'no' => 'لا',
    'all' => 'الكل',

    // البحث والفلاتر
    'search' => 'بحث',
    'ph_search_sku_name' => 'ابحث بالاسم أو الكود...',
    'hint_search_products' => 'يمكنك البحث بالاسم العربي أو الإنجليزي أو كود الصنف.',

    // الفلاتر الخاصة بالحركات
    'filter_warehouse' => 'المخزن',
    'filter_item' => 'الصنف',
    'filter_type' => 'نوع الحركة',
    'date_from' => 'من تاريخ',
    'date_to' => 'إلى تاريخ',
'status_draft'     => 'مسودة',
'status_posted'    => 'مرحّلة',
'status_cancelled' => 'ملغاة',

    // أنواع الحركات
    'type_sales_issue' => 'صرف مبيعات',
    'type_sales_return' => 'مرتجع مبيعات',
    'type_adjustment' => 'تسوية مخزون',
    'type_transfer' => 'تحويل مخزون',
    'type_purchase_receive' => 'استلام مشتريات',

    // الحقول الإضافية في التقارير
    'trx_no' => 'رقم الحركة',
    'trx_date' => 'تاريخ الحركة',
    'warehouse_from' => 'من مخزن',
    'warehouse_to' => 'إلى مخزن',
    'type' => 'النوع',
    'status_posted' => 'مرحّل',
    'status_cancelled' => 'ملغي',
    'status_draft' => 'مسودة',

    // التقارير
    'inventory_reports' => 'تقارير المخزون',
    'export_excel' => 'تصدير Excel',
    'product_image' => 'صورة المنتج',
    'hint_image' => 'ارفع صورة واضحة للمنتج (JPG/PNG، حد أقصى 2MB). يمكنك السحب والإفلات أو اختيار ملف.',
    'hint_track_batch' => 'فعّلها إذا كان الصنف بإدارة الدُفعات/انتهاء الصلاحية (Batch/Expiry).',
    'hint_track_serial' => 'فعّلها إذا لكل قطعة رقم تسلسلي فريد (Serial) يجب تتبّعه.',
    'hint_desc_ar' => 'اكتب وصف الصنف بالعربية لعرضه في نقاط البيع والتقارير.',
    'hint_desc_en' => 'اكتب الوصف الإنجليزي إن وُجد لضمان ظهور متعدد اللغات.',
    'hint_reorder' => 'حدّ إعادة الطلب: أقل كمية بالمخزون قبل إصدار تنبيه وإعادة التوريد.',
    'reorder_level' => 'حد إعادة الطلب',
    'clear_filters' => 'مسح الفلاتر',
    'image' => 'الصورة',
    'confirm_delete_title' => 'تحذير',
    'confirm_delete_text' => 'هل أنت متأكد من حذف هذا السجل؟ لا يمكن التراجع عن هذه العملية.',
    'btn_yes_delete' => 'نعم، احذف',
    'btn_cancel' => 'إلغاء',
    'remove_image' => 'إزالة الصورة',
    'units_settings' => 'إعدادات الوحدات',
    'Go_to_settings_now' => 'اذهب إلى الإعدادات الآن',
    'view_image' => 'عرض الصورة',
    'close' => 'إغلاق',
    // ===== Inventory Count (Stocktaking) =====
    'start_new_count' => 'بدء جرد جديد',
    'warehouse' => 'المخزن',
    'choose' => 'اختر',
    'policy' => 'نوع الجرد',
    'count_periodic' => 'جرد دوري',
    'count_spot' => 'جرد مفاجئ',
    'notes' => 'ملاحظات',
    'hint_notes' => 'ملاحظات اختيارية…',
    'btn_start' => 'بدء',
    'previous_counts' => 'عمليات الجرد السابقة',
    'status' => 'الحالة',
    'started_at' => 'تاريخ ووقت البدء',
    'no_data' => 'لا توجد بيانات',

    // statuses (تُستخدم مع __('pos.' . $c->status))
    'open' => 'مفتوح',
    'closed' => 'مغلق',
    // ===== Inventory Alerts =====
    'inventory_alerts_title' => 'تنبيهات المخزون',
    'alert_type' => 'نوع التنبيه',
    'alert_reorder' => 'إعادة الطلب',
    'alert_expiry' => 'قرب انتهاء الصلاحية',
    'alert_expired' => 'انتهت الصلاحية',

    'item' => 'الصنف',
    'warehouse' => 'المخزن',
    'qty' => 'الكمية',
    'alert_message' => 'رسالة التنبيه',
    'created_at' => 'تاريخ الإنشاء',
    'no_alerts' => 'لا توجد تنبيهات',

    // الرسائل الديناميكية
    'reorder_alert_msg' => 'الكمية المتاحة من :item أقل من حد إعادة الطلب.',
    'expiry_alert_msg' => ':item يقترب من انتهاء الصلاحية — يُرجى التصريف أولاً.',
    'expired_alert_msg' => ':item انتهت صلاحيته — أوقف البيع واتخذ الإجراء المناسب.',

    // عناوين
    'units_title' => 'إدارة الوحدات',
    'units_index' => 'قائمة الوحدات',
    'units_create' => 'إنشاء وحدة',
    'units_edit' => 'تعديل وحدة',

    // الحقول
    'name_ar' => 'الاسم (عربي)',
    'name_en' => 'الاسم (إنجليزي)',
    'description_ar' => 'الوصف (عربي)',
    'description_en' => 'الوصف (إنجليزي)',
    'level' => 'المستوى',
    'status' => 'الحالة',
    'minor' => 'وحدة صغرى',
    'middle' => 'وحدة وسطى',
    'major' => 'وحدة كبرى',
    'active' => 'نشطة',
    'inactive' => 'غير نشطة',

    // أزرار
    'btn_save' => 'حفظ',
    'btn_update' => 'تحديث',
    'btn_cancel' => 'إلغاء',
    'btn_new' => 'وحدة جديدة',
    'btn_edit' => 'تعديل',
    'btn_delete' => 'حذف',
    'btn_toggle_status' => 'تغيير الحالة',
    'btn_search' => 'بحث',
    'btn_reset' => 'إعادة ضبط',

    // جدول
    'th_id' => '#',
    'th_name' => 'الاسم',
    'th_level' => 'المستوى',
    'th_status' => 'الحالة',
    'th_actions' => 'إجراءات',

    // تعليمات تحت الحقول
    'hint_name_ar' => 'اكتب اسم الوحدة باللغة العربية.',
    'hint_name_en' => 'اكتب اسم الوحدة باللغة الإنجليزية.',
    'hint_description_ar' => 'وصف مختصر باللغة العربية (اختياري).',
    'hint_description_en' => 'وصف مختصر باللغة الإنجليزية (اختياري).',
    'hint_level' => 'اختر نوع/مستوى الوحدة (صغرى/وسطى/كبرى).',
    'hint_status' => 'اختر حالة التفعيل للوحدة.',

    // فلاتر
    'filter_search' => 'بحث بالاسم',
    'filter_level' => 'تصفية بالمستوى',
    'filter_status' => 'تصفية بالحالة',

    // رسائل
    'msg_created' => '✅ تم إنشاء الوحدة بنجاح.',
    'msg_updated' => '✅ تم تعديل الوحدة بنجاح.',
    'msg_deleted' => '✅ تم حذف الوحدة بنجاح.',
    'msg_status_toggled' => '✅ تم تغيير حالة الوحدة.',

    // تحذيرات/تأكيدات
    'delete_title' => 'تحذير',
    'delete_text' => '⚠️ هل أنت متأكد أنك تريد الحذف؟ لا يمكن التراجع!',
    'delete_confirm' => 'نعم، احذفها',
    'delete_cancel' => 'إلغاء',
    // ... مفاتيحك السابقة

    // صفحة إدارة المنتجات (العنوان والوصف)
    'page_manage_products_title' => 'إدارة المنتجات',
    'page_manage_products_sub' => 'اختصارات سريعة للوحدات والأقسام وبيانات الأصناف والإعدادات',

    // الكروت
    'nav_units' => 'الوحدات',
    'nav_units_sub' => 'إدارة وحدات القياس (صغرى/وسطى/كبرى)',
    'nav_categories' => 'الأقسام',
    'nav_categories_sub' => 'إدارة التصنيفات والفئات',
    'nav_products_data' => 'بيانات الأصناف',
    'nav_products_data_sub' => 'إدارة المنتجات والباركود والتسعير',
    'nav_general_settings' => 'إعدادات عامة',
    'nav_general_settings_sub' => 'خيارات وأفضليات النظام',
    'categories_index_sub' => 'تصفّح وابحث وأدِر فئات المنتجات.',

    // ===== صفحة إنشاء منتج =====
    'product_create_title' => 'إنشاء منتج',
    'product_create_sub' => 'أدخل البيانات الأساسية، الوحدات والإعدادات، ثم احفظ المنتج.',

    // الصورة
    'product_image' => 'صورة المنتج',
    'hint_product_image' => 'الامتدادات المدعومة: PNG / JPG / WEBP — الحجم الأقصى 2MB',
    'remove' => 'إزالة',

    // الحقول الأساسية (صف 4 أعمدة)
    'sku' => 'كود الصنف',
    'hint_sku' => 'يُستخدم يدويًا في التقارير والربط مع أنظمة أخرى',
    'barcode' => 'الباركود',
    'hint_barcode' => 'اختياري — يدعم CODE128',
    'name_ar' => 'الاسم (عربي)',
    'name_en' => 'الاسم (إنجليزي)',

    // الوصف
    'description_ar' => 'الوصف (عربي)',
    'description_en' => 'الوصف (إنجليزي)',

    // القوائم المنسدلة
    'category' => 'القسم',
    'supplier' => 'المورّد',
    'choose' => 'اختر',

    // الحالة
    'status' => 'الحالة',
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',

    // جدول الوحدات
    'units_matrix' => 'بيانات الوحدات',
    'field' => 'الحقل',
    'minor' => 'الوحدة الصغرى',
    'middle' => 'الوحدة الوسطى',
    'major' => 'الوحدة الكبرى',
    'cost_price' => 'سعر التكلفة',
    'sale_price' => 'سعر البيع',
    'conv_factor' => 'معامل التحويل',
    'sale_unit' => 'وحدة البيع',
    'purchase_unit' => 'وحدة الشراء',

    // الصلاحية
    'expiry_settings' => 'صلاحية الصنف',
    'has_expiry' => 'له صلاحية؟',
    'expiry_unit' => 'وحدة الصلاحية',
    'expiry_value' => 'القيمة',
    'expiry_weekdays' => 'أيام الأسبوع',
    'day' => 'يوم',
    'month' => 'شهر',
    'year' => 'سنة',

    // أسماء أيام الأسبوع (لو حبيت تستخدمها من الترجمة)
    'wd_sat' => 'السبت',
    'wd_sun' => 'الأحد',
    'wd_mon' => 'الإثنين',
    'wd_tue' => 'الثلاثاء',
    'wd_wed' => 'الأربعاء',
    'wd_thu' => 'الخميس',
    'wd_fri' => 'الجمعة',

    // أزرار
    'btn_save' => 'حفظ',
    'btn_cancel' => 'إلغاء',
    'btn_back' => 'رجوع',

    // رسائل عامة/نجاح
    'saved_success' => 'تم الحفظ بنجاح',

    // (اختياري) رسائل تحقق شائعة للإنشاء
    'val_sku_required' => 'كود الصنف مطلوب.',
    'val_sku_unique' => 'كود الصنف مستخدم من قبل.',
    'val_name_ar_required' => 'الاسم بالعربية مطلوب.',
    'val_name_en_required' => 'الاسم بالإنجليزية مطلوب.',
    'val_factor_min' => 'معامل التحويل يجب أن يكون 1 على الأقل.',
    'val_expiry_unit_req' => 'اختر وحدة الصلاحية.',
    'view' => 'عرض',
    // ar
    'clear_selection' => 'مسح التحديد',
    'select_all' => 'تحديد الكل',
    'deselect_all' => 'إلغاء تحديد الكل',
    'invert_selection' => 'عكس التحديد',
    'bulk_actions' => 'إجراءات جماعية',
    'delete_selected' => 'حذف المحدد',
    'restore_selected' => 'استعادة المحدد',
    'export_selected' => 'تصدير المحدد',
    'print_selected' => 'طباعة المحدد',
    'change_status' => 'تغيير الحالة',
    'mark_active' => 'تعيين كـ نشط',
    'mark_inactive' => 'تعيين كـ غير نشط',
    'selected_rows' => '{0} لم يتم تحديد أي صف|{1} تم تحديد صف واحد|{2} تم تحديد صفّين|[3,10] تم تحديد :count صفوف|[11,*] تم تحديد :count صفًا',
    'basic_info_title' => 'البيانات الأساسية',
    'inventory_transactions_sub' => 'معاملات المخزون',

    // === Common / Buttons ===
    'btn_back' => 'رجوع',
    'btn_cancel' => 'إلغاء',
    'btn_save' => 'حفظ',
    'btn_update' => 'تحديث',
    'yes' => 'نعم',
    'no' => 'لا',
    'choose' => 'اختر',

    // === Products: headers ===
    'product_create_title' => 'إنشاء منتج',
    'product_create_sub' => 'أدخل البيانات الأساسية، بيانات الوحدات، وإعدادات الصلاحية',
    'product_edit_title' => 'تعديل منتج',
    'product_edit_sub' => 'عدّل البيانات الأساسية، بيانات الوحدات، وإعدادات الصلاحية',
    'saved_success' => 'تم الحفظ/التحديث بنجاح',

    // === Image ===
    'product_image' => 'صورة المنتج',
    'hint_product_image' => 'الصيغ المدعومة: PNG / JPG / WEBP بحد أقصى 2MB',
    'remove' => 'إزالة',
    'preview_selected_file' => 'تم اختيار صورة',
    'preview_existing_image' => 'صورة محفوظة مسبقًا',
    'preview_no_image' => 'لا توجد صورة',

    // === Basics (4 fields) ===
    'sku' => 'كود الصنف',
    'ph_sku' => 'مثال: PRD-0001',
    'hint_sku' => 'يُستخدم كمعرّف داخلي فريد للصنف ويُظهر في التقارير',

    'barcode' => 'الباركود',
    'ph_barcode' => 'مثال: 6221234567890',
    'hint_barcode' => 'اختياري — أدخل باركود الصنف إن وُجد (CODE128/EAN-13…)',

    'name_ar' => 'الاسم (عربي)',
    'ph_name_ar' => 'اكتب الاسم بالعربية',
    'hint_name_ar' => 'سيظهر هذا الاسم في واجهة المستخدم العربية والفواتير العربية',

    'name_en' => 'الاسم (إنجليزي)',
    'ph_name_en' => 'اكتب الاسم بالإنجليزية',
    'hint_name_en' => 'سيظهر هذا الاسم في واجهة المستخدم الإنجليزية والفواتير الإنجليزية',

    'description_ar' => 'الوصف (عربي)',
    'ph_description_ar' => 'وصف مختصر للصنف بالعربية',
    'description_en' => 'الوصف (إنجليزي)',
    'ph_description_en' => 'Short English description',
    'hint_description' => 'اختياري — يساعد على توضيح تفاصيل الصنف للمستخدم',

    'category' => 'القسم',
    'hint_category' => 'اختر القسم الذي يتبع له الصنف لتسهيل البحث والتقارير',

    'supplier' => 'المورّد',
    'hint_supplier' => 'اختر المورّد الأساسي للصنف لاستخدامه في أوامر الشراء',

    'status' => 'الحالة',
    'status_active' => 'نشط',
    'status_inactive' => 'غير نشط',
    'hint_status' => 'تحكّم في تفعيل/إيقاف الصنف للظهور والبيع',

    // === Units matrix ===
    'units_matrix' => 'بيانات الوحدات',
    'field' => 'الحقل',
    'minor' => 'الوحدة الصغرى',
    'middle' => 'الوحدة الوسطى',
    'major' => 'الوحدة الكبرى',

    'unit' => 'الوحدة',
    'hint_pick_unit' => 'اختر وحدة لهذا المستوى من الوحدات المسجلة في النظام',

    'cost_price' => 'سعر التكلفة',
    'hint_cost' => 'أدخل تكلفة الشراء/التصنيع لكل وحدة',

    'sale_price' => 'سعر البيع',
    'hint_price' => 'أدخل سعر البيع المقترح لكل وحدة',

    'conv_factor' => 'معامل التحويل',
    'hint_factor' => 'عدد الوحدات الأصغر التي تُكوّن هذه الوحدة (الافتراضي 1)',

    'sale_unit' => 'وحدة البيع',
    'hint_sale_unit' => 'اختر وحدة البيع من بين الوحدات المحددة في الجدول أعلاه',

    'purchase_unit' => 'وحدة الشراء',
    'hint_purchase_unit' => 'اختر وحدة الشراء من بين الوحدات المحددة في الجدول أعلاه',

    // === Expiry ===
    'expiry_settings' => 'صلاحية الصنف',
    'has_expiry' => 'له صلاحية؟',
    'expiry_unit' => 'وحدة الصلاحية',
    'hint_expiry_unit' => 'اختر ما إذا كانت الصلاحية بالأيام أو الأشهر أو السنوات',
    'expiry_value' => 'القيمة',
    'hint_expiry_value' => 'حدد عدد الأيام/الأشهر/السنوات حسب وحدة الصلاحية المختارة',
    'expiry_weekdays' => 'أيام الأسبوع',
    'hint_expiry_days' => 'اختر أيام الأسبوع المتاحة عند اختيار وحدة الصلاحية (يوم)',

    'day' => 'يوم',
    'month' => 'شهر',
    'year' => 'سنة',

    'sat' => 'السبت',
    'sun' => 'الأحد',
    'mon' => 'الإثنين',
    'tue' => 'الثلاثاء',
    'wed' => 'الأربعاء',
    'thu' => 'الخميس',
    'fri' => 'الجمعة',

    // === Print page (اختياري لو بتستخدم صفحة الطباعة) ===
    'print_page_title' => 'طباعة الباركود',
    'print_page_hint' => 'راجِع المعاينة ثم اضغط طباعة',
    'btn_print' => 'طباعة',
   // أزرار عامة
    'btn_save'           => 'حفظ',
    'btn_cancel'         => 'إلغاء',
    'btn_edit'           => 'تعديل',
    'btn_delete'         => 'حذف',
    'btn_back'           => 'رجوع',
    'btn_save_changes'   => 'حفظ التعديلات',
    'btn_apply_filters'  => 'تطبيق',
    'btn_reset'          => 'إعادة تعيين',

    // رسائل
    'msg_created'        => 'تم إنشاء المخزن بنجاح',
    'msg_updated'        => 'تم حفظ التعديلات بنجاح',
    'msg_deleted'        => 'تم حذف المخزن',
    'please_check_errors'=> 'من فضلك راجع أخطاء الإدخال',
    'confirm_delete_warehouse' => 'تأكيد حذف المخزن؟',
    'no_data'            => 'لا توجد بيانات',

    // العناوين
    'warehouses_title'   => 'المخازن',
    'btn_new_warehouse'  => 'مخزن جديد',
    'edit_warehouse'     => 'تعديل مخزن',

    // الحقول
    'warehouse_name_ar'  => 'اسم المخزن (ع)',
    'warehouse_name_en'  => 'اسم المخزن (En)',
    'ph_warehouse_name_ar' => 'اكتب اسم المخزن بالعربية',
    'ph_warehouse_name_en' => 'Warehouse name in English',
    'hint_warehouse_name'  => 'اكتب اسم المخزن باللغة العربية.',
    'hint_warehouse_name_en'=> 'Write the warehouse name in English.',

    'code'               => 'الكود',
    'ph_code'            => 'WH-001',
    'hint_unique_warehouse_code' => 'كود المخزن يجب أن يكون فريدًا.',

    'branch'             => 'الفرع',
    'select_branch'      => '— اختر الفرع —',
    'hint_select_branch' => 'اختر الفرع المرتبط بالمخزن.',
    'all_branches'       => 'كل الفروع',

    'status'             => 'الحالة',
    'active'             => 'نشط',
    'inactive'           => 'غير نشط',

    'warehouse_type'     => 'نوع المخزن',
    'warehouse_type_short'=> 'النوع',
    'main'               => 'رئيسي',
    'sub'                => 'فرعي',
    'hint_warehouse_type'=> 'اختر ما إذا كان المخزن رئيسيًا أو فرعيًا.',

    'warehouse_managers' => 'مسئولو المخزن',
    'hint_select_multiple_managers' => 'يمكنك اختيار أكثر من مسئول.',
    'managers_count'     => 'المسئولين',
    'none'               => 'لا يوجد',

    'warehouse_address'  => 'عنوان المخزن',
    'ph_warehouse_address' => 'مثال: 12 شارع النصر، مخزن رقم 3',
    'hint_address'       => 'أدخل وصفًا واضحًا لموقع المخزن.',

    'category'           => 'القسم',
    'select_category'    => '— اختر القسم —',
    'hint_select_category'=> 'اختر القسم أولًا ليظهر لك المنتجات التابعة له.',

    'products'           => 'المنتجات',
    'products_all_category' => 'القسم كامل (كل المنتجات)',
    'products_all_chip'  => 'القسم كامل',
    'hint_products_all'  => 'إذا اخترت “القسم كامل”، سيتم تجاهل تحديد المنتجات الفردية.',
    'err_load_products'  => 'تعذر تحميل المنتجات لهذا القسم',

    // فلاتر/بحث
    'search'             => 'بحث',
    'ph_search_wh'       => 'ابحث بالكود أو الاسم',
    'all'                => 'الكل',
    'name'               => 'الاسم',
    'actions'            => 'إجراءات',
    'select_category_first' => '— اختر القسم أولًا —',
    'no_products_in_category' => 'لا توجد منتجات في هذا القسم',
      // ===== عناوين عامة =====
    'warehouses_title'      => 'المخازن',
    'warehouse_details'     => 'تفاصيل المخزن',

    // ===== أزرار عامة =====
    'btn_back'              => 'رجوع',
    'btn_edit'              => 'تعديل',
    'btn_save'              => 'حفظ',
    'btn_save_changes'      => 'حفظ التغييرات',
    'btn_cancel'            => 'إلغاء',
    'btn_new_warehouse'     => 'مخزن جديد',
    'btn_show'              => 'عرض',
    'btn_delete'            => 'حذف',
    'btn_reset'             => 'تصفية',
    'actions'               => 'الإجراءات',

    // ===== حقول أساسية =====
    'warehouse_name'        => 'اسم المخزن',
    'warehouse_name_ar'     => 'اسم المخزن (ع)',
    'warehouse_name_en'     => 'اسم المخزن (En)',
    'code'                  => 'الكود',
    'branch'                => 'الفرع',
    'status'                => 'الحالة',
    'active'                => 'نشط',
    'inactive'              => 'غير نشط',
    'warehouse_type'        => 'نوع المخزن',
    'warehouse_type_short'  => 'النوع',
    'main'                  => 'رئيسي',
    'sub'                   => 'فرعي',
    'warehouse_address'     => 'عنوان المخزن',
    'warehouse_managers'    => 'مسئولو المخزن',
    'category'              => 'القسم',
    'products'              => 'المنتجات',

    // ===== عناصر العرض (Show) =====
    'lang_current'          => 'اللغة الحالية',
    'products_all_chip'     => 'القسم كامل',

    // ===== فلاتر وبحث (Index) =====
    'search'                => 'بحث',
    'ph_search_wh'          => 'ابحث باسم المخزن أو الكود',
    'all'                   => 'الكل',
    'all_branches'          => 'كل الفروع',
    'per_page'              => 'لكل صفحة',
    'name'                  => 'الاسم',
    'managers_count'        => 'عدد المسئولين',
    'none'                  => 'لا يوجد',
    'no_data'               => 'لا توجد بيانات',

    // ===== رسائل نجاح/أخطاء =====
    'msg_created'           => 'تم إنشاء المخزن بنجاح.',
    'msg_updated'           => 'تم تحديث بيانات المخزن بنجاح.',
    'msg_deleted'           => 'تم حذف المخزن بنجاح.',

    // ===== تلميحات (Hints/Placeholders) اختيارية =====
    'ph_code'               => 'مثل: WH-001',
    'hint_unique_warehouse_code' => 'كود المخزن يجب أن يكون فريدًا.',
    'hint_warehouse_name'   => 'أدخل اسم المخزن باللغة العربية.',
    'hint_warehouse_name_en'=> 'أدخل اسم المخزن باللغة الإنجليزية.',
    'hint_select_branch'    => 'اختر الفرع المرتبط بالمخزن.',
    'hint_warehouse_type'   => 'اختر ما إذا كان المخزن رئيسيًا أو فرعيًا.',
    'hint_address'          => 'اكتب وصفًا واضحًا للموقع.',
    'select_branch'         => 'اختر الفرع',
    'select_category'       => 'اختر القسم',
    'products_all_category' => 'القسم كامل (كل المنتجات)',
    'hint_products_all'     => 'إذا اخترت “القسم كامل” سيتم تجاهل تحديد المنتجات.',
    'select_category_first' => 'اختر قسمًا أولًا لعرض المنتجات.',
    'no_products_in_category' => 'لا توجد منتجات ضمن هذا القسم.',


    
    // ======== Common ========
    'all' => 'الكل',
    'yes' => 'نعم',
    'no' => 'لا',
    'no_value' => 'لا يوجد',
    'actions' => 'الإجراءات',
    'edit' => 'تعديل',
    'delete' => 'حذف',
    'save' => 'حفظ',
    'back' => 'عودة',
    'no_data' => 'لا توجد بيانات',
    'search' => 'بحث',
    'code' => 'الكود',
    'name' => 'الاسم',
    'description' => 'الوصف',
    'type' => 'النوع',
    'period' => 'الفترة',
    'priority' => 'الأولوية',
    'stackable' => 'قابل للدمج',
    'date_from' => 'من',
    'date_to' => 'إلى',
    'hours_from' => 'من ساعة',
    'hours_to' => 'إلى ساعة',
    'status' => 'الحالة',
    'status_active' => 'نشط',
    'status_paused' => 'موقوف',
    'status_expired' => 'منتهي',
    'status_draft' => 'مسودة',
    'toggle_status' => 'تبديل الحالة',

    // ======== Offers ========
    'offers_title' => 'العروض',
    'btn_new_offer' => 'عرض جديد',
    'create_offer' => 'إنشاء عرض',
    'edit_offer' => 'تعديل عرض',
    'type_percentage' => 'نسبة %',
    'type_fixed' => 'قيمة ثابتة',
    'type_bxgy' => 'اشتر X واحصل Y',
    'type_bundle' => 'حزمة Bundle',
    'discount_value' => 'قيمة الخصم',
    'x_qty' => 'عدد X',
    'y_qty' => 'عدد Y',
    'bundle_price' => 'سعر الحزمة',
    'max_discount_per_order' => 'حد أقصى للخصم/فاتورة',

    // ======== Coupons ========
    'coupons_title' => 'الكوبونات',
    'btn_new_coupon' => 'كوبون جديد',
    'create_coupon' => 'إنشاء كوبون',
    'edit_coupon' => 'تعديل كوبون',
    'usage' => 'الاستخدام',
    'max_uses_per_customer' => 'الحد لكل عميل',
    'max_total_uses' => 'الحد الإجمالي',
    'branches' => 'الفروع',
    'customers' => 'العملاء',

    // ======== Placeholders & Hints ========
    'ph_search_offer' => 'ابحث بالكود/الاسم',
    'ph_search_coupon' => 'ابحث عن كوبون بالكود/الاسم',
    'ph_name_ar' => 'اكتب الاسم بالعربية',
    'ph_name_en' => 'اكتب الاسم بالإنجليزية',
    'ph_description' => 'اكتب وصفًا مختصرًا',
    'hint_search_offer' => 'أدخل جزءًا من الكود أو الاسم.',
    'hint_search_coupon' => 'ابحث بكود الكوبون أو اسمه.',
    'hint_status' => 'فلتر حسب الحالة.',
    'hint_type' => 'فلتر حسب النوع.',
    'hint_type_offer' => 'اختر نوع العرض لتظهر الحقول المناسبة.',
    'hint_discount_value' => 'بالنسبة: 0-100. القيمة: أي رقم موجب.',
    'hint_bxgy_x' => 'عدد الوحدات المطلوبة للشراء.',
    'hint_bxgy_y' => 'عدد الوحدات المجانية.',
    'hint_bundle_price' => 'السعر النهائي للحزمة مجتمعة.',
    'hint_priority' => 'رقم أعلى = أولوية أعلى.',
    'hint_stackable' => 'هل يمكن دمجه مع عروض أخرى؟',
    'hint_date_range' => 'فلترة حسب فترة الصلاحية.',
    'hint_period' => 'يمكن تركها فارغة لتكون مفتوحة.',
    'hint_hours' => 'اختياري لتحديد ساعات يومية.',
    'hint_name' => 'اسم واضح يظهر في التقارير والجهاز.',
    'hint_max_discount' => 'حد أقصى للخصم على الفاتورة الواحدة.',
    'hint_max_uses_per_customer' => 'أقصى مرات استخدام لنفس العميل.',
    'hint_max_total_uses' => 'اتركها فارغة للسماح بعدد غير محدود.',

    // ======== Alerts & Messages ========
    'alert_title' => 'تحذير',
    'alert_text' => '⚠️ هل أنت متأكد من الحذف؟ لا يمكن التراجع!',
    'alert_yes_delete' => 'نعم، احذف',
    'alert_cancel' => 'إلغاء',
    'deleted' => 'تم الحذف',
    'msg_deleted_ok' => '✅ تم الحذف بنجاح.',
    'msg_saved_ok' => '✅ تم الحفظ بنجاح.',
    'msg_status_changed' => 'تم تغيير الحالة بنجاح.',
    'validation_required' => 'هذا الحقل مطلوب',

    // ======== Engine/System messages ========
    'engine_applied_higher_priority' => 'تم تطبيق عرض ذو أولوية أعلى.',
    'engine_coupon_limit_reached' => 'تخطّى العميل حد استخدام الكوبون.',
    'engine_offer_expired' => 'انتهت صلاحية العرض – لم يتم تطبيقه.',
    'engine_bulk_import_ok' => 'تم استيراد :count كود كوبون بنجاح.',
    'engine_not_eligible' => 'العرض غير مؤهل لهذا الفرع أو التاريخ.',



    'balance_title'      => 'رصيد المخزن',
    'rebuild_btn'        => 'إعادة بناء الرصيد',
    'rebuild_confirm'    => 'تأكيد إعادة بناء الرصيد؟',
    'balance_missing_table' => 'جدول stock_balances غير موجود. نفّذ الميجريشن أولاً.',
    'balance_rebuilt_ok' => 'تمت إعادة بناء رصيد المخزن بنجاح.',
    'balance_rebuilt_fail' => 'تعذّر إعادة بناء الرصيد',

    'warehouse'          => 'المخزن',
    'product'            => 'الصنف',
    'uom'                => 'الوحدة',
    'onhand'             => 'الرصيد',
    'last_update'        => 'آخر تحديث',

    'search'             => 'بحث',
    'search_ph'          => 'ابحث بالاسم/الوحدة',
    'search_btn'         => 'بحث',
    'clear'              => 'تصفية',
    'all'                => '— الكل —',
    'no_data'            => 'لا توجد بيانات — تأكد من تشغيل الميجريشن أو إدخال حركات.',

      // مشترك
    'choose'  => '— اختر —',
    'save'    => 'حفظ',
    'refresh' => 'تحديث',
    'back'    => 'رجوع',
    'search'  => 'بحث',
    'all'     => 'الكل',
    'user'    => 'المستخدم',
    'actions' => 'إجراءات',
    'no_data' => 'لا توجد بيانات',
    'per_page'=> 'لكل صفحة',

    // رسائل وتنبيهات الحذف/التأكيد
    'confirm_delete_title'      => 'تحذير',
    'confirm_delete_text'       => '⚠️ هل أنت متأكد من حذف هذا السجل؟ لا يمكن التراجع!',
    'confirm_delete_row_text'   => '⚠️ هل أنت متأكد من حذف هذا الصف؟',
    'confirm'                   => 'نعم، تأكيد',
    'cancel'                    => 'إلغاء',
    'deleted'                   => 'تم الحذف',
    'deleted_ok'                => '✅ تم الحذف بنجاح.',
    'row_deleted_ok'            => '✅ تم حذف الصف.',

    // الحالة
    'status'           => 'الحالة',
    'status_draft'     => 'مسودة',
    'status_posted'    => 'مرحّلة',
    'status_cancelled' => 'ملغاة',

    // الأنواع
    'type'                  => 'النوع',
    'trx_type'              => 'نوع الحركة',
    'trx_type_in'           => 'إدخال',
    'trx_type_out'          => 'صرف',
    'trx_type_transfer'     => 'تحويل',
    'trx_type_direct_add'   => 'إضافة مباشرة',

    // فلاتر التاريخ
    'date_from' => 'من تاريخ',
    'date_to'   => 'إلى تاريخ',

    // الحقول العامة
    'notes'     => 'ملاحظات',
    'hint_notes_json' => 'يمكن كتابة نص عادي أو JSON متعدد اللغات مثل {"ar":"ملاحظة","en":"Note"}.',

    // المخازن
    'warehouse_any'       => 'المخزن (أيّ)',
    'from_warehouse'      => 'مخزن المصدر',
    'to_warehouse'        => 'مخزن الوجهة',
    'hint_from_warehouse' => 'يُستخدم في نوعي الصرف/التحويل.',
    'hint_to_warehouse'   => 'يُستخدم في الإدخال/التحويل/الإضافة المباشرة.',

    // الأصناف/الوحدات
    'product'        => 'الصنف',
    'choose_product' => '— اختر الصنف —',
    'hint_product'   => 'اختر الصنف المراد الحركة عليه.',

    'unit'        => 'الوحدة',
    'choose_unit' => '— اختر الوحدة —',
    'hint_unit'   => 'اختر وحدة القياس (كبرى/وسطى/صغرى) إن وُجدت.',

    'uom_text'      => 'الوحدة (نص)',
    'hint_uom_text' => 'اكتب اختصاراً مثل PCS / BOX إن لزم.',

    // الكميات والرصد
    'qty'        => 'الكمية',
    'hint_qty'   => 'أدخل الكمية بدقة بأربع منازل عشريّة.',
    'onhand'     => 'الرصيد',

    // الدُفعات/الصلاحية/السبب
    'expiry_date' => 'تاريخ الصلاحية',
    'hint_expiry' => 'اختياري: إن كانت السلعة ذات صلاحية.',
    'batch_no'    => 'رقم التشغيلة',
    'hint_batch'  => 'اختياري: رقم الدُفعة/التشغيلة.',
    'reason'      => 'السبب',
    'reason_ph'   => 'سبب الحركة (اختياري)',
    'hint_reason' => 'اكتب سبب الحركة للاسترشاد مستقبلاً.',

    // عناصر الجدول التفصيلي
    'trx_items_title' => 'تفاصيل الحركة',
    'add_row'         => 'إضافة صف',
    'remove_row'      => 'حذف الصف',

    // نموذج الحركة (إضافة/تعديل)
    'trx_form_title'  => 'إضافة حركة مخزون',
    'trx_edit_title'  => 'تعديل حركة مخزون',
    'trx_date'        => 'تاريخ الحركة',
    'hint_trx_date'   => 'حدد اليوم الذي تمت فيه الحركة.',
    'hint_trx_type'   => 'اختر نوع الحركة المراد تنفيذها.',

    // قائمة الحركات (Index)
    'trx_index_title'    => 'حركات المخزون',
    'trx_index_subtitle' => 'استعراض وفلاتر وتقارير الحركات',
    'trx_new'            => 'حركة جديدة',
    'search_ph_trx'      => 'ابحث برقم الحركة/الملاحظات/النوع',
    'trx_no'             => 'رقم الحركة',
    'warehouse'          => 'المخزن',
    'from'               => 'من',
    'to'                 => 'إلى',

     // ===== مشتريات - القائمة =====
    'purchases_title'            => 'المشتريات',
    'purchases_index_title'      => 'قائمة المشتريات',
    'purchases_index_subtitle'   => 'إدارة فواتير الشراء مع الفلاتر والعمليات.',
    'purchases_new'              => 'فاتورة شراء جديدة',

    // شريط أدوات القائمة
    'search'                     => 'بحث',
    'purchases_search_ph'        => 'ابحث برقم الفاتورة أو الملاحظات…',
    'status'                     => 'الحالة',
    'all'                        => 'الكل',
    'date_from'                  => 'من تاريخ',
    'date_to'                    => 'إلى تاريخ',
    'per_page'                   => 'لكل صفحة',

    // أعمدة الجدول في القائمة
    'purchase_no'                => 'رقم الفاتورة',
    'purchase_date'              => 'تاريخ الشراء',
    'delivery_date'              => 'تاريخ التوريد',
    'supplier'                   => 'المورّد',
    'warehouse'                  => 'المخزن',
    'items_count'                => 'عدد البنود',
    'grand_total'                => 'الإجمالي',
    'actions'                    => 'إجراءات',

    // حالات
    'status_draft'               => 'مسودة',
    'status_approved'            => 'موافق عليها',
    'status_posted'              => 'مرحّلة',
    'status_cancelled'           => 'ملغاة',

    // رسائل عامة
    'no_data'                    => 'لا توجد بيانات.',
    'not_found'                  => 'العنصر غير موجود.',
    'deleted_ok'                 => 'تم الحذف بنجاح.',
    'saved_ok'                   => 'تم الحفظ بنجاح.',
    'na'                         => 'غير متوفر',

    // أزرار عامة
    'save'                       => 'حفظ',
    'back'                       => 'رجوع',
    'refresh'                    => 'تحديث',
    'choose'                     => '— اختر —',

    // ===== مشتريات - النموذج (إنشاء/تعديل) =====
    'purchase_title'             => 'فاتورة شراء',
    'hint_purchase_date'         => 'اختر تاريخ إنشاء الفاتورة.',
    'hint_delivery_date'         => 'تاريخ استلام وتوريد البضاعة (اختياري).',
    'hint_warehouse'             => 'اختر المخزن الذي ستُضاف إليه الأصناف.',
    'hint_supplier'              => 'اختر المورّد لهذه الفاتورة.',
    'notes_ar'                   => 'ملاحظات (عربي)',
    'notes_en'                   => 'ملاحظات (إنجليزي)',
    'notes_ph'                   => 'أدخل أي ملاحظات حول الفاتورة…',

    // تفاصيل البنود
    'items_details'              => 'تفاصيل الأصناف',
    'add_row'                    => 'إضافة صف',
    'remove_row'                 => 'حذف الصف',
    'product'                    => 'الصنف',
    'code'                       => 'الكود',
    'category'                   => 'الفئة',
    'unit'                       => 'الوحدة',
    'qty'                        => 'الكمية',
    'unit_price'                 => 'سعر الوحدة',
    'onhand'                     => 'الرصيد',
    'expiry_date'                => 'تاريخ الصلاحية',
    'batch_no'                   => 'رقم التشغيلة',
    'choose_product'             => '— اختر الصنف —',
    'choose_unit'                => '— اختر الوحدة —',

    // أخطاء وتحقق
    'input_errors'               => 'حدثت أخطاء في الإدخال',

    // تأكيدات الحذف (صف/فاتورة)
    'confirm_delete_title'       => 'تحذير',
    'confirm_delete_text'        => 'هل تريد حذف هذه الفاتورة؟ لا يمكن التراجع.',
    'confirm_delete_row_text'    => 'هل تريد حذف هذا الصف؟ لا يمكن التراجع.',
    'confirm'                    => 'تأكيد',
    'cancel'                     => 'إلغاء',
    'deleted'                    => 'تم الحذف',
    'row_deleted_ok'             => 'تم حذف الصف بنجاح.',
'select_category' => '— اختر القسم —',
'has_expiry' => 'له تاريخ صلاحية؟',
'required_expiry_for_checked' => 'هذا الصف محدد كتاريخ صلاحية، الرجاء إدخال تاريخ الصلاحية.',
'at_least_one_row' => 'يجب إضافة صف واحد على الأقل.',
'purchase_invoice' => 'فاتورة شراء',
    'purchase_no'      => 'رقم الفاتورة',
    'purchase_date'    => 'تاريخ الشراء',
    'delivery_date'    => 'تاريخ التوريد',
    'supplier'         => 'المورد',
    'warehouse'        => 'المخزن',
    'created_by'       => 'أنشأ بواسطة',
    'product'          => 'الصنف',
    'code'             => 'الكود',
    'unit'             => 'الوحدة',
    'qty'              => 'الكمية',
    'unit_price'       => 'سعر الوحدة',
    'row_total'        => 'الإجمالي',
    'expiry_date'      => 'تاريخ الصلاحية',
    'batch_no'         => 'رقم التشغيلة',
    'subtotal'         => 'الإجمالي الفرعي',
    'discount'         => 'الخصم',
    'tax'              => 'الضريبة',
    'grand_total'      => 'الإجمالي الكلي',
    'notes'            => 'ملاحظات',
    'print'            => 'طباعة',
    'print_now'        => 'اطبع الآن',
    'back'             => 'رجوع',
    'status'           => 'الحالة',
    'status_draft'     => 'مسودة',
    'status_approved'  => 'معتمدة',
    'status_posted'    => 'مُرحلة',
    'status_cancelled' => 'ملغاة',
      'purchase_show_title' => 'عرض فاتورة شراء',
    'purchase_invoice'    => 'فاتورة شراء',
    'purchase_no'         => 'رقم الفاتورة',
    'purchase_date'       => 'تاريخ الشراء',
    'delivery_date'       => 'تاريخ التوريد',
    'warehouse'           => 'المخزن',
    'supplier'            => 'المورّد',
    'status'              => 'الحالة',
    'status_draft'        => 'مسودة',
    'status_approved'     => 'معتمدة',
    'status_posted'       => 'مرحّلة',
    'status_cancelled'    => 'ملغاة',
    'product'             => 'الصنف',
    'unit'                => 'الوحدة',
    'qty'                 => 'الكمية',
    'unit_price'          => 'سعر الوحدة',
    'expiry_date'         => 'تاريخ الصلاحية',
    'batch_no'            => 'رقم التشغيلة',
    'total'               => 'الإجمالي',
    'subtotal'            => 'الإجمالي الفرعي',
    'discount'            => 'الخصم',
    'tax'                 => 'الضريبة',
    'grand_total'         => 'الإجمالي الكلي',
    'notes'               => 'ملاحظات',
    'print'               => 'طباعة',
    'edit'                => 'تعديل',
    'back'                => 'رجوع',
       'change_status'          => 'تغيير الحالة',
    'confirm_status_change'  => 'هل أنت متأكد أنك تريد تغيير الحالة؟',
    'yes_change'             => 'نعم، تغيير',
    'status_changed'         => 'تم تغيير الحالة',
    'status_changed_ok'      => '✅ تم تغيير الحالة بنجاح.',
     // ... مفاتيح أخرى

    'trx_type_in'          => 'وارد',
    'trx_type_out'         => 'منصرف',
    'trx_type_transfer'    => 'تحويل',
    'trx_type_direct_add'  => 'إضافة مباشرة',

    // اختياري: في حالة نوع غير معروف
    'trx_type_unknown'     => 'غير معروف',
     'stock_trx_type' => [
        'sales_issue'      => 'صرف مبيعات',
        'sales_return'     => 'مرتجع مبيعات',
        'adjustment'       => 'تسوية / جرد',
        'transfer'         => 'تحويل مخزني',
        'purchase_receive' => 'استلام مشتريات',
    ],


    'pos_index_title'      => 'فواتير المبيعات',
    'pos_index_subtitle'   => 'بحث سريع وإدارة فواتير المبيعات',
    'pos_new'              => 'فاتورة جديدة',

    'purchase_title'       => 'إنشاء فاتورة بيع',
    'purchase_title_edit'  => 'تعديل فاتورة بيع',
    'invoice_view'         => 'عرض الفاتورة',
    'print'                => 'طباعة',

    'sale_no'      => 'رقم الفاتورة',
    'sale_date'    => 'تاريخ البيع',
    'delivery_date'=> 'تاريخ التسليم',
    'warehouse'    => 'المخزن',
    'customer'     => 'العميل',
    'notes_ar'     => 'ملاحظات (عربي)',
    'notes_en'     => 'ملاحظات (إنجليزي)',
    'notes_ph'     => 'اكتب ملاحظات مختصرة...',

    'category'     => 'القسم',
    'product'      => 'الصنف',
    'unit'         => 'الوحدة',
    'qty'          => 'الكمية',
    'unit_price'   => 'سعر الوحدة',
    'line_total'   => 'إجمالي السطر',
    'onhand'       => 'المتاح',
    'expiry_date'  => 'تاريخ الصلاحية',
    'batch_no'     => 'رقم التشغيلة',
    'has_expiry'   => 'له تاريخ صلاحية؟',

    'items_count'  => 'عدد البنود',
    'subtotal'     => 'الإجمالي',
    'discount'     => 'الخصم',
    'tax'          => 'الضريبة',
    'grand_total'  => 'الإجمالي النهائي',

    'search'       => 'بحث',
    'search_ph'    => 'ابحث برقم الفاتورة أو الملاحظات',
    'status'       => 'الحالة',
    'status_draft'     => 'مسودة',
    'status_approved'  => 'معتمد',
    'status_posted'    => 'مرحّل',
    'status_cancelled' => 'ملغي',
    'actions'      => 'إجراءات',
    'per_page'     => 'لكل صفحة',
    'all'          => 'الكل',

    'date_from'    => 'من تاريخ',
    'date_to'      => 'إلى تاريخ',

    'choose'       => '— اختر —',
    'select_category' => '— اختر القسم —',
    'choose_product'=> '— اختر الصنف —',
    'choose_unit'   => '— اختر الوحدة —',

    'add_row'      => 'إضافة صف',
    'remove_row'   => 'حذف الصف',

    'save'         => 'حفظ',
    'update'       => 'تحديث',
    'back'         => 'رجوع',
    'refresh'      => 'تحديث',

    'no_data'      => 'لا توجد بيانات',
    'not_found'    => 'العنصر غير موجود',
    'saved_ok'     => 'تم الحفظ بنجاح',
    'deleted_ok'   => 'تم الحذف  بنجاح',
    'status_changed_ok' => 'تم تغيير الحالة بنجاح',

    'confirm_delete_title' => 'تحذير',
    'confirm_delete_text'  => '⚠️ هل أنت متأكد أنك تريد حذف هذا الإجراء لا يمكن التراجع عنه!',
    'confirm' => 'تأكيد',
    'cancel'  => 'إلغاء',
    'deleted' => 'تم الحذف!',
    'sales_title' => 'إدارة المبيعات', 

      // ========= عام / جنريك =========
    'input_errors'   => 'توجد أخطاء في الإدخال، يرجى المراجعة.',
    'back'           => 'رجوع',
    'refresh'        => 'تحديث',
    'save'           => 'حفظ',
    'update'         => 'تحديث',
    'created_at'     => 'تاريخ الإنشاء',
    'updated_at'     => 'آخر تحديث',
    'actions'        => 'إجراءات',
    'show'           => 'عرض',
    'edit'           => 'تعديل',
    'delete'         => 'حذف',
    'change_status'  => 'تغيير الحالة',
    'per_page'       => 'لكل صفحة',
    'search'         => 'بحث',
    'all'            => 'الكل',
    'date_from'      => 'من تاريخ',
    'date_to'        => 'إلى تاريخ',
    'no_data'        => 'لا توجد بيانات',
    'records_showing'=> 'المعروض الآن',
    'records_total'  => 'إجمالي السجلات',

    // رسائل نظام
    'confirm_delete_title'     => 'تأكيد الحذف',
    'confirm_delete_text'      => 'هل أنت متأكد من الحذف؟ هذا الإجراء لا يمكن التراجع عنه.',
    'confirm_delete_row_text'  => 'هل تريد حذف هذا السطر؟',
    'confirm'        => 'تأكيد',
    'cancel'         => 'إلغاء',
    'deleted'        => 'تم الحذف',
    'deleted_ok'     => 'تم حذف السجل بنجاح.',
    'row_deleted_ok' => 'تم حذف السطر بنجاح.',
    'not_found'      => 'العنصر غير موجود.',
    'saved_ok'       => 'تم الحفظ بنجاح.',
    'updated_ok'     => 'تم التحديث بنجاح.',
    'status_changed' => 'تم تغيير الحالة بنجاح.',

    // ========= الحالات =========
    'status'           => 'الحالة',
    'status_all'       => 'كل الحالات',
    'status_active'    => 'نشط',
    'status_inactive'  => 'غير نشط',

    // (للمشتريات)
    'status_draft'     => 'مسودة',
    'status_approved'  => 'معتمد',
    'status_posted'    => 'مرحّلة',
    'status_cancelled' => 'ملغاة',

    // ========= عناصر المينيو =========
    'purchases_title'         => 'المشتريات',
    'inventory_title'         => 'المخزون',
    'products_index_title'    => 'المنتجات',
    'supplier_title'          => 'المورّدون',
    'offers_title'            => 'العروض',
    'coupons_title'           => 'الكوبونات',
    'title_customers_index'   => 'العملاء',

    // ========= العملاء (Index) =========
    'customers_index_title'    => 'إدارة العملاء',
    'customers_index_subtitle' => 'عرض وإدارة بيانات العملاء مع خيارات البحث والتصفية.',
    'customers_new'            => 'عميل جديد',
    'customers_search_ph'      => 'ابحث بالاسم / الكود / الهاتف / البريد',

    // ========= العملاء (Manage/Create/Edit) =========
    'customer_title_create' => 'إضافة عميل',
    'customer_title_edit'   => 'تعديل عميل',

    'code'         => 'الكود',
    'hint_code'    => 'مثال: CUS-0001 (اختياري وغير متكرر).',

    'type'         => 'النوع',
    'person'       => 'فرد',
    'company'      => 'شركة',
    'hint_type'    => 'اختر نوع العميل: فرد أو شركة.',

    'status_active_label'   => 'نشط',
    'status_inactive_label' => 'غير نشط',
    'hint_status'           => 'حدد حالة ظهور وتفعيل العميل بالنظام.',

    'country'      => 'الدولة',
    'hint_country' => 'اكتب رمز الدولة أو اسمها (اختياري).',

    'name'      => 'الاسم',
    'name_ar'   => 'الاسم بالعربية',
    'name_en'   => 'الاسم بالإنجليزية',
    'hint_name_ar' => 'الاسم بالعربية مطلوب.',
    'hint_name_en' => 'يمكن كتابة الاسم باللغة الإنجليزية (اختياري).',

    'phone'      => 'الهاتف',
    'hint_phone' => 'رقم الهاتف (اختياري).',
    'mobile'      => 'الجوال',
    'hint_mobile' => 'رقم الجوال (اختياري).',
    'email'       => 'البريد الإلكتروني',
    'hint_email'  => 'اكتب بريدًا صحيحًا (اختياري).',

    'city_ar'   => 'المدينة (عربي)',
    'city_en'   => 'المدينة (إنجليزي)',
    'hint_city_ar' => 'المدينة بالعربية (اختياري).',
    'hint_city_en' => 'المدينة بالإنجليزية (اختياري).',

    'address_ar'   => 'العنوان (عربي)',
    'address_en'   => 'العنوان (إنجليزي)',
    'hint_address_ar' => 'العنوان بالعربية (اختياري).',
    'hint_address_en' => 'العنوان بالإنجليزية (اختياري).',

    'tax_no'           => 'الرقم الضريبي',
    'hint_tax_no'      => 'أدخل الرقم الضريبي إن وجد.',
    'commercial_no'    => 'السجل التجاري',
    'hint_commercial_no'=> 'أدخل رقم السجل التجاري إن وجد.',

    'notes_ar'   => 'ملاحظات (عربي)',
    'notes_en'   => 'ملاحظات (إنجليزي)',
    'notes_ph'   => 'اكتب أي ملاحظات إضافية...',

    // ========= المشتريات (للاستخدام السابق) =========
    'purchase_title'         => 'إنشاء فاتورة مشتريات',
    'purchase_title_edit'    => 'تعديل فاتورة مشتريات',
    'purchases_index_title'  => 'قائمة فواتير المشتريات',
    'purchases_index_subtitle'=> 'إدارة فواتير المشتريات والبحث فيها.',
    'purchases_new'          => 'فاتورة جديدة',
    'purchases_search_ph'    => 'ابحث برقم الفاتورة / المورد / المخزن',

    'purchase_no'    => 'رقم الفاتورة',
    'purchase_date'  => 'تاريخ الشراء',
    'delivery_date'  => 'تاريخ التوريد',
    'supplier'       => 'المورّد',
    'warehouse'      => 'المخزن',
    'items_count'    => 'عدد البنود',
    'grand_total'    => 'الإجمالي',

    'category'     => 'القسم',
    'product'      => 'المنتج',
    'unit'         => 'الوحدة',
    'qty'          => 'الكمية',
    'unit_price'   => 'سعر الوحدة',
    'onhand'       => 'الرصيد الحالي',
    'expiry_date'  => 'تاريخ الصلاحية',
    'batch_no'     => 'رقم التشغيلة/الدفعة',

    'choose'           => 'اختر',
    'choose_product'   => '— اختر المنتج —',
    'choose_unit'      => '— اختر الوحدة —',
    'select_category'  => '— اختر القسم —',
    'has_expiry'       => 'له تاريخ صلاحية؟',
    'items_details'    => 'تفاصيل الأصناف',
    'add_row'          => 'إضافة سطر',
    'remove_row'       => 'حذف السطر',
    'hint_purchase_date'=> 'حدد تاريخ إنشاء فاتورة الشراء.',
    'hint_delivery_date'=> 'حدد تاريخ التوريد للمخزن.',
    'hint_supplier'     => 'اختر المورد المرتبط بالفاتورة.',
    'hint_warehouse'    => 'اختر المخزن المستلم.',

    // ========= وحدات واجهة المخزون (كروت) =========
    'module_stock_balance' => 'رصيد المخزن',
    'module_direct_store'  => 'إضافة مباشرة إلى المخزن',
    'route_missing'        => 'المسار غير متوفر',
    'open'                 => 'فتح',
    'soon'                 => 'قريبًا',
      // عامة
    'all' => 'الكل',
    'yes' => 'نعم',
    'no'  => 'لا',
    'search' => 'بحث',
    'status' => 'الحالة',
    'branch' => 'الفرع',
    'date_from' => 'التاريخ من',
    'date_to'   => 'التاريخ إلى',
    'per_page'  => '/صفحة',
    'btn_new'   => 'جديد',
    'btn_edit'  => 'تعديل',
    'btn_delete'=> 'حذف',
    'btn_show'  => 'عرض',
    'btn_save'  => 'حفظ',
    'btn_back'  => 'رجوع',
    'btn_toggle_status' => 'تغيير الحالة',
    'status_active'   => 'نشط',
    'status_inactive' => 'غير نشط',
    'no_data' => 'لا توجد بيانات',
    'pagination_info' => 'عرض :from إلى :to من أصل :total صف',

    // تنبيهات الحذف (SweetAlert2)
    'alert_title' => 'تحذير',
    'alert_text'  => '⚠️ هل أنت متأكد أنك تريد حذف هذا الإجراء؟ لا يمكن التراجع!',
    'alert_confirm' => 'نعم، احذف',
    'alert_cancel'  => 'إلغاء',
    'alert_deleted_title' => 'تم الحذف!',
    'alert_deleted_text'  => '✅ تم الحذف بنجاح.',

    // رسائل عمليات
    'msg_created_success' => 'تم الإنشاء بنجاح.',
    'msg_updated_success' => 'تم التعديل بنجاح.',
    'msg_deleted_success' => 'تم الحذف بنجاح.',
    'msg_status_toggled'  => 'تم تغيير الحالة بنجاح.',

    // عناوين صفحات finance
    'finance_title_index'  => 'الخزائن',
    'finance_title_create' => 'إنشاء خزنة',
    'finance_title_edit'   => 'تعديل خزنة',
    'finance_title_show'   => 'عرض خزنة',

    // فلاتر البحث في index
    'ph_search_finance' => 'ابحث بالاسم (AR/EN) أو البادئة',
    'hint_search_finance'=> 'إدخال جزء من الاسم أو البادئة',

    // أعمدة الجدول
    'col_name'     => 'الاسم',
    'col_branch'   => 'الفرع',
    'col_currency' => 'العملة',
    'col_prefix'   => 'البادئة',
    'col_next_no'  => 'أول رقم',
    'col_status'   => 'الحالة',
    'col_actions'  => 'إجراءات',

    // حقول الفورم
    'f_name_ar' => 'الاسم (عربي)',
    'f_name_en' => 'الاسم (English)',
    'f_branch'  => 'الفرع',
    'f_currency'=> 'العملة',
    'f_prefix'  => 'بادئة الترقيم',
    'f_next_no' => 'أول رقم متسلسل',
    'f_allow_negative' => 'السماح بالسالب',
    'lbl_switch' => 'تفعيل/تعطيل',
    'f_status'  => 'الحالة',
    'f_notes_ar'=> 'ملاحظات (عربي)',
    'f_notes_en'=> 'Notes (EN)',

    // Placeholders + Help
    'ph_name_ar'   => 'مثال: خزينة رئيسية',
    'ph_name_en'   => 'e.g., Main Cashbox',
    'ph_branch_id' => 'رقم الفرع (اختياري)',
    'ph_currency_id' => 'رقم العملة (اختياري)',
    'ph_prefix'    => 'مثال: CBX',
    'ph_notes_ar'  => 'اكتب ملاحظات بالعربية...',
    'ph_notes_en'  => 'Write notes in English...',

    'help_name_ar' => 'أدخل اسم الخزنة باللغة العربية.',
    'help_name_en' => 'Enter the cashbox name in English.',
    'help_branch'  => 'اختياري: اربط الخزنة بفرع محدد.',
    'help_currency'=> 'اختياري: اختر عملة الخزنة.',
    'help_prefix'  => 'تُستخدم كبادئة لتكوين رقم الإيصال.',
    'help_next_no' => 'أول رقم يُستخدم عند إنشاء الإيصال التالي.',
    'help_notes'   => 'ملاحظات داخلية تظهر في السجلات فقط.',
    'cashboxes_hub_title'     => 'إدارة الخزائن',
'cashboxes_hub_subtitle'  => 'وحدات وأدوات سريعة لإدارة الخزائن',
'open'                    => 'فتح',
'cashboxes_settings'      => 'إعدادات الخزائن',
'cashboxes_movements'     => 'حركات الخزائن',
'cashboxes_shifts'        => 'استلام/تسليم الخزينة',
'cashboxes_receipts'      => 'الإيصالات',
// ===== عناوين عامة =====
    'pos_title_new'      => 'فاتورة بيع جديدة',
    'pos_title_edit'     => 'تعديل فاتورة بيع',
    'pos_index_title'    => 'فواتير البيع',
    'pos_index_subtitle' => 'إدارة وفلترة فواتير البيع الخاصة بك',

    // ===== حقول الرأس في صفحة الإنشاء/التعديل =====
    'sale_date'          => 'تاريخ البيع',
    'delivery_date'      => 'تاريخ التوريد',
    'warehouse'          => 'المخزن',
    'customer'           => 'العميل',

    // ===== تلميحات (Hints) المفقودة التي ظهرت كمفاتيح =====
    'hint_sale_date'     => 'حدد تاريخ البيع',
    'hint_delivery_date' => 'حدد تاريخ التوريد للمخزن',
    'hint_warehouse'     => 'اختر المخزن المستلم',
    'hint_customer'      => 'اختر العميل',

    // ===== الملاحظات =====
    'notes_ar'           => 'ملحوظات (عربي)',
    'notes_en'           => 'ملحوظات (إنجليزي)',
    'notes_ph'           => 'اكتب أي ملاحظات إضافية...',

    // ===== تفاصيل الأصناف =====
    'items_details'      => 'تفاصيل الأصناف',
    'category'           => 'القسم',
    'product'            => 'المنتج',
    'unit'               => 'الوحدة',
    'qty'                => 'الكمية',
    'unit_price'         => 'سعر الوحدة',
    'onhand'             => 'الرصيد الحالي',
    'expiry_date'        => 'تاريخ الصلاحية',
    'batch_no'           => 'رقم التشغيلة/الدفعة',
    'has_expiry'         => 'له تاريخ صلاحية؟',

    // ===== خيارات/Placeholders =====
    'choose'             => 'اختر',
    'select_category'    => 'اختر القسم',
    'choose_product'     => 'اختر المنتج',
    'choose_unit'        => 'اختر الوحدة',

    // ===== الأزرار =====
    'add_row'            => 'إضافة سطر',
    'remove_row'         => 'حذف السطر',
    'save'               => 'حفظ',
    'update'             => 'تحديث',
    'back'               => 'رجوع',
    'refresh'            => 'تحديث',
    'saved_ok'           => 'تم الحفظ بنجاح',

    // ===== المجاميع =====
    'subtotal'           => 'الإجمالي',
    'discount'           => 'الخصم',
    'tax'                => 'الضريبة',
    'grand_total'        => 'الإجمالي الكلي',

    // ===== تأكيدات ورسائل =====
    'confirm_delete_title'     => 'تأكيد الحذف',
    'confirm_delete_row_text'  => 'هل أنت متأكد من حذف هذا السطر؟',
    'confirm_delete_text'      => 'هل أنت متأكد من الحذف؟ لا يمكن التراجع.',
    'confirm'                  => 'تأكيد',
    'cancel'                   => 'إلغاء',
    'deleted'                  => 'تم الحذف',
    'deleted_ok'               => 'تمت عملية الحذف بنجاح',
    'row_deleted_ok'           => 'تم حذف السطر بنجاح',

    // ===== أخرى مستخدمة في الفلاتر والجداول =====
    'per_page'          => 'لكل صفحة',
    'search'            => 'بحث',
    'search_ph'         => 'ابحث برقم الفاتورة أو العميل...',
    'status'            => 'الحالة',
    'status_draft'      => 'مسودة',
    'status_approved'   => 'موافق عليها',
    'status_posted'     => 'مُرحّلة',
    'status_cancelled'  => 'ملغاة',
    'sale_no'           => 'رقم الفاتورة',
    'items_count'       => 'عدد البنود',
    'actions'           => 'الإجراءات',
    'no_data'           => 'لا توجد بيانات',

    // ===== عامة =====
    'all' => 'الكل',
    'yes' => 'نعم',
    'no'  => 'لا',
    'enabled'  => 'مفعّل',
    'disabled' => 'معطّل',
    'open' => 'فتح',

    // Buttons
    'btn_new'    => 'جديد',
    'btn_edit'   => 'تعديل',
    'btn_delete' => 'حذف',
    'btn_show'   => 'عرض',
    'btn_save'   => 'حفظ',
    'btn_back'   => 'رجوع',
    'btn_toggle_status' => 'تغيير الحالة',
    'btn_add_row'    => 'إضافة صف',
    'btn_remove_row' => 'إزالة صف',

    // Table / misc
    'col_actions' => 'إجراءات',
    'no_data'     => 'لا توجد بيانات',
    'pagination_info' => 'عرض :from إلى :to من أصل :total صف',

    // Alerts (SweetAlert2)
    'alert_title' => 'تحذير',
    'alert_text'  => '⚠️ هل أنت متأكد؟ هذا الإجراء لا يمكن التراجع عنه!',
    'alert_confirm' => 'نعم، متابعة',
    'alert_cancel'  => 'إلغاء',
    'alert_deleted_title' => 'تم الحذف!',
    'alert_deleted_text'  => '✅ تم الحذف بنجاح.',

    // Flash messages
    'msg_created_success' => 'تم الإنشاء بنجاح.',
    'msg_updated_success' => 'تم التعديل بنجاح.',
    'msg_deleted_success' => 'تم الحذف بنجاح.',
    'msg_status_toggled'  => 'تم تغيير الحالة بنجاح.',
    'msg_saved_finset'    => 'تم حفظ إعدادات المخازن بنجاح.',

    // ===== لوحة الخزائن (أربع كروت) =====
    'cashboxes_hub_title'     => 'إدارة الخزائن',
    'cashboxes_hub_subtitle'  => 'وحدات وأدوات سريعة لإدارة الخزائن',
    'cashboxes_settings'      => 'إعدادات الخزائن',
    'cashboxes_movements'     => 'حركات الخزائن',
    'cashboxes_shifts'        => 'استلام/تسليم الخزينة',
    'cashboxes_receipts'      => 'الإيصالات',
    'cashboxes_settings_desc' => 'تعريف الخزائن، الترقيم، الصلاحيات والربط المحاسبي.',
    'cashboxes_movements_desc'=> 'تصفح الحركات مع الفلاتر والطباعة/التصدير.',
    'cashboxes_shifts_desc'   => 'فتح وإغلاق الشفت وتسجيل الجرد والفروقات.',
    'cashboxes_receipts_desc' => 'قبض/صرف وربط محاسبي مع المرفقات.',

    // (عناوين قديمة إن كنت تستخدمها)
    'finance_title_index'  => 'الخزائن',
    'finance_title_create' => 'إنشاء خزنة',
    'finance_title_edit'   => 'تعديل خزنة',
    'finance_title_show'   => 'عرض خزنة',

    // ===== إعدادات المخازن (finance_settings) =====
    'finset_title'   => 'إعدادات المخازن',
    'creating'       => 'إنشاء',
    'editing'        => 'تعديل',

    // الاسم
    'finset_name_ar'        => 'الاسم (عربي)',
    'finset_ph_name_ar'     => 'مثال: إعدادات مخزن رئيسي',
    'finset_help_name_ar'   => 'اكتب اسم الإعداد باللغة العربية.',
    'finset_name_en'        => 'Name (English)',
    'finset_ph_name_en'     => 'e.g., Main Warehouse Settings',
    'finset_help_name_en'   => 'Enter the settings name in English.',

    // ربطات
    'finset_branch'         => 'الفرع',
    'finset_ph_branch'      => 'رقم الفرع (اختياري)',
    'finset_help_branch'    => 'اختياري: اربط الإعداد بفرع محدد.',
    'finset_warehouse'      => 'المخزن',
    'finset_ph_warehouse'   => 'رقم المخزن (اختياري)',
    'finset_help_warehouse' => 'اختياري: اختر المخزن الافتراضي.',
    'finset_currency'       => 'العملة',
    'finset_ph_currency'    => 'رقم العملة (اختياري)',
    'finset_help_currency'  => 'اختياري: العملة الافتراضية للعمليات.',

    // مفاتيح التحكم
    'finset_is_available'            => 'متاح أم لا',
    'finset_allow_negative_stock'    => 'السماح برصيد سالب للمخزون',
    'finset_return_window_days'      => 'مهلة الارتجاع (أيام)',
    'finset_help_return_window_days' => 'عدد الأيام المسموح خلالها بقبول الارتجاع.',
    'finset_require_return_approval' => 'يتطلب موافقة على الارتجاع',
    'finset_approval_over_amount'    => 'موافقة إذا تجاوز المبلغ',
    'finset_help_approval_over_amount'=> 'اتركه فارغًا لتعطيل الشرط.',

    // ترقيم الارتجاع
    'finset_receipt_prefix'          => 'بادئة ترقيم الارتجاع',
    'finset_help_receipt_prefix'     => 'تستخدم في تكوين رقم الارتجاع التالي.',
    'finset_next_return_number'      => 'أول رقم تسلسلي للارتجاع',
    'finset_help_next_return_number' => 'أول رقم سيُستخدم في عملية الارتجاع التالية.',

    // ملاحظات
    'finset_notes_ar'      => 'ملاحظات (عربي)',
    'finset_ph_notes_ar'   => 'اكتب ملاحظات بالعربية...',
    'finset_notes_en'      => 'Notes (EN)',
    'finset_ph_notes_en'   => 'Write notes in English...',
    'finset_help_notes'    => 'ملاحظات داخلية لأغراض المرجعية فقط.',

    // حدود المستخدمين
    'finset_user_limits_section' => 'حدود المستخدمين (الحد الأقصى للارتجاع)',
    'finset_ul_user'             => 'المستخدم',
    'finset_ul_ph_user'          => 'ID المستخدم',
    'finset_ul_help_user'        => 'اكتب رقم المستخدم أو اختره لاحقًا من قائمة.',
    'finset_ul_daily_count'      => 'حد يومي للعدد',
    'finset_ul_help_daily_count' => 'أقصى عدد عمليات ارتجاع يوميًا.',
    'finset_ul_daily_amount'     => 'حد يومي للمبلغ',
    'finset_ul_help_daily_amount'=> 'أقصى إجمالي مبالغ الارتجاع يوميًا.',
    'finset_ul_require_supervisor'=> 'يتطلب مشرف',
    'finset_ul_active'           => 'نشط',
    'finset_ul_no_rows'          => 'لا توجد حدود مضافة بعد.',


    'finset_cashbox_type' => 'نوع الخزينة',
    'trashed' => 'محذوفة',
    'active_only' => 'نشط فقط',
    'with_trashed' => 'مع المحذوفات',
    'only_trashed' => 'المحذوف فقط',
    'finset_cashbox_type_main' => 'الخزينة الرئيسية',
    'finset_cashbox_type_sub' => 'خزينة فرعية',
    'finset_help_cashbox_type' => 'اختر نوع الخزينة: رئيسية أو فرعية.',

    // Movements
'mov_title_index'   => 'حركات الخزائن',
'mov_title_manage'  => 'إدارة حركة خزينة',
'mov_in'            => 'قبض',
'mov_out'           => 'صرف',
'date'              => 'التاريخ',
'amount'            => 'المبلغ',
'currency'          => 'العملة',
'method'            => 'طريقة الدفع',
'method_cash'       => 'نقدي',
'method_bank'       => 'بنكي',
'method_pos'        => 'نقطة بيع',
'method_transfer'   => 'تحويل',
'doc_no'            => 'رقم المستند',
'reference'         => 'المرجع',
'status'            => 'الحالة',
'cashbox'           => 'الخزينة',
'search'            => 'بحث',
'date_range'        => 'الفترة',
'amount_range'      => 'نطاق المبلغ',
'active_only'       => 'النشطة فقط',
'with_trashed'      => 'مع المحذوف',
'only_trashed'      => 'المحذوف فقط',
'per_page'          => 'لكل صفحة',
'choose'            => 'اختر...',
// helps
'mov_help_cashbox'  => 'اختر الخزينة التي ستتم عليها الحركة.',
'mov_help_date'     => 'اختر تاريخ الحركة.',
'mov_help_direction'=> 'حدد إن كانت قبضًا أم صرفًا.',
'mov_help_amount'   => 'أدخل المبلغ بشكل صحيح.',
'mov_help_method'   => 'اختر وسيلة/طريقة الدفع.',
'mov_help_docno'    => 'يمكن تركه فارغًا/إدخاله لاحقًا.',
'mov_help_reference'=> 'مرجع اختياري مرتبط بوثيقة خارجية.',
'mov_help_status'   => 'الحالة الحالية للحركة.',
'void'              => 'ملغاة',
'direction'         => 'الاتجاه',

'handover_title_index' => 'استلام/تسليم الخزينة',
'handover_title_manage'=> 'إدارة استلام/تسليم الخزينة',
'amount_expected'      => 'المبلغ المتوقّع',
'amount_counted'       => 'المبلغ المحصّل',
'difference'           => 'الفرق',
'submitted'            => 'مُرسلة',
'received'             => 'مستلمة',
'rejected'             => 'مرفوضة',
'handover_help_cashbox'=> 'اختر الخزينة محل التسليم.',
'handover_help_date'   => 'تاريخ ووقت التسليم/الاستلام.',
'handover_help_docno'  => 'رقم المستند اختياري.',
'handover_help_expected'=> 'المبلغ المتوقع حسب العمليات.',
'handover_help_counted'=> 'المبلغ الفعلي المُسلّم.',
'handover_help_status' => 'الحالة الحالية للتسليم.',
'handover_help_delivered_by'=> 'المستخدم الذي قام بالتسليم.',
'handover_help_received_by' => 'المستخدم الذي استلم.',

'delivered_by' => 'المستخدم الذي قام بالتسليم.',
'received_by'  => 'المستخدم الذي استلم.',
  'handover_title_manage' => 'إدارة استلام/تسليم الخزينة',
    'editing'   => 'تعديل',
    'creating'  => 'إنشاء',

    'from_cashbox' => 'من خزنة',
    'to_cashbox'   => 'إلى خزنة',
    'choose'       => 'اختر...',
    'help_from_cashbox' => 'اختر الخزينة المرسِلة.',
    'help_to_cashbox'   => 'اختر الخزينة المستلِمة.',

    'swap' => 'تبديل',

    'handover_date'       => 'تاريخ التسليم',
    'help_handover_date'  => 'تاريخ فقط (الافتراضي: اليوم).',

    // الرصيد
    'cashbox_balance'     => 'رصيد الخزينة الحالي (حسب خزنة "من")',
    'handover_help_balance' => 'الرقم محسوب من الإيصالات غير الملغاة والتحويلات الداخلة/الخارجة.',

    'amount_counted'      => 'المبلغ المحصّل',
    'handover_help_counted' => 'المبلغ الفعلي المُسلّم.',

    'difference' => 'الفرق',

    'status'    => 'الحالة',
    'draft'     => 'مسودة',
    'submitted' => 'مُرسلة',
    'received'  => 'مستلمة',
    'rejected'  => 'مرفوضة',
    'handover_help_status' => 'اختر الحالة الحالية.',

    'delivered_by' => 'قام بالتسليم',
    'handover_help_delivered_by' => 'المستخدم الذي قام بالتسليم.',
    'received_by'  => 'المُستلم',
    'handover_help_received_by'  => 'المستخدم الذي استلم.',

    'doc_no' => 'رقم المستند',
    'handover_help_docno' => 'اختياري؛ يمكن توليده لاحقًا.',

    'notes_ar' => 'ملاحظات (عربي)',
    'notes_en' => 'Notes (EN)',
    'finset_ph_notes_ar' => 'اكتب ملاحظات بالعربية...',
    'finset_ph_notes_en' => 'Write notes in English...',

    'btn_back' => 'رجوع',
    'btn_save' => 'حفظ',

    'msg_saved' => 'تم الحفظ بنجاح.',
'receipts_title_index' => 'الإيصالات',
'receipts_title_manage'=> 'إدارة إيصال',
'total_cash'           => 'إجمالي النقدي',
'total_return'         => 'إجمالي المرتجع',
'total_net'            => 'إجمالي الصافي',
'active'               => 'نشط',
'canceled'             => 'ملغي',
'return_amount'        => 'المرتجع',
'btn_canceled_report'  => 'تقرير الملغاة',
'cancel_title'         => 'إلغاء إيصال',
'cancel_reason'        => 'سبب الإلغاء',
'cancel_reason_ph'     => 'اكتب سبب الإلغاء...',
'cancel_confirm'       => 'تأكيد الإلغاء',
'receipt_canceled'     => 'تم إلغاء الإيصال.',
// helps
'rec_help_cashbox'     => 'اختر الخزينة الخاصة بهذا الإيصال.',
'rec_help_date'        => 'تاريخ ووقت الإيصال.',
'rec_help_docno'       => 'رقم الإيصال اختياري.',
'rec_help_method'      => 'طريقة سداد الإيصال.',
'rec_help_amount'      => 'المبلغ الإجمالي للإيصال.',
'rec_help_return'      => 'قيمة المرتجع (إن وجدت).',
'rec_help_reference'   => 'مرجع خارجي (اختياري).',
'rec_help_status'      => 'حالة الإيصال.',

 // Alerts / Errors
    'input_errors' => 'يوجد أخطاء في الإدخال',

    // Header
    'sales_management'     => 'إدارة المبيعات',
    'modern_fast_interface'=> 'واجهة حديثة سريعة مع بحث ذكي وفاتورة فورية',
    'today'                => 'اليوم',
    'sales_list'           => 'قائمة المبيعات',
    'preview_order'        => 'عرض الطلب',
    'print'                => 'طباعة',

    // Toolbar / Search
    'barcode_search_ph'    => 'البحث بالباركود (Enter للإضافة للسلة)',
    'hint_barcode_enter'   => 'اكتب الباركود ثم Enter ليضاف المنتج مباشرة للسلة',
    'product_search_ph'    => 'بحث اسم المنتج / SKU',
    'search_customer_ph'   => 'بحث بالعميل',
    'search_warehouse_ph'  => 'بحث بالمخزن',
    'search_category_ph'   => 'بحث بالقسم',
    'all'                  => 'الكل',
    'add_to_cart'          => 'إضافة للسلة',
    'available'            => 'متاح',
    'no_products'          => 'لا توجد منتجات',

    // Cart side
    'warehouse'            => 'المخزن',
    'customer_optional'    => 'العميل (اختياري)',
    'note'                 => 'ملاحظة',
    'cart_empty'           => 'السلة فارغة - اختر منتجًا من القائمة',

    // Table / Totals
    'subtotal'             => 'الإجمالي الفرعي',
    'discount'             => 'الخصم',
    'tax'                  => 'الضريبة',
    'grand_total'          => 'الإجمالي النهائي',
    'save_finish'          => 'حفظ / إنهاء',
    'update_btn'           => 'تحديث',
    'clear_cart'           => 'إفراغ السلة',
    'delete'               => 'حذف',

    // Modal / Invoice
    'order_preview'        => 'معاينة الطلب',
    'invoice_sale'         => 'فاتورة بيع',
    'date'                 => 'التاريخ',
    'customer'             => 'العميل',
    'product'              => 'المنتج',
    'unit'                 => 'الوحدة',
    'qty'                  => 'الكمية',
    'unit_price'           => 'سعر الوحدة',
    'total'                => 'الإجمالي',
    'close'                => 'إغلاق',
    'unsaved'              => '(غير محفوظ)',
     // ==== عناوين الصفحات ====
    'title_pos_index'   => 'فواتير البيع',
    'title_pos_show'    => 'عرض فاتورة البيع',

    // ==== أزرار عامة ====
    'btn_new_sale'      => 'فاتورة جديدة',
    'btn_show'          => 'عرض',
    'btn_edit'          => 'تعديل',
    'btn_delete'        => 'حذف',
    'back_to_list'      => 'عودة للقائمة',
    'clear_filters'     => 'مسح الفلاتر',
    'print'             => 'طباعة',

    // ==== فلاتر وبحث ====
    'search'            => 'بحث',
    'ph_search_sale'    => 'بحث برقم الفاتورة/الحالة/الملاحظات',
    'hint_search_sale'  => 'اكتب جزءًا من رقم الفاتورة أو الحالة أو الملاحظات',
    'date_from'         => 'التاريخ من',
    'date_to'           => 'التاريخ إلى',
    'status'            => 'الحالة',
    'per_page'          => 'لكل صفحة',
    'hint_click_to_sort'=> 'اضغط على عنوان العمود للفرز تصاعدي/تنازلي',
    'all'               => 'الكل',

    // ==== أعمدة الجدول ====
    'sale_no'           => 'رقم الفاتورة',     // يُعرض مع pos_no
    'sale_date'         => 'تاريخ الفاتورة',   // يُعرض مع pos_date
    'delivery_date'     => 'تاريخ التسليم',
    'customer'          => 'العميل',
    'warehouse'         => 'المخزن',
    'subtotal'          => 'الإجمالي الفرعي',
    'grand_total'       => 'الإجمالي النهائي',
    'actions'           => 'الإجراءات',

    // ==== الحالات ====
    'status_draft'      => 'مسودة',
    'status_approved'   => 'معتمدة',
    'status_posted'     => 'مرحّلة',
    'status_cancelled'  => 'ملغاة',

    // ==== عرض الفاتورة (Show) ====
    'invoice_info'      => 'بيانات الفاتورة',
    'parties_info'      => 'بيانات الأطراف',
    'created_by'        => 'أنشأ بواسطة',
    'product'           => 'المنتج',
    'unit'              => 'الوحدة',
    'qty'               => 'الكمية',
    'unit_price'        => 'سعر الوحدة',
    'total'             => 'الإجمالي',
    'no_items'          => 'لا توجد بنود',
    'print_hint'        => 'يمكنك طباعة الفاتورة من الزر بالأعلى.',

    // ==== رسائل عامة ====
    'deleted_ok'        => 'تم حذف الفاتورة بنجاح',
    'confirm_delete'    => 'هل أنت متأكد من حذف هذه الفاتورة؟',
    'no_data'           => 'لا توجد بيانات متاحة',

    // ==== معلومات التصفح ====
    'pagination_info'   => 'عرض :from–:to من أصل :total',

    // ===== Toolbar / Filters =====
    'search'                    => 'بحث',
    'ph_search_code_or_name'    => 'ابحث بالكود أو الاسم…',
    'hint_search_currencies'    => 'اكتب جزءًا من الكود أو الاسم للبحث.',
    'status'                    => 'الحالة',
    'all'                       => 'الكل',
    'active'                    => 'نشط',
    'inactive'                  => 'غير نشط',
    'per_page'                  => 'لكل صفحة',
    'reload'                    => 'تحديث',

    // ===== Form Titles =====
    'edit_currency'             => 'تعديل عملة',
    'add_currency'              => 'إضافة عملة',
    'editing_id'                => 'رقم التحرير',

    // ===== Fields =====
    'code'                      => 'الكود',
    'h_code'                    => 'ادخل كود العملة وفق ISO-4217 مثل: EGP / USD.',
    'name_ar'                   => 'الاسم (عربي)',
    'ph_ar'                     => 'مثال: الجنيه المصري',
    'h_name_ar'                 => 'الاسم الظاهر للمستخدمين باللغة العربية.',
    'name_en'                   => 'الاسم (إنجليزي)',
    'ph_en'                     => 'مثال: Egyptian Pound',
    'h_name_en'                 => 'الاسم الظاهر للمستخدمين باللغة الإنجليزية.',
    'symbol'                    => 'الرمز المختصر',
    'h_symbol'                  => 'اكتب الرمز المختصر مثل: ج.م / $ / SAR / E£.',
    'minor_unit'                => 'عدد المنازل العشرية',
    'h_minor_unit'              => 'عدد الأرقام بعد الفاصلة (0–6).',
    'exchange_rate'             => 'سعر الصرف',
    'h_exchange_rate'           => 'سعر الصرف مقابل العملة الافتراضية في النظام.',
    'h_status'                  => 'حدد إذا كانت العملة فعّالة للاستخدام.',
    'is_default'                => 'عملة افتراضية',
    'h_is_default'              => 'العملة الافتراضية تُستخدم كأساس للتحويلات.',
    'notes'                     => 'ملاحظات',
    'notes_ar'                  => 'ملاحظات (عربي)',
    'notes_en'                  => 'ملاحظات (إنجليزي)',
    'h_notes'                   => 'اكتب أي ملاحظات إدارية متعلقة بالعملة.',

    // ===== Buttons / Actions =====
    'save'                      => 'حفظ',
    'reset'                     => 'إعادة ضبط',
    'actions'                   => 'إجراءات',
    'change_status'             => 'تغيير الحالة',
    'delete'                    => 'حذف',
    'edit'                      => 'تعديل',

    // ===== Table / Listing =====
    'currencies_list'           => 'قائمة العملات',
    'name'                      => 'الاسم',
    'default'                   => 'افتراضية',
    'no_data'                   => 'لا توجد بيانات.',
    'showing'                   => 'عرض',
    'of'                        => 'من',
    'yes'                       => 'نعم',
    'no'                        => 'لا',

    // ===== Alerts / Validation =====
    'input_errors'              => 'هناك أخطاء في الإدخال، برجاء المراجعة.',
    'hint_filter_status'        => 'فلترة القائمة بحسب الحالة.',

    // ===== SweetAlert2 (delete confirm) =====
    'swal_title'                => 'تحذير',
    'swal_text'                 => '⚠️ هل أنت متأكد أنك تريد حذف هذا الإجراء؟ لا يمكن التراجع عنه!',
    'swal_confirm'              => 'نعم، احذفها',
    'swal_cancel'               => 'إلغاء',
    'swal_deleted_title'        => 'تم الحذف!',
    'swal_deleted_text'         => '✅ تم الحذف بنجاح.',
    'currencies_manage'          => 'إدارة العملات',
    'saved_successfully' => ' ✅ تم الحفظ بنجاح' ,

    
    // --- Payments / Currency / Treasury ---
    'payment_method'   => 'طريقة السداد',
    'pm_cash'          => 'نقدي',
    'pm_card'          => 'بطاقة (فيزا/ماستر)',
    'pm_instapay'      => 'إنستاباي',
    'pm_wallet'        => 'محفظة إلكترونية',

    'currency'         => 'العملة',
    'treasury'         => 'الخزينة',
    'branch'           => 'الفرع',

    // --- Coupon ---
    'coupon_code'      => 'كود الكوبون',
    'apply'            => 'تطبيق',
    'coupon_applied'   => 'تم تطبيق الكوبون بنجاح',
    'coupon_error'     => 'تعذّر تطبيق الكوبون',
    'coupon_discount'  => 'خصم الكوبون',

    // --- Payment amounts ---
    'amount_paid'      => 'المبلغ المدفوع',
    'change_due'       => 'المتبقي/الفرق',
];
