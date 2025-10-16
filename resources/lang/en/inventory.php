<?php

return [

    // ===== Manage =====
    'manage_title'    => 'Inventory Management',
    'manage_subtitle' => 'Pick a module to manage your inventory.',
    'open'            => 'Open',
    'soon'            => 'Coming soon',
    'route_missing'   => 'Route not defined',

    // Module cards
    'module_settings'     => 'Inventory Settings',
    'module_counts'       => 'Stock Counts',
    'module_alerts'       => 'Stock Alerts',
    'module_transactions' => 'Transactions',
    'module_warehouses'   => 'Warehouses',
    'module_products'     => 'Products',
    'module_stocks'       => 'Stocks',

    // ===== Units Index =====
    'units_management_title' => 'Units Management',
    'units_management_sub'   => 'Major units with their related minor units.',
    'add_unit'               => 'Add Unit',
    'search'                 => 'Search',
    'search_placeholder'     => 'Code or name...',
    'search_hint'            => 'Search by code or name (AR/EN).',
    'clear_search'           => 'Clear search',

    'major_unit'     => 'Major Unit',
    'minor_unit'     => 'Minor Unit',
    'active'         => 'Active',
    'inactive'       => 'Inactive',
    'default'        => 'Default',
    'no'             => 'No',
    'code'           => 'Code',
    'name_ar'        => 'Name (AR)',
    'name_en'        => 'Name (EN)',
    'abbreviation'   => 'Abbreviation',
    'ratio'          => 'Ratio',
    'status'         => 'Status',
    'actions'        => 'Actions',
    'edit'           => 'Edit',
    'delete'         => 'Delete',
    'no_minors_for_major' => 'No minor units linked to this major unit.',
    'no_units_yet'   => 'No units yet.',

    // Delete confirm (SweetAlert)
    'confirm_delete_title' => 'Confirm deletion',
    'confirm_delete_text'  => 'Are you sure you want to delete this unit? This action cannot be undone.',
    'confirm_delete_yes'   => 'Yes, delete',
    'confirm_delete_cancel'=> 'Cancel',

    // ===== Units Form =====
    'units_form_add_title'    => 'Add Unit',
    'units_form_edit_title'   => 'Edit Unit',
    'units_form_subtitle'     => 'Manage measurement units (major/minor) with default conversion, abbreviation, and status.',
    'back'                    => 'Back',
    'save'                    => 'Save',
    'saving'                  => 'Saving...',
    'cancel'                  => 'Cancel',

    'field_code'              => 'Code',
    'field_kind'              => 'Type',
    'field_status'            => 'Status',
    'field_name_ar'           => 'Name (AR)',
    'field_name_en'           => 'Name (EN)',
    'field_abbreviation'      => 'Abbreviation',
    'field_parent'            => 'Parent (Major)',
    'field_ratio_to_parent'   => 'Ratio to Major',
    'field_is_default_minor'  => 'Set as default minor for this major',

    'kind_major'              => 'Major',
    'kind_minor'              => 'Minor',

    'hint_code_unique'        => 'Unique code for the unit.',
    'hint_abbreviation'       => 'Optional; used in lists and invoices.',
    'hint_ratio'              => '1 Major = :ratio Minor',
    'hint_ratio_inverse'      => '1 Minor = (1 / ratio) of Major.',

    // Help/Preview card
    'tips_title'              => 'Quick Tips',
    'tips_item_major'         => 'A major unit does not require a parent or ratio.',
    'tips_item_minor'         => 'A minor unit must be linked to a major with a defined ratio.',
    'tips_item_default'       => 'Only one default minor per major is allowed — enforced automatically.',
    'preview_title'           => 'Quick Preview',

    // Preview labels
    'preview_state'           => 'State',
    'preview_kind'            => 'Type',
    'preview_code'            => 'Code',
    'preview_name'            => 'Name',
    'preview_parent'          => 'Major',
    'preview_ratio'           => 'Ratio',
    'preview_default'         => 'Default',
    'yes'                     => 'Yes',

    // Shortcuts
    'shortcut_save'           => 'Shortcut: Ctrl + S to save',
      // Inventory Settings page
    'settings_title' => 'Inventory Settings',
    'settings_sub'   => 'Configure negative policy, expiry alerts, and transaction numbering',

    // Negative stock policy
    'negative_stock_policy' => 'Negative Stock Policy',
    'policy_block'          => 'Block negatives',
    'policy_warn'           => 'Warn only',
    'help_negative_stock_policy_short' => 'Choose how the system behaves when an issue would make stock negative.',

    // Expiry alert
    'expiry_alert_days'      => 'Expiry Alert (days)',
    'help_expiry_alert_days' => 'Show an alert when an item will expire within this number of days or less.',

    // Numbering pattern
    'transaction_pattern'      => 'Transaction Number Pattern',
    'help_transaction_pattern' => 'Use tokens: {YYYY} year, {YY} two-digit year, {MM} month, {DD} day, {####} 4-digit sequence.',
    'example'                  => 'Example',

    // Buttons / common
    'btn_save_settings' => 'Save Settings',
    'saved_success'     => 'Settings saved successfully',
    'enabled'           => 'Enabled',
    'disabled'          => 'Disabled',
    'none'              => 'None',
];
