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
];
