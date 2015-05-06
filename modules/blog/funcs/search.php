<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

$page_title = $mod_title = $BL->lang('search');

// Breadcrumbs
$array_mod_title[] = array(
	'catid' => 0,
	'title' => $BL->lang('search'),
	'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op,
);

$array = array(
	'q' => nv_substr( $nv_Request->get_title( 'q', 'get', '', NV_MIN_SEARCH_LENGTH ), 0, NV_MAX_SEARCH_LENGTH ),
	'catid' => $nv_Request->get_int( 'catid', 'get', 0 ),
	'contents' => array(),
);

// Phân trang
$page = $nv_Request->get_int( 'page', 'get', 1 );
$generate_page = '';
$total_pages = 0;
$all_page = 0;

// Chuyển đến trang xem theo theo mục nếu để trống từ khóa mà tìm theo danh mục
if( empty( $array['q'] ) and isset( $global_array_cat[$array['catid']] ) )
{
	header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $global_array_cat[$array['catid']]['alias'], true ) );
	die();
}

// Chỉnh lại đường dẫn cho phù hợp
if( $page < 1 or ( $page == 1 and $nv_Request->isset_request( 'page', 'get' ) ) or ( $nv_Request->isset_request( 'q', 'get' ) and empty( $array['q'] ) ) or ( empty( $array['q'] ) and isset( $_GET['catid'] ) ) or ( isset( $_GET['catid'] ) and ( ! is_numeric( $_GET['catid'] ) or ( ! isset( $global_array_cat[$array['catid']] ) and $array['catid'] != 0 ) ) ) )
{
	header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op, true ) );
	die();
}

// Tiêu đề nếu có từ khóa
if( ! empty( $array['q'] ) )
{
	$page_title = $BL->lang('searchAbout') . ' ' . $array['q'];
}

// Tiêu đề nếu có danh mục
if( isset( $_GET['catid'] ) )
{
	if( empty( $array['catid'] ) )
	{
		$page_title .= ' ' . $BL->lang('searchAllCat');
	}
	else
	{
		$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_array_cat[$array['catid']]['title'];
	}
}

// Xac dinh thong tin phan trang
$per_page = intval( $BL->setting['numSearchResult'] );

$array_userids = array();

// Dữ liệu tìm kiếm khi có từ khóa tìm kiếm
if( ! empty( $array['q'] ) )
{
	// Giá trị tìm được chuẩn hóa
	$sql_like = "LIKE '%" . $db->dblikeescape( $array['q'] ) . "%'";
	
	// SQL co ban
	$sql = "FROM " . $BL->table_prefix . "_rows WHERE status=1 AND ( title " . $sql_like . " OR keywords " . $sql_like . " OR hometext " . $sql_like . " OR bodytext " . $sql_like . " )" . ( $array['catid'] ? " AND ( " . $BL->build_query_search_id( $array['catid'], 'catids' ) . " )" : "" );
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;q=" . urlencode( $array['q'] ) . "&amp;catid=" . $array['catid'];
	
	// Lay so row
	$sql1 = "SELECT COUNT(*) " . $sql;
	$result1 = $db->query( $sql1 );
	$all_page = $result1->fetchColumn();
	
	// Lay du lieu
	$sql = "SELECT * " . $sql . " ORDER BY pubtime DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . ", " . $per_page;
	$result = $db->query( $sql );
	
	
	while( $row = $result->fetch() )
	{
		$row['mediatype'] = intval( $row['mediatype'] );
		$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
		$row['postName'] = '';
		
		$array['contents'][$row['id']] = $row;
		$array_userids[$row['postid']] = $row['postid'];
	}

	// Du lieu phan trang
	$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page,  true, '&amp;' );
 	$total_pages = ceil( $all_page / $per_page );
}

// Khong cho dat $page tuy y
if( $page > 1 and empty( $array['contents'] ) )
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
	
	foreach( $array['contents'] as $row )
	{
		if( isset( $array_userids[$row['postid']] ) )
		{
			$array['contents'][$row['id']]['postName'] = $array_userids[$row['postid']];
		}
	}
}

// Them vao tieu de neu phan trang
if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $BL->glang('page') . ' ' . $page;
}

// Open Graph
$meta_property['og:title'] = $page_title . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'];
$meta_property['og:type'] = 'website';
$meta_property['og:url'] = $client_info['selfurl'];
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

$contents = nv_search_theme( $array, $page, $total_pages, $all_page, $generate_page, $BL );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';