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
];
