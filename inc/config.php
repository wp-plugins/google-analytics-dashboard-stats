<?php
/**
 * @package MM_STATS_Functions
 * @version 1.5
 * Project Name: Google Analytics Dashboard Stats
.---------------------------------------------------------------------------.
|   Authors: Mike Mattner                                                   |
|   Copyright (c) 2012, Mike Mattner. All Rights Reserved.                  |
'---------------------------------------------------------------------------'
* License: GPL
 
=====================================================================================
Copyright (C) 2012 Mike Mattner

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
=====================================================================================
*/

define('ga_email',get_option('mm_ga_stats_email'));        // GA Email
define('ga_password',get_option('mm_ga_stats_password'));  // 2-part authorization password

$properties = array(
		"property"   => array(
		        "title"         => get_option('mm_ga_stats_prop_label'),
				"id"            => get_option('mm_ga_stats_prop_id')
		),
		"options"   => array (
		        "directory"    => "google-analytics-dashboard-stats",
		)
);
	
?>