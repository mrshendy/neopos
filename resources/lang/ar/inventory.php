<?php

return [

    // ===== Manage =====
    'manage_title'    => 'إدارة المخزون',
    'manage_subtitle' => 'اختر وحدة من وحدات إدارة المخزون للمتابعة.',
    'open'            => 'فتح',
    'soon'            => 'قريبًا',
    'route_missing'   => 'المسار غير مُعرّف',

    // بطاقات الموديولات
    'module_settings'     => 'إعدادات المخزون',
    'module_counts'       => 'جرد المخزون',
    'module_alerts'       => 'تنبيهات المخزون',
    'module_transactions' => 'حركات المخزون',
    'module_warehouses'   => 'المخازن',
    'module_products'     => 'المنتجات',
    'module_stocks'       => 'أرصدة المخزون',
  
    'module_branches'   => 'الفروع',
    'manage_title'      => 'إدارة المخزون',
    'manage_subtitle'   => 'اختَر أحد برامج إدارة المخزون للمتابعة',
    'open'              => 'فتح',
    'soon'              => 'قريبًا',
    'route_missing'     => 'المسار غير مفعّل بعد',

    // ===== Units Index =====
    'units_management_title' => 'إدارة الوحدات',
    'units_management_sub'   => 'الوحدات الكبرى مع الوحدات الصغرى المرتبطة بها.',
    'add_unit'               => 'إضافة وحدة',
    'search'                 => 'بحث',
    'search_placeholder'     => 'كود أو اسم...',
    'search_hint'            => 'ابحث بالكود أو الاسم (ع/En).',
    'clear_search'           => 'مسح البحث',

    'major_unit'     => 'وحدة كبرى',
    'minor_unit'     => 'وحدة صغرى',
    'active'         => 'نشط',
    'inactive'       => 'موقوف',
    'default'        => 'افتراضي',
    'no'             => 'لا',
    'code'           => 'الكود',
    'name_ar'        => 'الاسم (ع)',
    'name_en'        => 'الاسم (En)',
    'abbreviation'   => 'الاختصار',
    'ratio'          => 'النسبة',
    'status'         => 'الحالة',
    'actions'        => 'إجراءات',
    'edit'           => 'تعديل',
    'delete'         => 'حذف',
    'no_minors_for_major' => 'لا توجد وحدات صغرى مرتبطة بهذه الكبرى.',
    'no_units_yet'   => 'لا توجد وحدات حتى الآن.',

    // تأكيد الحذف (SweetAlert)
    'confirm_delete_title' => 'تأكيد الحذف',
    'confirm_delete_text'  => 'هل أنت متأكد من حذف هذه الوحدة؟ لا يمكن التراجع بعد ذلك.',
    'confirm_delete_yes'   => 'نعم، احذف',
    'confirm_delete_cancel'=> 'إلغاء',

    // ===== Units Form =====
    'units_form_add_title'    => 'إضافة وحدة',
    'units_form_edit_title'   => 'تعديل وحدة',
    'units_form_subtitle'     => 'إدارة وحدات القياس (كبرى/صغرى) مع التحويل الافتراضي والاختصار والحالة.',
    'back'                    => 'رجوع',
    'save'                    => 'حفظ',
    'saving'                  => 'جارِ الحفظ...',
    'cancel'                  => 'إلغاء',

    'field_code'              => 'الكود',
    'field_kind'              => 'النوع',
    'field_status'            => 'الحالة',
    'field_name_ar'           => 'الاسم (ع)',
    'field_name_en'           => 'الاسم (En)',
    'field_abbreviation'      => 'الاختصار',
    'field_parent'            => 'الوحدة الكبرى',
    'field_ratio_to_parent'   => 'النسبة إلى الكبرى',
    'field_is_default_minor'  => 'الوحدة الصغرى الافتراضية لهذه الكبرى',

    'kind_major'              => 'كبرى',
    'kind_minor'              => 'صغرى',

    'hint_code_unique'        => 'كود فريد للوحدة.',
    'hint_abbreviation'       => 'اختياري للعرض في القوائم والفواتير.',
    'hint_ratio'              => '1 كبرى = :ratio صغرى',
    'hint_ratio_inverse'      => '1 من الصغرى = (1 / النسبة) من الكبرى.',

    // بطاقة المساعدة/المعاينة
    'tips_title'              => 'نصائح سريعة',
    'tips_item_major'         => 'الوحدة الكبرى لا تحتاج أبًا أو نسبة.',
    'tips_item_minor'         => 'الوحدة الصغرى يجب ربطها بكبرى مع تحديد النسبة.',
    'tips_item_default'       => 'لا يمكن اختيار أكثر من صغرى افتراضية لكل كبرى — يتم ضبطها تلقائيًا.',
    'preview_title'           => 'معاينة سريعة',

    // شريط الحالة والمعاينة
    'preview_state'           => 'الحالة',
    'preview_kind'            => 'النوع',
    'preview_code'            => 'الكود',
    'preview_name'            => 'الاسم',
    'preview_parent'          => 'الكبرى',
    'preview_ratio'           => 'النسبة',
    'preview_default'         => 'افتراضية',
    'yes'                     => 'نعم',

    // اختصارات
    'shortcut_save'           => 'اختصار: Ctrl + S للحفظ',
       // صفحة إعدادات المخزون
    'settings_title' => 'إعدادات المخزون',
    'settings_sub'   => 'ضبط سياسات السالب وتنبيهات الصلاحية وترقيم الحركات',

    // سياسة السالب
    'negative_stock_policy' => 'سياسة السماح بالسالب',
    'policy_block'          => 'منع السالب',
    'policy_warn'           => 'تحذير فقط',
    'help_negative_stock_policy_short' => 'اختر كيف يتصرف النظام عند محاولة صرف يؤدي لسالب.',

    // تنبيه الصلاحية
    'expiry_alert_days'     => 'أيام تنبيه صلاحية الأصناف',
    'help_expiry_alert_days'=> 'يظهر تنبيه إذا تبقّى على انتهاء الصلاحية عدد أيام أقل أو يساوي هذه القيمة.',

    // نمط الترقيم
    'transaction_pattern'   => 'نمط ترقيم الحركات',
    'help_transaction_pattern' => 'استخدم الوسوم: {YYYY} السنة، {YY} السنتان، {MM} الشهر، {DD} اليوم، {####} تسلسل 4 أرقام.',
    'example'               => 'مثال',

    // أزرار/حالات عامة
    'btn_save_settings' => 'حفظ الإعدادات',
    'saved_success'     => 'تم حفظ الإعدادات بنجاح',
    'enabled'           => 'مفعّل',
    'disabled'          => 'معطّل',
    'none'              => 'بدون',
];
