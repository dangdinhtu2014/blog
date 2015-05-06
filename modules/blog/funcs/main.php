<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

// Chuyển hướng đến trang chủ không có tên module nếu có cấu hình
if( $BL->setting['sysRedirect2Home'] and empty( $home ) and $page <= 1 and $global_config['site_home_module'] == $module_name )
{
	header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA, true ) );
	die();
}

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

// Xac dinh thong tin phan trang
$per_page = intval( $BL->setting['numPostPerPage'] );

// SQL co ban
$sql = "FROM " . $BL->table_prefix . "_rows WHERE status=1 AND inhome=1";
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query( $sql1 );
$all_page = $result1->fetchColumn();

// Lay du lieu
$sql = "SELECT * " . $sql . " ORDER BY pubtime DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . ", " . $per_page;
$result = $db->query( $sql );

$array = $array_userids = array();

// Danh sách các bảng data của bài viết sẽ cần duyệt qua để lấy nội dung hmtl
$array_table_pass = array();

while( $row = $result->fetch() )
{
	$row['mediatype'] = intval( $row['mediatype'] );
	$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
	$row['postName'] = '';
	
	// Xac dinh media
	if( $row['mediatype'] == 0 )
	{
		$row['mediavalue'] = $row['images'];
	}
	
	if( ! empty( $row['mediavalue'] ) )
	{
		if( is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . $row['mediavalue'] ) )
		{
			$row['mediavalue'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . $row['mediavalue'];
		}
		elseif( ! nv_is_url( $row['mediavalue'] ) )
		{
			$row['mediavalue'] = '';
		}
	}

	// Xac dinh images
	if( ! empty( $row['images'] ) )
	{
		if( is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . $row['images'] ) )
		{
			$row['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . $row['images'];
		}
		elseif( ! nv_is_url( $row['images'] ) )
		{
			$row['images'] = '';
		}
	}
	
	// Mặc định nội dung html trống
	$row['bodyhtml'] = '';
	
	// Đánh dấu fullpage
	if( ! empty( $row['fullpage'] ) )
	{
		$table = ceil( $row['id'] / 4000 );
		$array_table_pass[$table][$row['id']] = $row['id'];
		unset( $table );
	}
	
	$array[$row['id']] = $row;
	$array_userids[$row['postid']] = $row['postid'];
}

// Khong cho dat $page tuy y
if( $page > 1 and empty( $array ) )
{
	header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	die();
}

// Lay thanh vien dang bai
if( ! empty( $array_userids ) )
{
	$sql = "SELECT userid, username, first_name, last_name FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN(" . implode( ",", $array_userids ) . ")";
	$result = $db->query( $sql );
	
	$array_userids = array();
	while( $row = $result->fetch() )
	{
		$row['full_name'] = ( $global_config['name_show'] )  ? $row['first_name'] . ' ' . $row['last_name'] : $row['last_name'] . ' ' . $row['first_name'];
		$array_userids[$row['userid']] = $row['full_name'] ? $row['full_name'] : $row['username'];
	}
	
	foreach( $array as $row )
	{
		if( isset( $array_userids[$row['postid']] ) )
		{
			$array[$row['id']]['postName'] = $array_userids[$row['postid']];
		}
	}
}

// Lấy nội dung html nếu có
if( ! empty( $array_table_pass ) )
{
	foreach( $array_table_pass as $table => $postids )
	{
		$sql = "SELECT id, bodyhtml FROM " . $BL->table_prefix . "_data_" . $table . " WHERE id IN( " . implode( ",", $postids ) . " )";
		$result = $db->query( $sql );
		
		while( $row = $result->fetch() )
		{
			$array[$row['id']]['bodyhtml'] = $row['bodyhtml'];
		}
	}
}

// Du lieu phan trang
 
$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
$total_pages = ceil( $all_page / $per_page );

// Them vao tieu de neu phan trang
if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $BL->glang('page') . ' ' . $page;
	$key_words .= ', ' . $BL->glang('page') . ' ' . $page;
	$description .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $BL->glang('page') . ' ' . $page;
}
 
 
// Open Graph
if( $global_config['site_home_module'] == $module_name and ! empty( $home ) )
{
	$meta_property['og:title'] = $global_config['site_name'];
	$meta_property['og:type'] = 'website';
	$meta_property['og:url'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA, true );
	$meta_property['og:description'] = $global_config['site_description'];
	 
}
else
{
	$meta_property['og:title'] = $page_title . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'];
	$meta_property['og:type'] = 'website';
	$meta_property['og:url'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true );
	$meta_property['og:description'] = $module_info['description'];
 
}

if( ! empty( $BL->setting['sysDefaultImage'] ) )
{
	if( preg_match( "/^\//", $BL->setting['sysDefaultImage'] ) )
	{
		$my_head .= "<meta property=\"og:image\" content=\"" . NV_MY_DOMAIN . $BL->setting['sysDefaultImage'] . "\" />\n";
	}
	else
	{
		$my_head .= "<meta property=\"og:image\" content=\"" . $BL->setting['sysDefaultImage'] . "\" />\n";
	}
}
else
{
	$my_head .= "<meta property=\"og:image\" content=\"" . NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo'] . "\" />\n";
}

$my_head .= "<meta property=\"og:locale\" content=\"" . $BL->setting['sysLocale'] . "\" />\n";

$contents = nv_main_theme( $array, $generate_page, $BL->setting, $page, $total_pages, $BL );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';