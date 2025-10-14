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

];
