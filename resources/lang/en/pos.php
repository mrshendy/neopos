<?php

return [

    // =========================
    // Page Titles (Customers)
    // =========================
    'title_customers_index'  => 'Customers',
    'title_customers_create' => 'Create Customer',
    'title_customers_edit'   => 'Edit Customer',
    'title_customers_show'   => 'Customer Details',

    // Cards/Sections in Show
    'card_profile'       => 'Profile Information',
    'card_summary'       => 'Transactions & Balances',
    'card_transactions'  => 'Recent Transactions',
    'desc_profile'       => 'General customer details and contact/location info.',
    'desc_summary'       => 'Quick summary of balance, credit limit, and recent transactions.',

    // =========================
    // Table Headers (Index/Show)
    // =========================
    'th_code'           => 'Code',
    'th_name'           => 'Name',
    'th_type'           => 'Type',
    'th_channel'        => 'Channel',
    'th_phone'          => 'Phone',
    'th_city'           => 'City',
    'th_area'           => 'Area',
    'th_price_category' => 'Price Category',
    'th_credit_limit'   => 'Credit Limit',
    'th_balance'        => 'Balance',
    'th_available'      => 'Available',
    'th_open_invoices'  => 'Open Invoices',
    'th_status'         => 'Status',
    'th_actions'        => 'Actions',
    'th_reference'      => 'Reference',
    'th_date'           => 'Date',
    'th_value'          => 'Amount',

    // =========================
    // Form Fields (Create/Edit)
    // =========================
    'f_code'              => 'Customer Code',
    'f_legal_name_ar'     => 'Legal Name (Arabic)',
    'f_legal_name_en'     => 'Legal Name (English)',
    'f_trade_name_ar'     => 'Trade Name (Arabic)',
    'f_trade_name_en'     => 'Trade Name (English)',
    'f_type'              => 'Customer Type',
    'f_channel'           => 'Channel',
    'f_country'           => 'Country',
    'f_governorate'       => 'Governorate',
    'f_city_select'       => 'City (List)',
    'f_area'              => 'Area',
    'f_city_text'         => 'City (Text)',
    'f_phone'             => 'Phone',
    'f_tax'               => 'Tax Number',
    'f_price_category'    => 'Price Category',
    'f_credit_limit'      => 'Credit Limit',
    'f_account_status'    => 'Account Status',
    'f_sales_rep'         => 'Sales Rep',

    // =========================
    // Placeholders & Helpers
    // =========================
    'ph_search_name'   => 'Search by name',
    'ph_code'          => 'Enter unique code',
    'ph_phone'         => 'Enter phone number',
    'ph_tax'           => 'Enter tax number',
    'ph_country'       => 'Select country',
    'ph_governorate'   => 'Select governorate',
    'ph_city_select'   => 'Select city',
    'ph_area'          => 'Select area',
    'ph_price_category'=> 'Select price category',
    'ph_city_text'     => 'Type city manually (optional)',

    'h_code'           => 'Used for quick identification.',
    'h_legal_ar'       => 'Shown on Arabic reports.',
    'h_legal_en'       => 'Shown on English reports.',
    'h_type'           => 'Choose customer type.',
    'h_channel'        => 'Primary sales channel.',
    'h_country'        => 'Used for country-wise reports.',
    'h_governorate'    => 'Filters dependent cities.',
    'h_city_select'    => 'Depends on governorate.',
    'h_area'           => 'Depends on city.',
    'h_city_text'      => 'If you prefer free text city.',
    'h_phone'          => 'For contact and notifications.',
    'h_tax'            => 'Required for tax invoices.',
    'h_price_category' => 'Affects product pricing.',
    'h_credit_limit'   => 'Maximum allowed A/R.',
    'h_account_status' => 'Activate/Deactivate/Suspend.',
    'preview'          => 'Preview',

    // =========================
    // Buttons
    // =========================
    'btn_new_customer' => '+ New Customer',
    'btn_save'         => 'Save',
    'btn_update'       => 'Update',
    'btn_cancel'       => 'Cancel',
    'btn_edit'         => 'Edit',
    'btn_view'         => 'View',
    'btn_delete'       => 'Delete',
    'btn_toggle'       => 'Toggle Status',
    'btn_import'       => 'Import',
    'btn_export'       => 'Export',
    'btn_merge'        => 'Merge',
    'btn_columns'      => 'Columns',
    'btn_invoice'      => 'New Invoice',
    'btn_statement'    => 'Statement',
    'btn_activate'     => 'Activate',
    'btn_deactivate'   => 'Deactivate',
    'btn_back'         => 'Back',

    // =========================
    // Status / Lists
    // =========================
    'status_active'    => 'Active',
    'status_inactive'  => 'Inactive',
    'status_suspended' => 'Suspended',
    'any_status'       => 'Any status',
    'none'             => 'None',
    'no_data'          => 'No records found',
    'no_transactions'  => 'No transactions found.',

    // =========================
    // Messages
    // =========================
    'msg_created'               => '✅ Customer created successfully.',
    'msg_updated'               => '✅ Customer updated successfully.',
    'msg_deleted'               => '🗑️ Customer deleted successfully.',
    'msg_status_updated'        => '🔁 Account status updated.',
    'msg_cannot_delete_has_tx'  => '⚠️ Customer with transactions cannot be deleted — use deactivate.',
    'msg_error'                 => '❌ An error occurred. Please try again.',

    // =========================
    // Validation
    // =========================
    'val_code_required'         => 'Customer code is required.',
    'val_code_unique'           => 'Code already in use.',
    'val_legal_ar_required'     => 'Arabic legal name is required.',
    'val_legal_en_required'     => 'English legal name is required.',
    'val_credit_limit_min'      => 'Credit limit must be at least 0.',
    'val_area_exists'           => 'Selected area is invalid.',
    'val_country_exists'        => 'Selected country is invalid.',
    'val_governorate_exists'    => 'Selected governorate is invalid.',
    'val_city_exists'           => 'Selected city is invalid.',
    'val_price_category_exists' => 'Selected price category is invalid.',

    // =========================
    // SweetAlert (Delete)
    // =========================
    'alert_delete_title'   => 'Warning',
    'alert_delete_text'    => '⚠️ Are you sure you want to delete? This action cannot be undone!',
    'alert_confirm'        => 'Yes, delete',
    'alert_cancel'         => 'Cancel',
    'alert_deleted_title'  => 'Deleted!',
    'alert_deleted_text'   => '✅ Deleted successfully.',

    // =========================
    // Filters & Saved Views
    // =========================
    'filters'           => 'Filters',
    'apply_filters'     => 'Apply Filters',
    'reset_filters'     => 'Reset',
    'save_filter_view'  => 'Save Filter View',
    'saved_views'       => 'Saved Views',
    'view_name'         => 'View Name',

    // =========================
    // Approvals (Optional)
    // =========================
    'approvals'             => 'Approvals',
    'approve'               => 'Approve',
    'reject'                => 'Reject',
    'hold'                  => 'Hold',
    'reason_required'       => 'Decision reason (required)',
    'approval_sent'         => 'Approval request sent.',
    'approval_updated'      => 'Approval updated.',

     // Titles & Buttons
    'title_customers_create'   => 'Create Customer',
    'btn_save'                 => 'Save',
    'btn_cancel'               => 'Cancel',
    'btn_back'                 => 'Back',

    // Sections
    'section_basic'            => 'Basic Information',
    'section_location'         => 'Location',
    'section_contact_finance'  => 'Contact & Finance',

    // Fields / Helpers / Placeholders
    'f_code'                   => 'Code',
    'ph_code'                  => 'Auto or custom code',
    'h_code'                   => 'Unique reference for the customer (auto allowed).',

    'f_legal_name_ar'          => 'Legal Name (Arabic)',
    'ph_legal_name_ar'         => 'Enter Arabic legal name',
    'h_legal_ar'               => 'Official Arabic name that appears on invoices.',

    'f_legal_name_en'          => 'Legal Name (English)',
    'ph_legal_name_en'         => 'Enter English legal name',
    'h_legal_en'               => 'Official English name that appears on invoices.',

    'f_type'                   => 'Customer Type',
    'h_type'                   => 'Select the relation type.',
    'opt_individual'           => 'Individual',
    'opt_company'              => 'Company',

    'f_channel'                => 'Sales Channel',
    'h_channel'                => 'Where the customer is served.',
    'opt_retail'               => 'Retail',
    'opt_wholesale'            => 'Wholesale',
    'opt_online'               => 'Online',
    'opt_pharmacy'             => 'Pharmacy',

    'f_country'                => 'Country',
    'ph_country'               => 'Choose country',
    'h_country'                => 'Used to determine address and taxation rules.',

    'f_governorate'            => 'Governorate',
    'ph_governorate'           => 'Choose governorate',
    'h_governorate'            => 'Administrative region within the country.',

    'f_city_select'            => 'City (List)',
    'ph_city_select'           => 'Choose city',
    'h_city_select'            => 'Pick an existing city from the list.',

    'f_area'                   => 'Area',
    'ph_area'                  => 'Choose area',
    'h_area'                   => 'Neighborhood / district.',

    'f_city_text'              => 'City (Text)',
    'ph_city_text'             => 'Type city name if not in list',
    'h_city_text'              => 'Used when the city does not exist in the predefined list.',

    'f_phone'                  => 'Phone',
    'ph_phone'                 => 'Enter phone number',
    'h_phone'                  => 'Primary contact number.',

    'f_tax'                    => 'Tax Number',
    'ph_tax'                   => 'Enter VAT/Tax ID',
    'h_tax'                    => 'Registered taxpayer number if applicable.',

    'f_price_category'         => 'Price Category',
    'ph_price_category'        => 'Choose price list',
    'h_price_category'         => 'Defines default prices / discounts.',

    'f_credit_limit'           => 'Credit Limit',
    'h_credit_limit'           => 'Maximum allowed outstanding balance.',

    'f_account_status'         => 'Account Status',
    'status_active'            => 'Active',
    'status_inactive'          => 'Inactive',
    'status_suspended'         => 'Suspended',
    'h_account_status'         => 'Controls whether the customer can transact.',

    // Misc
    'loading'                  => 'Saving…',

        // Common Buttons/Titles
    'btn_save'                 => 'Save',
    'btn_cancel'               => 'Cancel',
    'btn_close'                => 'Close',
    'btn_back'                 => 'Back',
    'btn_edit'                 => 'Edit',
    'btn_update'               => 'Update',
    'btn_print'                => 'Print',
    'btn_new_invoice'          => 'New Invoice',

    // Sections / Fields
    'section_basic'            => 'Basic Information',
    'section_location'         => 'Location',
    'section_contact_finance'  => 'Contact & Finance',

    'f_code'                   => 'Code',
    'ph_code'                  => 'Auto or custom code',
    'h_code'                   => 'Unique reference for the customer (auto allowed).',

    'f_legal_name_ar'          => 'Legal Name (Arabic)',
    'ph_legal_name_ar'         => 'Enter Arabic legal name',
    'h_legal_ar'               => 'Official Arabic name that appears on invoices.',

    'f_legal_name_en'          => 'Legal Name (English)',
    'ph_legal_name_en'         => 'Enter English legal name',
    'h_legal_en'               => 'Official English name that appears on invoices.',

    'f_type'                   => 'Customer Type',
    'opt_individual'           => 'Individual',
    'opt_company'              => 'Company',
    'h_type'                   => 'Select the relation type.',

    'f_channel'                => 'Sales Channel',
    'opt_retail'               => 'Retail',
    'opt_wholesale'            => 'Wholesale',
    'opt_online'               => 'Online',
    'opt_pharmacy'             => 'Pharmacy',
    'h_channel'                => 'Where the customer is served.',

    'f_country'                => 'Country',
    'ph_country'               => 'Choose country',
    'h_country'                => 'Used to determine address and taxation rules.',

    'f_governorate'            => 'Governorate',
    'ph_governorate'           => 'Choose governorate',
    'h_governorate'            => 'Administrative region within the country.',

    'f_city_select'            => 'City (List)',
    'ph_city_select'           => 'Choose city',
    'h_city_select'            => 'Pick an existing city from the list.',

    'f_area'                   => 'Area',
    'ph_area'                  => 'Choose area',
    'h_area'                   => 'Neighborhood / district.',

    'f_city_text'              => 'City (Text)',
    'ph_city_text'             => 'Type city name if not in list',
    'h_city_text'              => 'Used when the city does not exist in the predefined list.',

    'f_phone'                  => 'Phone',
    'ph_phone'                 => 'Enter phone number',
    'h_phone'                  => 'Primary contact number.',

    'f_tax'                    => 'Tax Number',
    'ph_tax'                   => 'Enter VAT/Tax ID',
    'h_tax'                    => 'Registered taxpayer number if applicable.',

    'f_price_category'         => 'Price Category',
    'ph_price_category'        => 'Choose price list',
    'h_price_category'         => 'Defines default prices / discounts.',

    'f_credit_limit'           => 'Credit Limit',
    'h_credit_limit'           => 'Maximum allowed outstanding balance.',

    'f_account_status'         => 'Account Status',
    'status_active'            => 'Active',
    'status_inactive'          => 'Inactive',
    'status_suspended'         => 'Suspended',
    'h_account_status'         => 'Controls whether the customer can transact.',

    // Show Page
    'title_customers_show'     => 'Customer Profile',

    // Profile & Cards
    'card_profile'             => 'Profile',
    'desc_profile'             => 'General information and address.',
    'card_summary'             => 'Financial Summary',
    'desc_summary'             => 'Credit overview and latest activity.',
    'card_activity'            => 'Activity',
    'card_contacts'            => 'Contacts',
    'desc_contacts'            => 'People responsible for communication.',
    'card_notes'               => 'Internal Notes',
    'desc_notes'               => 'Private notes visible to staff.',
    'no_notes'                 => 'No notes recorded.',

    // Table / labels
    'th_code'                  => 'Code',
    'th_name'                  => 'Name',
    'th_phone'                 => 'Phone',
    'th_status'                => 'Status',
    'th_country'               => 'Country',
    'th_governorate'           => 'Governorate',
    'th_city'                  => 'City',
    'th_area'                  => 'Area',
    'th_price_category'        => 'Price Category',
    'th_balance'               => 'Balance',
    'th_credit_limit'          => 'Credit Limit',
    'th_available'             => 'Available',
    'th_open_invoices'         => 'Open Invoices',
    'th_type'                  => 'Type',
    'th_reference'             => 'Reference',
    'th_date'                  => 'Date',
    'th_value'                 => 'Amount',
    'th_person'                => 'Person',
    'th_role'                  => 'Role',
    'th_email'                 => 'Email',

    // Tabs & Empty states
    'tab_invoices'             => 'Invoices',
    'tab_receipts'             => 'Receipts',
    'tab_returns'              => 'Returns',
    'tab_latest'               => 'Latest',
    'no_transactions'          => 'No transactions found.',
    'no_invoices'              => 'No invoices found.',
    'no_receipts'              => 'No receipts found.',
    'no_returns'               => 'No returns found.',
    'no_contacts'              => 'No contacts defined.',

    // Misc
    'credit_used'              => 'Credit used',
    'tt_copy'                  => 'Copy to clipboard',

       // Pages
    'supplier_title'   => 'Suppliers',
    'supplier_list'    => 'Suppliers List',
    'supplier_create'  => 'Create Supplier',
    'supplier_edit'    => 'Edit Supplier',
    'supplier_show'    => 'Supplier Details',

    // Fields
    'supplier_code'    => 'Supplier Code',
    'supplier_name'    => 'Supplier Name',
    'name_ar'          => 'Arabic Name',
    'name_en'          => 'English Name',
    'commercial_register' => 'Commercial Register',
    'tax_number'       => 'Tax Number',
    'category'         => 'Category',
    'payment_term'     => 'Payment Term',

    // Geography
    'country'          => 'Country',
    'governorate'      => 'Governorate',
    'city'             => 'City',
    'area'             => 'Area',

    // Status & actions
    'status'           => 'Status',
    'status_active'    => 'Active',
    'status_inactive'  => 'Inactive',
    'actions'          => 'Actions',

    // Buttons
    'btn_save'         => 'Save',
    'btn_update'       => 'Update',
    'btn_back'         => 'Back',
    'btn_create'       => 'Add Supplier',
    'btn_show'         => 'Show',
    'btn_edit'         => 'Edit',
    'btn_delete'       => 'Delete',
    'btn_toggle'       => 'Toggle Status',

    // Common
    'no_data'          => 'No data found',
    'search_supplier'  => 'Search by name/code',

    // Success/Warnings
    'created_success'  => '✅ Supplier created successfully.',
    'updated_success'  => '✅ Supplier updated successfully.',
    'deleted_success'  => '✅ Record deleted successfully.',
    'status_changed'   => 'Status updated successfully.',
    'warn_inactive_supplier' => 'Warning: Supplier is inactive — purchasing is not allowed until activated.',

    // Placeholders + Descriptions
    'ph_code'          => 'Enter a unique supplier code',
    'desc_code'        => 'Used to link with other modules.',
    'ph_name_ar'       => 'Enter Arabic name',
    'desc_name_ar'     => 'Official Arabic name.',
    'ph_name_en'       => 'Enter English name',
    'desc_name_en'     => 'Official English name.',
    'ph_cr'            => 'Commercial register (optional)',
    'desc_cr'          => 'For legal verification.',
    'ph_tax'           => 'Tax number (optional)',
    'desc_tax'         => 'Required for fiscal invoices.',
    'ph_category'      => 'Select category',
    'desc_category'    => 'For reports and filters.',
    'ph_payment_term'  => 'Select payment term',
    'desc_payment_term'=> 'Due days & partial payments.',
    'desc_status'      => 'Temporarily disable without deleting.',

    'ph_country'       => 'Select country',
    'desc_country'     => 'Supplier\'s country.',
    'ph_governorate'   => 'Select governorate',
    'desc_governorate' => 'Within the selected country.',
    'ph_city'          => 'Select city',
    'desc_city'        => 'Within the governorate.',
    'ph_area'          => 'Select area',
    'desc_area'        => 'Within the city.',

    // Filters
    'filter_category'    => 'Filter by category',
    'filter_governorate' => 'Filter by governorate',
    'filter_city'        => 'Filter by city',
    'filter_status'      => 'Filter by status',

    // SweetAlert2
    'alert_delete_title'    => 'Warning',
    'alert_delete_text'     => '⚠️ Are you sure you want to delete? This cannot be undone!',
    'alert_delete_confirm'  => 'Yes, delete',
    'alert_delete_cancel'   => 'Cancel',
    'alert_deleted'         => 'Deleted!',
    'alert_deleted_text'    => '✅ Deleted successfully.',

      'products_index_title' => 'Products Management',
    'product_create_title' => 'Create Product',
    'price_lists_title'    => 'Price Lists',

    'search'=>'Search','all'=>'All','choose'=>'Choose','actions'=>'Actions','no_data'=>'No data.',
    'status'=>'Status','status_active'=>'Active','status_inactive'=>'Inactive',

    'sku'=>'SKU','barcode'=>'Barcode','name'=>'Name',
    'name_ar'=>'Name (AR)','name_en'=>'Name (EN)',
    'description_ar'=>'Description (AR)','description_en'=>'Description (EN)',
    'unit'=>'Unit','category'=>'Category','tax_rate'=>'Tax Rate %','opening_stock'=>'Opening Stock',

    'ph_search_sku_barcode_name'=>'Search by SKU/Barcode/Name…',
    'ph_sku'=>'Enter internal SKU',
    'ph_barcode'=>'Enter barcode (optional)',
    'ph_name_ar'=>'Enter Arabic name',
    'ph_name_en'=>'Enter English name',
    'ph_desc_ar'=>'Arabic short description',
    'ph_desc_en'=>'English short description',
    'hint_search_products'=>'Use keywords for quick access.',
    'hint_sku'=>'Unique SKU to avoid duplicates.',
    'hint_barcode'=>'Barcode must be unique.',
    'hint_name_ar'=>'Shown in Arabic UIs.',
    'hint_name_en'=>'Shown in English UIs.',
    'hint_unit'=>'Select a suitable unit.',
    'hint_category'=>'Choosing a category improves reports.',
    'hint_tax_rate'=>'Range 0 – 100.',
    'hint_opening_stock'=>'Beginning inventory.',
    'hint_status'=>'You can toggle it later from actions.',

    'filter_category'=>'Filter by Category',
    'filter_unit'=>'Filter by Unit',
    'filter_status'=>'Filter by Status',

    'btn_new_product'=>'New Product',
    'btn_save'=>'Save',
    'btn_back'=>'Back',

    'alert_title'=>'Warning',
    'alert_text'=>'Are you sure to delete? This cannot be undone!',
    'alert_confirm'=>'Yes, delete',
    'alert_cancel'=>'Cancel',
    'deleted'=>'Deleted',
    'msg_deleted_ok'=>'Deleted successfully.',
    'msg_saved_ok'=>'Saved successfully.',
    'msg_status_changed'=>'Status changed.',

    'err_price_conflict'=>'Price conflict — please adjust period or priority.',
    'err_price_conflict_banner'=>'Conflicting price for the same product within the same list and period.',
    'err_price_negative'=>'Price cannot be below 0.',
];
