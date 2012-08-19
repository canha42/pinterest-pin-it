<?php

// Security check : stop here if not really uninstalling.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

delete_option( 'ppibfi_pg_index');
delete_option( 'ppibfi_pg_single');
delete_option( 'ppibfi_pg_page');
delete_option( 'ppibfi_pg_cat');
delete_option( 'ppibfi_img_pinthis');
delete_option( 'ppibfi_opt_enable');
delete_option( 'ppibfi_content_width');
delete_option( 'pibfi_no_show_button');