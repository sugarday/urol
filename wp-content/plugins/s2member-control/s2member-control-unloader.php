<?php

function s2member_control_unload() {
    remove_action("init", "c_ws_plugin__s2member_translations::load", 2);
    /**/
    remove_action("init", "c_ws_plugin__s2member_ssl::check_force_ssl", 3);
    remove_action("init", "c_ws_plugin__s2member_user_securities::initialize", 3);
    remove_action("init", "c_ws_plugin__s2member_no_cache::no_cache", 3);
    /**/
    remove_action("init", "c_ws_plugin__s2member_register::register", 4);
    remove_action("init", "c_ws_plugin__s2member_paypal_notify::paypal_notify", 4);
    remove_action("init", "c_ws_plugin__s2member_files::check_file_download_access", 4);
    remove_action("init", "c_ws_plugin__s2member_profile_mods::handle_profile_modifications", 4);
    remove_action("init", "c_ws_plugin__s2member_profile_mods_4bp::handle_profile_modifications_4bp", 4);
    remove_action("init", "c_ws_plugin__s2member_tracking_cookies::delete_sp_tracking_cookie", 4);
    remove_action("init", "c_ws_plugin__s2member_tracking_cookies::delete_tracking_cookie", 4);
    remove_action("init", "c_ws_plugin__s2member_cron_jobs::auto_eot_system_via_cron", 4);
    remove_action("init", "c_ws_plugin__s2member_mo_page::membership_options_page", 4);
    remove_action("init", "c_ws_plugin__s2member_s_badge_status::s_badge_status", 4);
    /**/
    remove_action("init", "c_ws_plugin__s2member_admin_css_js::menu_pages_css", 5);
    remove_action("init", "c_ws_plugin__s2member_admin_css_js::menu_pages_js", 5);
    remove_action("init", "c_ws_plugin__s2member_css_js::css", 5);
    /**/
    remove_action("init", "c_ws_plugin__s2member_constants::constants", 6);
    /**/
    remove_action("init", "c_ws_plugin__s2member_css_js::js_w_globals", 7);
    remove_action("init", "c_ws_plugin__s2member_paypal_return::paypal_return", 7);
    remove_action("init", "c_ws_plugin__s2member_profile::profile", 7);
    /**/
    remove_action("init", "c_ws_plugin__s2member_labels::config_label_translations", 10);
    /**/
    remove_action("init", "c_ws_plugin__s2member_login_redirects_r::remove_login_redirect_filters", 11);
    /**/
    remove_action("pre_get_posts", "c_ws_plugin__s2member_security::security_gate_query", 100);
    /**/
    remove_action("wp", "c_ws_plugin__s2member_ssl::check_force_ssl", 1);
    remove_action("wp", "c_ws_plugin__s2member_security::security_gate", 1);
    /* Stay ahead of BuddyPress® at `3` on `wp in `bp-core-hooks.php`. */
    /* Set to `1` so other plugins may come between these. */
    /**/
    remove_filter("wp_mail", "c_ws_plugin__s2member_email_configs::email_filter");
    /**/
    remove_filter("widget_text", "do_shortcode"); /* Shortcodes in widgets. */
    /**/
    remove_action("wp_print_styles", "c_ws_plugin__s2member_css_js_themes::add_css");
    remove_action("wp_print_scripts", "c_ws_plugin__s2member_css_js_themes::add_js_w_globals");
    /**/
    remove_action("wp_login_failed", "c_ws_plugin__s2member_brute_force::track_failed_logins");
    remove_filter("authenticate", "c_ws_plugin__s2member_brute_force::stop_brute_force_logins", 100);
    /**/
    remove_action("delete_user", "c_ws_plugin__s2member_user_deletions::handle_user_deletions");
    remove_action("wpmu_delete_user", "c_ws_plugin__s2member_user_deletions::handle_ms_user_deletions");
    remove_action("remove_user_from_blog", "c_ws_plugin__s2member_user_deletions::handle_ms_user_deletions", 10, 2);
    /**/
    remove_filter("enable_edit_any_user_configuration", "c_ws_plugin__s2member_user_securities::ms_allow_edits");
    /**/
    remove_filter("pre_option_default_role", "c_ws_plugin__s2member_option_forces::force_default_role");
    remove_filter("pre_site_option_default_user_role", "c_ws_plugin__s2member_option_forces::force_mms_default_role");
    remove_filter("pre_site_option_add_new_users", "c_ws_plugin__s2member_option_forces::mms_allow_new_users");
    remove_filter("pre_site_option_dashboard_blog", "c_ws_plugin__s2member_option_forces::mms_dashboard_blog");
    remove_filter("pre_option_users_can_register", "c_ws_plugin__s2member_option_forces::check_register_access");
    remove_filter("pre_site_option_registration", "c_ws_plugin__s2member_option_forces::check_mms_register_access");
    remove_filter("bp_core_get_root_options", "c_ws_plugin__s2member_option_forces::check_bp_mms_register_access");
    remove_filter("bp_core_get_site_options", "c_ws_plugin__s2member_option_forces::check_bp_mms_register_access");
    /**/
    remove_filter("random_password", "c_ws_plugin__s2member_registrations::generate_password");
    remove_action("user_register", "c_ws_plugin__s2member_registrations::configure_user_registration");
    remove_action("register_form", "c_ws_plugin__s2member_custom_reg_fields::custom_registration_fields");
    /**/
    remove_filter("add_signup_meta", "c_ws_plugin__s2member_registrations::ms_process_signup_meta");
    remove_filter("bp_signup_usermeta", "c_ws_plugin__s2member_registrations::ms_process_signup_meta");
    remove_filter("wpmu_validate_user_signup", "c_ws_plugin__s2member_registrations::ms_validate_user_signup");
    remove_action("signup_hidden_fields", "c_ws_plugin__s2member_registrations::ms_process_signup_hidden_fields");
    remove_filter("registration_errors", "c_ws_plugin__s2member_registrations::ms_register_existing_user", 11, 3);
    remove_filter("wpmu_signup_user_notification_email", "c_ws_plugin__s2member_email_configs::ms_nice_email_roles", 11);
    remove_filter("_wpmu_activate_existing_error_", "c_ws_plugin__s2member_registrations::ms_activate_existing_user", 10, 2);
    remove_action("wpmu_activate_user", "c_ws_plugin__s2member_registrations::configure_user_on_ms_user_activation", 10, 3);
    remove_action("wpmu_activate_blog", "c_ws_plugin__s2member_registrations::configure_user_on_ms_blog_activation", 10, 5);
    remove_action("signup_extra_fields", "c_ws_plugin__s2member_custom_reg_fields::ms_custom_registration_fields");
    /**/
    remove_action("bp_after_signup_profile_fields", "c_ws_plugin__s2member_custom_reg_fields_4bp::custom_registration_fields_4bp");
    remove_action("bp_after_profile_field_content", "c_ws_plugin__s2member_custom_reg_fields_4bp::custom_profile_fields_4bp");
    remove_action("bp_profile_field_item", "c_ws_plugin__s2member_custom_reg_fields_4bp::custom_profile_field_items_4bp");
    /**/
    remove_action("wp_login", "c_ws_plugin__s2member_login_redirects::login_redirect");
    remove_action("login_head", "c_ws_plugin__s2member_login_customizations::login_header_styles");
    remove_filter("login_headerurl", "c_ws_plugin__s2member_login_customizations::login_header_url");
    remove_filter("login_headertitle", "c_ws_plugin__s2member_login_customizations::login_header_title");
    remove_action("login_footer", "c_ws_plugin__s2member_login_customizations::login_footer_design");
    /**/
    remove_action("login_footer", "c_ws_plugin__s2member_tracking_codes::display_signup_tracking_codes");
    remove_action("wp_footer", "c_ws_plugin__s2member_tracking_codes::display_signup_tracking_codes");
    /**/
    remove_action("login_footer", "c_ws_plugin__s2member_tracking_codes::display_modification_tracking_codes");
    remove_action("wp_footer", "c_ws_plugin__s2member_tracking_codes::display_modification_tracking_codes");
    /**/
    remove_action("login_footer", "c_ws_plugin__s2member_tracking_codes::display_ccap_tracking_codes");
    remove_action("wp_footer", "c_ws_plugin__s2member_tracking_codes::display_ccap_tracking_codes");
    /**/
    remove_action("login_footer", "c_ws_plugin__s2member_tracking_codes::display_sp_tracking_codes");
    remove_action("wp_footer", "c_ws_plugin__s2member_tracking_codes::display_sp_tracking_codes");
    /**/
    remove_action("wp_footer", "c_ws_plugin__s2member_wp_footer::wp_footer_code");
    /**/
    remove_action("admin_init", "c_ws_plugin__s2member_admin_lockouts::admin_lockout", 1);
    remove_action("admin_init", "c_ws_plugin__s2member_check_activation::check");
    /**/
    remove_action("load-settings.php", "c_ws_plugin__s2member_op_notices::multisite_ops_notice");
    remove_action("load-options-general.php", "c_ws_plugin__s2member_op_notices::general_ops_notice");
    remove_action("load-options-reading.php", "c_ws_plugin__s2member_op_notices::reading_ops_notice");
    remove_action("load-user-new.php", "c_ws_plugin__s2member_user_new::admin_user_new_fields");
    /**/
    remove_action("add_meta_boxes", "c_ws_plugin__s2member_meta_boxes::add_meta_boxes");
    remove_action("save_post", "c_ws_plugin__s2member_meta_box_saves::save_meta_boxes");
    remove_action("admin_menu", "c_ws_plugin__s2member_menu_pages::add_admin_options");
    remove_action("network_admin_menu", "c_ws_plugin__s2member_menu_pages::add_network_admin_options");
    remove_action("admin_bar_menu", "c_ws_plugin__s2member_admin_lockouts::filter_admin_menu_bar", 100);
    remove_action("admin_print_scripts", "c_ws_plugin__s2member_menu_pages::add_admin_scripts");
    remove_action("admin_print_styles", "c_ws_plugin__s2member_menu_pages::add_admin_styles");
    remove_filter("update_feedback", "c_ws_plugin__s2member_mms_patches::sync_mms_patches");
    /**/
    remove_action("admin_notices", "c_ws_plugin__s2member_admin_notices::admin_notices");
    remove_action("user_admin_notices", "c_ws_plugin__s2member_admin_notices::admin_notices");
    remove_action("network_admin_notices", "c_ws_plugin__s2member_admin_notices::admin_notices");
    /**/
    remove_action("pre_user_query", "c_ws_plugin__s2member_users_list::users_list_query");
    remove_filter("manage_users_columns", "c_ws_plugin__s2member_users_list::users_list_cols");
    remove_filter("manage_users_custom_column", "c_ws_plugin__s2member_users_list::users_list_display_cols", 10, 3);
    remove_action("edit_user_profile", "c_ws_plugin__s2member_users_list::users_list_edit_cols");
    remove_action("show_user_profile", "c_ws_plugin__s2member_users_list::users_list_edit_cols");
    remove_action("edit_user_profile_update", "c_ws_plugin__s2member_users_list::users_list_update_cols");
    remove_action("personal_options_update", "c_ws_plugin__s2member_users_list::users_list_update_cols");
    remove_action("set_user_role", "c_ws_plugin__s2member_registration_times::synchronize_paid_reg_times", 10, 2);
    remove_filter("show_password_fields", "c_ws_plugin__s2member_user_securities::hide_password_fields", 10, 2);
    /**/
    remove_filter("cron_schedules", "c_ws_plugin__s2member_cron_jobs::extend_cron_schedules");
    remove_action("ws_plugin__s2member_auto_eot_system__schedule", "c_ws_plugin__s2member_auto_eots::auto_eot_system");
    /**/
    remove_action("wp_ajax_ws_plugin__s2member_update_roles_via_ajax", "c_ws_plugin__s2member_roles_caps::update_roles_via_ajax");
    /**/
    remove_action("wp_ajax_ws_plugin__s2member_sp_access_link_via_ajax", "c_ws_plugin__s2member_sp_access::sp_access_link_via_ajax");
    remove_action("wp_ajax_ws_plugin__s2member_reg_access_link_via_ajax", "c_ws_plugin__s2member_register_access::reg_access_link_via_ajax");
    /**/
    remove_action("wp_ajax_ws_plugin__s2member_delete_reset_all_ip_restrictions_via_ajax", "c_ws_plugin__s2member_ip_restrictions::delete_reset_all_ip_restrictions_via_ajax");
    remove_action("wp_ajax_ws_plugin__s2member_delete_reset_specific_ip_restrictions_via_ajax", "c_ws_plugin__s2member_ip_restrictions::delete_reset_specific_ip_restrictions_via_ajax");
    /**/
    remove_action("ws_plugin__s2member_during_collective_mods", "c_ws_plugin__s2member_list_servers::auto_process_list_server_removals", 10, 7);
    remove_action("ws_plugin__s2member_during_collective_eots", "c_ws_plugin__s2member_list_servers::auto_process_list_server_removals", 10, 4);
    /**/
    remove_filter("ws_plugin__s2member_content_redirect_status", "c_ws_plugin__s2member_utils_urls::redirect_browsers_using_302_status");
    /**/
    remove_action("bbp_activation", "c_ws_plugin__s2member_roles_caps::config_roles", 11);
    /**/
}

?>