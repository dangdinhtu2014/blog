<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

// Breadcrumbs
$array_mod_title[] = array(
	'catid' => 0,
	'title' => 'Tags',
	'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op,
);

// Xem chi tiết tags
if( isset( $array_op[1] ) )
{
	// Kiểm tra hợp chuẩn URL
	unset( $m );
	if( isset( $array_op[3] ) or ( isset( $array_op[2] ) and ! preg_match( "/^page\-([0-9]+)$/i", $array_op[2], $m ) ) )
	{
		header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
		die();
	}
	
	// Xác định số trang
	$page = 1;
	if( ! empty( $m ) )
	{
		$page = intval( $m[1] );
	}
	$per_page = intval( $BL->setting['numPostPerPage'] );
	
	// Lấy thông tin của tags
	$sql = "SELECT * FROM " . $BL->table_prefix . "_tags WHERE alias=" . $db->quote( $array_op[1] );
	$result = $db->query( $sql );
	
	if( $result->rowCount() != 1 ){
		header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
		die();
	}
	
	$array_tags = $result->fetch();
	
	// Xác định tiêu đề của trang
	$page_title = $mod_title = $array_tags['title'];
	
	if( ! empty( $array_tags['keywords'] ) )
	{
		$key_words = $array_tags['keywords'];
	}
	
	$description = $array_tags['description'];
	
	if( empty( $description ) )
	{
		$description = $BL->lang('detailDefDescription') . ' ' . $array_tags['title'];
	}
	
	// Thêm vào breadcrumbs
	$array_mod_title[] = array(
		'catid' => 1,
		'title' => $array_tags['title'],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . '/' . $array_tags['alias'],
	);
	
	// SQL lấy các bài viết
	$sql = "FROM " . $BL->table_prefix . "_rows WHERE status=1 AND (" . $BL->build_query_search_id( $array_tags['id'], 'tagids' ) . ")";
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . '/' . $array_tags['alias'];
	
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
		
		if( ! empty( $key_words ) )
		{
			$key_words .= ', ' . $BL->glang('page') . ' ' . $page;
		}
		
		$description .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $BL->glang('page') . ' ' . $page;
	}
	
	// Open Graph
	$meta_property['og:title'] = $page_title . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'];
	$meta_property['og:type'] = 'website';
	$meta_property['og:url'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . '/' . $array_tags['alias'], true );
	$meta_property['og:description'] = $description;
 
	
	if( ! empty( $BL->setting['sysDefaultImage'] ) )
	{
		if( preg_match( "/^\//", $BL->setting['sysDefaultImage'] ) )
		{
			$meta_property['og:image'] = NV_MY_DOMAIN . $BL->setting['sysDefaultImage'];	
		}
		else
		{
			$meta_property['og:image'] = $BL->setting['sysDefaultImage'];
		}
	}
	else
	{
		$meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo'];	
	}
	
	$contents = nv_detail_tags_theme( $array, $generate_page, $BL->setting, $page, $total_pages, $BL );
	
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}
// Tất cả các tags
else
{
	$page_title = $mod_title = $BL->lang('allTags');
	$key_words = $module_info['keywords'] . ', tags';
	$description = $module_info['description'] . ' tags';
	
	$sql = "SELECT id, title, alias, numposts FROM " . $BL->table_prefix . "_tags ORDER BY title ASC";
	$result = $db->query( $sql );
	
	$array = array();
	while( $row = $result->fetch() )
	{
		$array[$row['id']] = $row;
		$array[$row['id']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $row['alias'];
	}
		
	// Open Graph
	$my_head .= "<meta property=\"og:title\" content=\"" . $page_title . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'] . "\" />\n";
	$my_head .= "<meta property=\"og:type\" content=\"website\" />\n";
	$my_head .= "<meta property=\"og:url\" content=\"" . NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=tags", true ) . "\" />\n";
	$my_head .= "<meta property=\"og:description\" content=\"" . $description . "\" />\n";
	
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
	
	$contents = nv_all_tags_theme( $array, $BL );
	
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}