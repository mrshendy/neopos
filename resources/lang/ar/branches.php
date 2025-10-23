<?php

return [

    // ===== Titles / Headers =====
    'index_title'   => 'إدارة الفروع',
    'create_title'  => 'إنشاء فرع جديد',
    'edit_title'    => 'تعديل فرع',

    // ===== Fields =====
    'field_name'    => 'اسم الفرع',
    'field_address' => 'العنوان',
    'field_status'  => 'الحالة',

    // ===== Placeholders =====
    'ph_name'       => 'مثال: فرع المهندسين',
    'ph_address'    => 'العنوان التفصيلي',
    'ph_search'     => 'ابحث بالاسم أو العنوان',

    // ===== Status labels =====
    'status_active'   => 'فعّال',
    'status_inactive' => 'غير فعّال',

    // ===== Toolbar / Filters =====
    'search'        => 'بحث',
    'hint_search'   => 'اكتب جزءًا من الاسم أو العنوان.',
    'filter_status' => 'الحالة',
    'all'           => 'الكل',
    'per_page'      => 'عدد الصفوف',
    'btn_new_branch'=> 'فرع جديد',

    // ===== Table columns =====
    'col_name'     => 'الاسم',
    'col_address'  => 'العنوان',
    'col_status'   => 'الحالة',
    'col_actions'  => 'إجراءات',
    'no_data'      => 'لا توجد بيانات',

    // ===== Buttons =====
    'btn_save'     => 'حفظ',
    'btn_update'   => 'حفظ التعديل',
    'btn_back'     => 'رجوع',
    'btn_edit'     => 'تعديل',
    'btn_delete'   => 'حذف',

    // ===== Flash Messages (optional) =====
    'msg_created'  => 'تم إنشاء الفرع بنجاح.',
    'msg_updated'  => 'تم تحديث بيانات الفرع بنجاح.',
    'msg_deleted'  => 'تم حذف الفرع بنجاح.',

    // ===== Preview chip =====
    'preview_value' => 'المعاينة',

    // ===== SweetAlert (Delete) =====
    'sa_title'     => 'تحذير',
    'sa_text'      => '⚠️ هل أنت متأكد أنك تريد الحذف؟ هذا الإجراء لا يمكن التراجع عنه!',
    'sa_confirm'   => 'نعم، احذف',
    'sa_cancel'    => 'إلغاء',
    'sa_done'      => 'تم الحذف!',
    'sa_done_text' => '✅ تم الحذف بنجاح.',

    // ===== Validation messages (optional – لو حبيت تستخدمها في rules) =====
    'val_name_required'    => 'اسم الفرع مطلوب',
    'val_name_min'         => 'اسم الفرع قصير جداً',
    'val_name_max'         => 'اسم الفرع كبير جداً',
    'val_address_max'      => 'العنوان طويل جداً',
    'val_status_required'  => 'الحالة مطلوبة',
    'val_status_boolean'   => 'قيمة الحالة غير صحيحة',

];
