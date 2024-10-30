<?php

/**
 * Changisha Uninstall
 *
 * Uninstalling Changisha deletes user roles, pages, tables, and options.
 *
 * @package Changisha\Uninstaller
 * @version 1.0.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;

// Delete options.
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'changisha\_%';" );

// Delete tables.
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}changisha_transactions" );

// Clear any cached data that has been removed.
wp_cache_flush();