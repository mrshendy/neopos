<?php

return [

    // =========================
    // Page Titles (Customers)
    // =========================
    'title_customers_index' => 'Customers',
    'title_customers_create' => 'Create Customer',
    'title_customers_edit' => 'Edit Customer',
    'title_customers_show' => 'Customer Details',

    // Cards/Sections in Show
    'card_profile' => 'Profile Information',
    'card_summary' => 'Transactions & Balances',
    'card_transactions' => 'Recent Transactions',
    'desc_profile' => 'General customer details and contact/location info.',
    'desc_summary' => 'Quick summary of balance, credit limit, and recent transactions.',

    // =========================
    // Table Headers (Index/Show)
    // =========================
    'th_code' => 'Code',
    'th_name' => 'Name',
    'th_type' => 'Type',
    'th_channel' => 'Channel',
    'th_phone' => 'Phone',
    'th_city' => 'City',
    'th_area' => 'Area',
    'th_price_category' => 'Price Category',
    'th_credit_limit' => 'Credit Limit',
    'th_balance' => 'Balance',
    'th_available' => 'Available',
    'th_open_invoices' => 'Open Invoices',
    'th_status' => 'Status',
    'th_actions' => 'Actions',
    'th_reference' => 'Reference',
    'th_date' => 'Date',
    'th_value' => 'Amount',

    // =========================
    // Form Fields (Create/Edit)
    // =========================
    'f_code' => 'Customer Code',
    'f_legal_name_ar' => 'Legal Name (Arabic)',
    'f_legal_name_en' => 'Legal Name (English)',
    'f_trade_name_ar' => 'Trade Name (Arabic)',
    'f_trade_name_en' => 'Trade Name (English)',
    'f_type' => 'Customer Type',
    'f_channel' => 'Channel',
    'f_country' => 'Country',
    'f_governorate' => 'Governorate',
    'f_city_select' => 'City (List)',
    'f_area' => 'Area',
    'f_city_text' => 'City (Text)',
    'f_phone' => 'Phone',
    'f_tax' => 'Tax Number',
    'f_price_category' => 'Price Category',
    'f_credit_limit' => 'Credit Limit',
    'f_account_status' => 'Account Status',
    'f_sales_rep' => 'Sales Rep',

    // =========================
    // Placeholders & Helpers
    // =========================
    'ph_search_name' => 'Search by name',
    'ph_code' => 'Enter unique code',
    'ph_phone' => 'Enter phone number',
    'ph_tax' => 'Enter tax number',
    'ph_country' => 'Select country',
    'ph_governorate' => 'Select governorate',
    'ph_city_select' => 'Select city',
    'ph_area' => 'Select area',
    'ph_price_category' => 'Select price category',
    'ph_city_text' => 'Type city manually (optional)',

    'h_code' => 'Used for quick identification.',
    'h_legal_ar' => 'Shown on Arabic reports.',
    'h_legal_en' => 'Shown on English reports.',
    'h_type' => 'Choose customer type.',
    'h_channel' => 'Primary sales channel.',
    'h_country' => 'Used for country-wise reports.',
    'h_governorate' => 'Filters dependent cities.',
    'h_city_select' => 'Depends on governorate.',
    'h_area' => 'Depends on city.',
    'h_city_text' => 'If you prefer free text city.',
    'h_phone' => 'For contact and notifications.',
    'h_tax' => 'Required for tax invoices.',
    'h_price_category' => 'Affects product pricing.',
    'h_credit_limit' => 'Maximum allowed A/R.',
    'h_account_status' => 'Activate/Deactivate/Suspend.',
    'preview' => 'Preview',

    // =========================
    // Buttons
    // =========================
    'btn_new_customer' => '+ New Customer',
    'btn_save' => 'Save',
    'btn_update' => 'Update',
    'btn_cancel' => 'Cancel',
    'btn_edit' => 'Edit',
    'btn_view' => 'View',
    'btn_delete' => 'Delete',
    'btn_toggle' => 'Toggle Status',
    'btn_import' => 'Import',
    'btn_export' => 'Export',
    'btn_merge' => 'Merge',
    'btn_columns' => 'Columns',
    'btn_invoice' => 'New Invoice',
    'btn_statement' => 'Statement',
    'btn_activate' => 'Activate',
    'btn_deactivate' => 'Deactivate',
    'btn_back' => 'Back',

    // =========================
    // Status / Lists
    // =========================
    'status_active' => 'Active',
    'status_inactive' => 'Inactive',
    'status_suspended' => 'Suspended',
    'any_status' => 'Any status',
    'none' => 'None',
    'no_data' => 'No records found',
    'no_transactions' => 'No transactions found.',

    // =========================
    // Messages
    // =========================
    'msg_created' => '✅ Customer created successfully.',
    'msg_updated' => '✅ Customer updated successfully.',
    'msg_deleted' => '🗑️ Customer deleted successfully.',
    'msg_status_updated' => '🔁 Account status updated.',
    'msg_cannot_delete_has_tx' => '⚠️ Customer with transactions cannot be deleted — use deactivate.',
    'msg_error' => '❌ An error occurred. Please try again.',

    // =========================
    // Validation
    // =========================
    'val_code_required' => 'Customer code is required.',
    'val_code_unique' => 'Code already in use.',
    'val_legal_ar_required' => 'Arabic legal name is required.',
    'val_legal_en_required' => 'English legal name is required.',
    'val_credit_limit_min' => 'Credit limit must be at least 0.',
    'val_area_exists' => 'Selected area is invalid.',
    'val_country_exists' => 'Selected country is invalid.',
    'val_governorate_exists' => 'Selected governorate is invalid.',
    'val_city_exists' => 'Selected city is invalid.',
    'val_price_category_exists' => 'Selected price category is invalid.',

    // =========================
    // SweetAlert (Delete)
    // =========================
    'alert_delete_title' => 'Warning',
    'alert_delete_text' => '⚠️ Are you sure you want to delete? This action cannot be undone!',
    'alert_confirm' => 'Yes, delete',
    'alert_cancel' => 'Cancel',
    'alert_deleted_title' => 'Deleted!',
    'alert_deleted_text' => '✅ Deleted successfully.',

    // =========================
    // Filters & Saved Views
    // =========================
    'filters' => 'Filters',
    'apply_filters' => 'Apply Filters',
    'reset_filters' => 'Reset',
    'save_filter_view' => 'Save Filter View',
    'saved_views' => 'Saved Views',
    'view_name' => 'View Name',

    // =========================
    // Approvals (Optional)
    // =========================
    'approvals' => 'Approvals',
    'approve' => 'Approve',
    'reject' => 'Reject',
    'hold' => 'Hold',
    'reason_required' => 'Decision reason (required)',
    'approval_sent' => 'Approval request sent.',
    'approval_updated' => 'Approval updated.',

    // Titles & Buttons
    'title_customers_create' => 'Create Customer',
    'btn_save' => 'Save',
    'btn_cancel' => 'Cancel',
    'btn_back' => 'Back',

    // Sections
    'section_basic' => 'Basic Information',
    'section_location' => 'Location',
    'section_contact_finance' => 'Contact & Finance',

    // Fields / Helpers / Placeholders
    'f_code' => 'Code',
    'ph_code' => 'Auto or custom code',
    'h_code' => 'Unique reference for the customer (auto allowed).',

    'f_legal_name_ar' => 'Legal Name (Arabic)',
    'ph_legal_name_ar' => 'Enter Arabic legal name',
    'h_legal_ar' => 'Official Arabic name that appears on invoices.',

    'f_legal_name_en' => 'Legal Name (English)',
    'ph_legal_name_en' => 'Enter English legal name',
    'h_legal_en' => 'Official English name that appears on invoices.',

    'f_type' => 'Customer Type',
    'h_type' => 'Select the relation type.',
    'opt_individual' => 'Individual',
    'opt_company' => 'Company',

    'f_channel' => 'Sales Channel',
    'h_channel' => 'Where the customer is served.',
    'opt_retail' => 'Retail',
    'opt_wholesale' => 'Wholesale',
    'opt_online' => 'Online',
    'opt_pharmacy' => 'Pharmacy',

    'f_country' => 'Country',
    'ph_country' => 'Choose country',
    'h_country' => 'Used to determine address and taxation rules.',

    'f_governorate' => 'Governorate',
    'ph_governorate' => 'Choose governorate',
    'h_governorate' => 'Administrative region within the country.',

    'f_city_select' => 'City (List)',
    'ph_city_select' => 'Choose city',
    'h_city_select' => 'Pick an existing city from the list.',

    'f_area' => 'Area',
    'ph_area' => 'Choose area',
    'h_area' => 'Neighborhood / district.',

    'f_city_text' => 'City (Text)',
    'ph_city_text' => 'Type city name if not in list',
    'h_city_text' => 'Used when the city does not exist in the predefined list.',

    'f_phone' => 'Phone',
    'ph_phone' => 'Enter phone number',
    'h_phone' => 'Primary contact number.',

    'f_tax' => 'Tax Number',
    'ph_tax' => 'Enter VAT/Tax ID',
    'h_tax' => 'Registered taxpayer number if applicable.',

    'f_price_category' => 'Price Category',
    'ph_price_category' => 'Choose price list',
    'h_price_category' => 'Defines default prices / discounts.',

    'f_credit_limit' => 'Credit Limit',
    'h_credit_limit' => 'Maximum allowed outstanding balance.',

    'f_account_status' => 'Account Status',
    'status_active' => 'Active',
    'status_inactive' => 'Inactive',
    'status_suspended' => 'Suspended',
    'h_account_status' => 'Controls whether the customer can transact.',

    // Misc
    'loading' => 'Saving…',

    // Common Buttons/Titles
    'btn_save' => 'Save',
    'btn_cancel' => 'Cancel',
    'btn_close' => 'Close',
    'btn_back' => 'Back',
    'btn_edit' => 'Edit',
    'btn_update' => 'Update',
    'btn_print' => 'Print',
    'btn_new_invoice' => 'New Invoice',

    // Sections / Fields
    'section_basic' => 'Basic Information',
    'section_location' => 'Location',
    'section_contact_finance' => 'Contact & Finance',

    'f_code' => 'Code',
    'ph_code' => 'Auto or custom code',
    'h_code' => 'Unique reference for the customer (auto allowed).',

    'f_legal_name_ar' => 'Legal Name (Arabic)',
    'ph_legal_name_ar' => 'Enter Arabic legal name',
    'h_legal_ar' => 'Official Arabic name that appears on invoices.',

    'f_legal_name_en' => 'Legal Name (English)',
    'ph_legal_name_en' => 'Enter English legal name',
    'h_legal_en' => 'Official English name that appears on invoices.',

    'f_type' => 'Customer Type',
    'opt_individual' => 'Individual',
    'opt_company' => 'Company',
    'h_type' => 'Select the relation type.',

    'f_channel' => 'Sales Channel',
    'opt_retail' => 'Retail',
    'opt_wholesale' => 'Wholesale',
    'opt_online' => 'Online',
    'opt_pharmacy' => 'Pharmacy',
    'h_channel' => 'Where the customer is served.',

    'f_country' => 'Country',
    'ph_country' => 'Choose country',
    'h_country' => 'Used to determine address and taxation rules.',

    'f_governorate' => 'Governorate',
    'ph_governorate' => 'Choose governorate',
    'h_governorate' => 'Administrative region within the country.',

    'f_city_select' => 'City (List)',
    'ph_city_select' => 'Choose city',
    'h_city_select' => 'Pick an existing city from the list.',

    'f_area' => 'Area',
    'ph_area' => 'Choose area',
    'h_area' => 'Neighborhood / district.',

    'f_city_text' => 'City (Text)',
    'ph_city_text' => 'Type city name if not in list',
    'h_city_text' => 'Used when the city does not exist in the predefined list.',

    'f_phone' => 'Phone',
    'ph_phone' => 'Enter phone number',
    'h_phone' => 'Primary contact number.',

    'f_tax' => 'Tax Number',
    'ph_tax' => 'Enter VAT/Tax ID',
    'h_tax' => 'Registered taxpayer number if applicable.',

    'f_price_category' => 'Price Category',
    'ph_price_category' => 'Choose price list',
    'h_price_category' => 'Defines default prices / discounts.',

    'f_credit_limit' => 'Credit Limit',
    'h_credit_limit' => 'Maximum allowed outstanding balance.',

    'f_account_status' => 'Account Status',
    'status_active' => 'Active',
    'status_inactive' => 'Inactive',
    'status_suspended' => 'Suspended',
    'h_account_status' => 'Controls whether the customer can transact.',

    // Show Page
    'title_customers_show' => 'Customer Profile',

    // Profile & Cards
    'card_profile' => 'Profile',
    'desc_profile' => 'General information and address.',
    'card_summary' => 'Financial Summary',
    'desc_summary' => 'Credit overview and latest activity.',
    'card_activity' => 'Activity',
    'card_contacts' => 'Contacts',
    'desc_contacts' => 'People responsible for communication.',
    'card_notes' => 'Internal Notes',
    'desc_notes' => 'Private notes visible to staff.',
    'no_notes' => 'No notes recorded.',

    // Table / labels
    'th_code' => 'Code',
    'th_name' => 'Name',
    'th_phone' => 'Phone',
    'th_status' => 'Status',
    'th_country' => 'Country',
    'th_governorate' => 'Governorate',
    'th_city' => 'City',
    'th_area' => 'Area',
    'th_price_category' => 'Price Category',
    'th_balance' => 'Balance',
    'th_credit_limit' => 'Credit Limit',
    'th_available' => 'Available',
    'th_open_invoices' => 'Open Invoices',
    'th_type' => 'Type',
    'th_reference' => 'Reference',
    'th_date' => 'Date',
    'th_value' => 'Amount',
    'th_person' => 'Person',
    'th_role' => 'Role',
    'th_email' => 'Email',

    // Tabs & Empty states
    'tab_invoices' => 'Invoices',
    'tab_receipts' => 'Receipts',
    'tab_returns' => 'Returns',
    'tab_latest' => 'Latest',
    'no_transactions' => 'No transactions found.',
    'no_invoices' => 'No invoices found.',
    'no_receipts' => 'No receipts found.',
    'no_returns' => 'No returns found.',
    'no_contacts' => 'No contacts defined.',

    // Misc
    'credit_used' => 'Credit used',
    'tt_copy' => 'Copy to clipboard',

    // Pages
    'supplier_title' => 'Suppliers',
    'supplier_list' => 'Suppliers List',
    'supplier_create' => 'Create Supplier',
    'supplier_edit' => 'Edit Supplier',
    'supplier_show' => 'Supplier Details',

    // Fields
    'supplier_code' => 'Supplier Code',
    'supplier_name' => 'Supplier Name',
    'name_ar' => 'Arabic Name',
    'name_en' => 'English Name',
    'commercial_register' => 'Commercial Register',
    'tax_number' => 'Tax Number',
    'category' => 'Category',
    'payment_term' => 'Payment Term',

    // Geography
    'country' => 'Country',
    'governorate' => 'Governorate',
    'city' => 'City',
    'area' => 'Area',

    // Status & actions
    'status' => 'Status',
    'status_active' => 'Active',
    'status_inactive' => 'Inactive',
    'actions' => 'Actions',

    // Buttons
    'btn_save' => 'Save',
    'btn_update' => 'Update',
    'btn_back' => 'Back',
    'btn_create' => 'Add Supplier',
    'btn_show' => 'Show',
    'btn_edit' => 'Edit',
    'btn_delete' => 'Delete',
    'btn_toggle' => 'Toggle Status',

    // Common
    'no_data' => 'No data found',
    'search_supplier' => 'Search by name/code',

    // Success/Warnings
    'created_success' => '✅ Supplier created successfully.',
    'updated_success' => '✅ Supplier updated successfully.',
    'deleted_success' => '✅ Record deleted successfully.',
    'status_changed' => 'Status updated successfully.',
    'warn_inactive_supplier' => 'Warning: Supplier is inactive — purchasing is not allowed until activated.',

    // Placeholders + Descriptions
    'ph_code' => 'Enter a unique supplier code',
    'desc_code' => 'Used to link with other modules.',
    'ph_name_ar' => 'Enter Arabic name',
    'desc_name_ar' => 'Official Arabic name.',
    'ph_name_en' => 'Enter English name',
    'desc_name_en' => 'Official English name.',
    'ph_cr' => 'Commercial register (optional)',
    'desc_cr' => 'For legal verification.',
    'ph_tax' => 'Tax number (optional)',
    'desc_tax' => 'Required for fiscal invoices.',
    'ph_category' => 'Select category',
    'desc_category' => 'For reports and filters.',
    'ph_payment_term' => 'Select payment term',
    'desc_payment_term' => 'Due days & partial payments.',
    'desc_status' => 'Temporarily disable without deleting.',

    'ph_country' => 'Select country',
    'desc_country' => 'Supplier\'s country.',
    'ph_governorate' => 'Select governorate',
    'desc_governorate' => 'Within the selected country.',
    'ph_city' => 'Select city',
    'desc_city' => 'Within the governorate.',
    'ph_area' => 'Select area',
    'desc_area' => 'Within the city.',

    // Filters
    'filter_category' => 'Filter by category',
    'filter_governorate' => 'Filter by governorate',
    'filter_city' => 'Filter by city',
    'filter_status' => 'Filter by status',

    // SweetAlert2
    'alert_delete_title' => 'Warning',
    'alert_delete_text' => '⚠️ Are you sure you want to delete? This cannot be undone!',
    'alert_delete_confirm' => 'Yes, delete',
    'alert_delete_cancel' => 'Cancel',
    'alert_deleted' => 'Deleted!',
    'alert_deleted_text' => '✅ Deleted successfully.',

    'products_index_title' => 'Products Management',
    'product_create_title' => 'Create Product',
    'price_lists_title' => 'Price Lists',

    'search' => 'Search', 'all' => 'All', 'choose' => 'Choose', 'actions' => 'Actions', 'no_data' => 'No data.',
    'status' => 'Status', 'status_active' => 'Active', 'status_inactive' => 'Inactive',

    'sku' => 'SKU', 'barcode' => 'Barcode', 'name' => 'Name',
    'name_ar' => 'Name (AR)', 'name_en' => 'Name (EN)',
    'description_ar' => 'Description (AR)', 'description_en' => 'Description (EN)',
    'unit' => 'Unit', 'category' => 'Category', 'tax_rate' => 'Tax Rate %', 'opening_stock' => 'Opening Stock',

    'ph_search_sku_barcode_name' => 'Search by SKU/Barcode/Name…',
    'ph_sku' => 'Enter internal SKU',
    'ph_barcode' => 'Enter barcode (optional)',
    'ph_name_ar' => 'Enter Arabic name',
    'ph_name_en' => 'Enter English name',
    'ph_desc_ar' => 'Arabic short description',
    'ph_desc_en' => 'English short description',
    'hint_search_products' => 'Use keywords for quick access.',
    'hint_sku' => 'Unique SKU to avoid duplicates.',
    'hint_barcode' => 'Barcode must be unique.',
    'hint_name_ar' => 'Shown in Arabic UIs.',
    'hint_name_en' => 'Shown in English UIs.',
    'hint_unit' => 'Select a suitable unit.',
    'hint_category' => 'Choosing a category improves reports.',
    'hint_tax_rate' => 'Range 0 – 100.',
    'hint_opening_stock' => 'Beginning inventory.',
    'hint_status' => 'You can toggle it later from actions.',

    'filter_category' => 'Filter by Category',
    'filter_unit' => 'Filter by Unit',
    'filter_status' => 'Filter by Status',

    'btn_new_product' => 'New Product',
    'btn_save' => 'Save',
    'btn_back' => 'Back',

    'alert_title' => 'Warning',
    'alert_text' => 'Are you sure to delete? This cannot be undone!',
    'alert_confirm' => 'Yes, delete',
    'alert_cancel' => 'Cancel',
    'deleted' => 'Deleted',
    'msg_deleted_ok' => 'Deleted successfully.',
    'msg_saved_ok' => 'Saved successfully.',
    'msg_status_changed' => 'Status changed.',

    'err_price_conflict' => 'Price conflict — please adjust period or priority.',
    'err_price_conflict_banner' => 'Conflicting price for the same product within the same list and period.',
    'err_price_negative' => 'Price cannot be below 0.',
    // ... your previous translations

    'valid' => 'Valid',
    'invalid' => 'Invalid',
    'valid_from' => 'Valid From',
    'valid_to' => 'Valid To',
    'valid_date' => 'Valid Date',
    'valid_input' => 'Valid Input',
    'please_enter_valid_value' => 'Please enter a valid value',

    // 🧱 Headings
    'category_title' => 'Categories',
    'categories_management' => 'Categories Management',
    'category_create_title' => 'Create New Category',
    'category_edit_title' => 'Edit Category',
    'category_details' => 'Category Details',

    // 🔤 Fields
    'name' => 'Name',
    'name_ar' => 'Name (Arabic)',
    'name_en' => 'Name (English)',
    'description' => 'Description',
    'description_ar' => 'Description (Arabic)',
    'description_en' => 'Description (English)',
    'status' => 'Status',

    // ⚙️ Values
    'status_active' => 'Active',
    'status_inactive' => 'Inactive',
    'all' => 'All',

    // 🔎 Filters & Search
    'search' => 'Search',
    'filter_status' => 'Filter by Status',
    'ph_search_category' => 'Type category name in Arabic or English...',
    'hint_search_category' => 'You can search by full or partial category name.',
    'hint_status' => 'Select status to filter categories.',

    // 📝 Field hints
    'hint_name_ar' => 'Enter the category name in Arabic as it appears in the system.',
    'hint_name_en' => 'Enter the category name in English.',
    'hint_description_ar' => 'Write a short description of the category in Arabic.',
    'hint_description_en' => 'Write a short description of the category in English.',

    // 🧭 Buttons
    'btn_save' => 'Save',
    'btn_back' => 'Back',
    'btn_new_category' => 'New Category',
    'btn_edit' => 'Edit',
    'btn_delete' => 'Delete',
    'btn_update' => 'Update',
    'btn_cancel' => 'Cancel',

    // ⚡ Messages & Notifications
    'msg_saved_ok' => '✅ Data saved successfully.',
    'msg_deleted_ok' => '🗑️ Category deleted successfully.',
    'msg_status_changed' => '🔄 Category status changed successfully.',
    'no_data' => 'No data available.',
    'confirm_delete' => 'Are you sure you want to delete this category? This action cannot be undone.',

    // ⚠️ SweetAlert Messages
    'alert_title' => 'Warning',
    'alert_text' => '⚠️ Are you sure you want to delete this category?',
    'alert_confirm' => 'Yes, delete it',
    'alert_cancel' => 'Cancel',
    'deleted' => 'Deleted!',
    'updated' => 'Updated!',

    'products_title' => 'Products',
    'products_management_sub' => 'Manage product data and basic configurations.',

    // Headings
    'price_lists_title' => 'Price Lists',
    'price_lists_create_title' => 'Create Price List',
    'price_lists_edit_title' => 'Edit Price List',
    'basic_info' => 'Basic Information',

    // Fields
    'name' => 'Name',
    'name_ar' => 'Name (Arabic)',
    'name_en' => 'Name (English)',

    // Placeholders & Hints
    'ph_name_ar' => 'Enter the Arabic name',
    'ph_name_en' => 'Enter the English name',
    'hint_name_ar' => 'This name will be shown to Arabic users.',
    'hint_name_en' => 'This name will be shown to English users.',
    'show' => 'show',
    // Dates
    'valid_from' => 'Valid From',
    'valid_to' => 'Valid To',
    'hint_valid_from' => 'Start date of the list validity.',
    'hint_valid_to' => 'Leave empty for open-ended.',
    'validity' => 'Validity',

    // Status
    'status' => 'Status',
    'status_active' => 'Active',
    'status_inactive' => 'Inactive',
    'hint_status' => 'Enable or disable this list.',

    // Price Items
    'price_items' => 'Price Items',
    'add_row' => 'Add Item',
    'product' => 'Product',
    'price' => 'Price',
    'min_qty' => 'Min Qty',
    'max_qty' => 'Max Qty',

    // Actions & Buttons
    'actions' => 'Actions',
    'btn_new' => 'New',
    'btn_edit' => 'Edit',
    'btn_delete' => 'Delete',
    'btn_save' => 'Save',
    'btn_back' => 'Back',
    'choose' => 'Choose',

    // Filters & Search
    'search' => 'Search',
    'ph_search' => 'Search by name...',
    'all' => 'All',
    'per_page' => 'Rows per page',
    'items_count' => 'Items Count',

    // System messages
    'no_data' => 'No data found',
    'showing' => 'Showing',
    'of' => 'of',
    'msg_saved_ok' => 'Saved successfully.',
    'msg_updated_ok' => 'Updated successfully.',
    'msg_deleted_ok' => 'Deleted successfully.',
    // ===== Common / Global =====
    'inventory_title' => 'Inventory',
    'inventory_dashboard_title' => 'Inventory Dashboard',

    'search' => 'Search',
    'status' => 'Status',
    'all' => 'All',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'yes' => 'Yes',
    'no' => 'No',
    'none' => 'None',
    'choose' => 'Choose',
    'actions' => 'Actions',
    'no_data' => 'No data',
    'notes' => 'Notes',
    'hint_notes' => 'You can write a general note or reason here',
    'date_from' => 'Date from',
    'date_to' => 'Date to',

    // Buttons
    'btn_save' => 'Save',
    'btn_cancel' => 'Cancel',
    'btn_back' => 'Back',
    'btn_new' => 'Add',
    'btn_edit' => 'Edit',
    'btn_delete' => 'Delete',
    'btn_refresh' => 'Reload',
    'btn_export' => 'Export',
    'btn_print' => 'Print',

    // Alerts / Flash
    'saved_success' => 'Saved successfully',
    'deleted' => 'Deleted',
    'deleted_success' => 'Deleted successfully.',
    'alert_title' => 'Warning',
    'alert_delete_text' => 'Are you sure? This action cannot be undone!',
    'alert_confirm_yes' => 'Yes, delete',
    'alert_confirm_cancel' => 'Cancel',

    // Statuses / Workflow
    'draft' => 'Draft',
    'posted' => 'Posted',
    'cancelled' => 'Cancelled',

    // ===== Items =====
    'inventory_items_title' => 'Inventory Items',
    'btn_new_item' => 'New Item',
    'add_new_item' => 'Add New Item',

    'item_name' => 'Item Name',
    'item_name_ar' => 'Item Name (AR)',
    'item_name_en' => 'Item Name (EN)',
    'sku' => 'SKU',
    'uom' => 'UoM',
    'track_batch' => 'Track Batches',
    'track_serial' => 'Track Serials',

    'ph_search_sku_name' => 'Search by name or SKU',
    'ph_item_name_ar' => 'مثال: بنادول',
    'ph_item_name_en' => 'e.g., Panadol',
    'ph_sku' => 'e.g., PND-001',
    'ph_uom' => 'e.g., box',

    'hint_item_name' => 'Provide a clear name for the item',
    'hint_uom' => 'Enter the unit of measure',
    'hint_sku' => 'Provide a unique SKU code',
    'hint_filter_status' => 'Filter by status',

    'inventory_deleted_success' => 'Item deleted successfully',
    'inventory_item_status_changed' => 'Item status changed',

    // Validations (Items)
    'val_item_name_ar_required' => 'Arabic name is required',
    'val_item_name_en_required' => 'English name is required',
    'val_item_sku_required' => 'SKU is required',
    'val_item_sku_unique' => 'SKU has already been taken',
    'val_item_uom_required' => 'Unit of measure is required',

    // ===== Warehouses =====
    'inventory_warehouses_title' => 'Warehouses',
    'btn_new_warehouse' => 'New Warehouse',
    'warehouse' => 'Warehouse',
    'warehouse_code' => 'Warehouse Code',
    'warehouse_branch' => 'Branch',

    'warehouse_deleted_success' => 'Warehouse deleted successfully',
    'warehouse_status_changed' => 'Warehouse status changed',

    // Validations (Warehouses)
    'val_wh_name_ar_required' => 'Warehouse Arabic name is required',
    'val_wh_name_en_required' => 'Warehouse English name is required',
    'val_wh_code_required' => 'Warehouse code is required',
    'val_wh_code_unique' => 'Warehouse code has already been taken',

    // ===== Transactions =====
    'inventory_transactions_title' => 'Stock Transactions',
    'inventory_transactions_create' => 'Create Stock Transaction',
    'btn_new_transaction' => 'New Transaction',
    'add_new_transaction' => 'Add New Transaction',

    'trx_no' => 'Trx No.',
    'type' => 'Type',
    'trx_date' => 'Trx Date',
    'from' => 'From',
    'to' => 'To',
    'from_warehouse' => 'From Warehouse',
    'to_warehouse' => 'To Warehouse',

    'ph_search_trx_no' => 'Search by transaction no.',

    'lines' => 'Lines',
    'add_line' => 'Add Line',
    'item' => 'Item',
    'qty' => 'Qty',
    'reason' => 'Reason',

    'hint_trx_type' => 'Choose the transaction type',
    'hint_trx_date' => 'Transaction date & time',
    'hint_from_wh' => 'Choose the source warehouse (optional)',
    'hint_to_wh' => 'Choose the destination warehouse (optional)',
    'hint_item' => 'Choose the item',
    'hint_qty' => 'Enter the required quantity',

    // Transaction Types
    'trx_sales_issue' => 'Sales Issue',
    'trx_sales_return' => 'Sales Return',
    'trx_adjustment' => 'Adjustment',
    'trx_transfer' => 'Transfer',
    'trx_purchase_receive' => 'Purchase Receive',

    // Transaction messages
    'cannot_post_non_draft' => 'Cannot post a non-draft transaction',
    'posted_success' => 'Transaction posted successfully',
    'cannot_cancel_posted' => 'Cannot cancel a posted transaction',
    'cancelled_success' => 'Transaction cancelled successfully',
    'cannot_delete_posted' => 'Cannot delete a posted transaction',

    // Validations (Transactions)
    'val_trx_type_required' => 'Transaction type is required',
    'val_trx_date_required' => 'Transaction date is required',
    'val_trx_item_required' => 'Item is required',
    'val_trx_qty_min' => 'Quantity must be greater than zero',
    'val_trx_uom_required' => 'Line unit of measure is required',

    // ===== Counts (Stock Count) =====
    'inventory_counts_title' => 'Stock Counts',
    'count_start' => 'Start Count',
    'count_policy' => 'Count Policy',
    'count_policy_periodic' => 'Periodic Count',
    'count_policy_spot' => 'Spot Check',
    'count_started' => 'Stock count started successfully',
    'count_open' => 'Open',
    'count_review' => 'In Review',
    'count_approved' => 'Approved',
    'count_rejected' => 'Rejected',
    'count_system_qty' => 'System Qty',
    'count_counted_qty' => 'Counted Qty',
    'count_difference_qty' => 'Difference Qty',
    'count_approve' => 'Approve Count',
    'count_approved_success' => 'Count approved and balances updated successfully',
    'count_reject' => 'Reject Count',
    'count_rejected_success' => 'Count rejected',

    // Count validations/messages
    'val_count_warehouse_required' => 'Please choose the warehouse for counting',
    'val_count_policy_required' => 'Count policy is required',
    'val_count_policy_invalid' => 'Invalid count policy',

    // ===== Alerts =====
    'inventory_alerts_title' => 'Inventory Alerts',
    'reorder_alerts' => 'Reorder Alerts',
    'expiry_alerts' => 'Expiry Alerts',
    'expired_stock' => 'Expired Stock',
    'reorder_suggest' => 'Suggest creating a purchase order or an inter-warehouse transfer',
    'expiry_days_left' => 'Days left to expire',
    'alert_generated' => 'Alert generated',

    // ===== Reports =====
    'inventory_reports_title' => 'Inventory Reports',
    'report_stock_movements' => 'Stock Movements Report',
    'report_counts_diff' => 'Stock Count & Differences Report',
    'report_batches' => 'Batches Report',
    'report_alerts' => 'Alerts Report',
    'report_transfers' => 'Inter-branch Transfers Report',
    'report_negative' => 'Negative Stock Report',

    // ===== Settings =====
    'inventory_settings_title' => 'Inventory Settings',
    'negative_stock_policy' => 'Negative Stock Policy',
    'negative_stock_block' => 'Block negatives',
    'negative_stock_warn' => 'Allow with warning',
    'expiry_alert_days' => 'Expiry Alert Days',
    'transaction_sequences' => 'Transaction Sequences',
    'sequence_pattern' => 'Sequence Pattern (e.g., INV-TRX-{YYYY}-{####})',

    // Negative stock system messages
    'neg_blocked' => 'Negative stock blocked – requested qty exceeds available.',
    'neg_allowed_warn' => 'Negative stock allowed with warning and logged to discrepancies.',

    // ===== Filters / Headings helpers =====
    'warehouse_filter' => 'Warehouse Filter',
    'from_warehouse_filter' => 'From Warehouse',
    'to_warehouse_filter' => 'To Warehouse',
      // General Titles
    'inventory_items_title'        => 'Items',
    'inventory_warehouses_title'   => 'Warehouses',
    'inventory_transactions_title' => 'Transactions',
    'inventory_counts_title'       => 'Stock Counts',
    'inventory_alerts_title'       => 'Alerts',
    'inventory_settings_title'     => 'Settings',
    'inventory_reports_title'      => 'Reports',

    // Buttons
    'btn_new_item' => 'Add New Item',
    'btn_save'     => 'Save',
    'btn_cancel'   => 'Cancel',
    'btn_edit'     => 'Edit',
    'btn_delete'   => 'Delete',

    // Messages
    'no_data'     => 'No data available.',
    'saved_success'   => 'Saved successfully ✅',
    'deleted_success' => 'Deleted successfully ✅',
    'status_changed'  => 'Status changed successfully',
    'export_in_progress' => 'Preparing export file...',
    'alert_title'   => 'Warning',
    'alert_delete_text' => '⚠️ Are you sure you want to delete this record? This action cannot be undone!',
    'alert_confirm_yes' => 'Yes, Delete it',
    'alert_confirm_cancel' => 'Cancel',
    'deleted' => 'Deleted!',

    // Fields
    'item_name'     => 'Item Name',
    'sku'           => 'SKU',
    'uom'           => 'Unit',
    'track_batch'   => 'Track Batch',
    'track_serial'  => 'Track Serial',
    'status'        => 'Status',
    'actions'       => 'Actions',

    // Status
    'active'   => 'Active',
    'inactive' => 'Inactive',
    'yes' => 'Yes',
    'no'  => 'No',
    'all' => 'All',

    // Search / Filters
    'search' => 'Search',
    'ph_search_sku_name' => 'Search by name or SKU...',
    'hint_search_items'   => 'You can search by Arabic/English name or SKU.',

    // Filters for Reports
    'filter_warehouse' => 'Warehouse',
    'filter_item'      => 'Item',
    'filter_type'      => 'Transaction Type',
    'date_from'        => 'Date From',
    'date_to'          => 'Date To',

    // Transaction Types
    'type_sales_issue'      => 'Sales Issue',
    'type_sales_return'     => 'Sales Return',
    'type_adjustment'       => 'Adjustment',
    'type_transfer'         => 'Transfer',
    'type_purchase_receive' => 'Purchase Receive',

    // Extra fields in report
    'trx_no'           => 'Transaction No.',
    'trx_date'         => 'Transaction Date',
    'warehouse_from'   => 'From Warehouse',
    'warehouse_to'     => 'To Warehouse',
    'type'             => 'Type',
    'status_posted'    => 'Posted',
    'status_cancelled' => 'Cancelled',
    'status_draft'     => 'Draft',

    // Reports
    'inventory_reports' => 'Inventory Reports',
    'export_excel'      => 'Export Excel',

];