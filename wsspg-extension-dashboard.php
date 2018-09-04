<?php
/**
 * @package WSSPGExtention
 */

/*
Plugin Name: WSSPG Extention
Plugin URI:
Description: Extention to WooCommerce Stripe Subscription Payment Gateway.
Version: 1.0.0
License: GPLv2 or later
Text Domain: wsspg-extension-dashboard
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2018 Automattic, Inc.
 */

defined( 'ABSPATH' ) or die( 'Hey, you can\t access this file!' );

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

function activate_wsspg_extantion_plugin() {
	Inc\Activate::activate();
}
register_activation_hook( __FILE__,  'activate_wsspg_extantion_plugin' );

if ( class_exists( 'Inc\\Init' ) ) {
	Inc\Init::register_services();
}

