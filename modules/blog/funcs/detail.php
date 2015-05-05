<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

if( empty( $blog_data ) )
{
	header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	die();
}

// Chỉ có phần xem chi tiết bài viết mới có thể .html
$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $blog_data['alias'] . $global_config['rewrite_exturl'], true );
if( $_SERVER['REQUEST_URI'] != $base_url_rewrite )
{
	Header( 'Location: ' . $base_url_rewrite );
	die();
}

$page_title = $mod_title = $blog_data['sitetitle'];
$key_words = $blog_data['keywords'];
$description = $blog_data['hometext'];

// Lấy nội dung html của bài viết
$sql = "SELECT bodyhtml FROM " . $BL->table_prefix . "_data_" . ceil( $blog_data['id'] / 4000 ) . " WHERE id=" . $blog_data['id'];
$result = $db->query( $sql );

if( $result->rowCount() )
{
	list( $blog_data['bodyhtml'] ) = $result->fetch( 3 );
}

// Lấy tags bài viết
$blog_data['tags'] = $BL->getTagsByID( $blog_data['tagids'], true );

// Lấy bài viết tiếp theo
$blog_data['nextPost'] = array();
$sql = "SELECT title, alias FROM " . $BL->table_prefix . "_rows WHERE status=1 AND ( " . $BL->build_query_search_id( $catid, 'catids' ) . " ) AND pubtime>" . $blog_data['pubtime'] . " ORDER BY pubtime ASC LIMIT 1";
$result = $db->query( $sql );

if( $result->rowCount() )
{
	$blog_data['nextPost'] = $result->fetch( PDO::FETCH_ASSOC );
	$blog_data['nextPost']['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $blog_data['nextPost']['alias'] . $global_config['rewrite_exturl'];
}

// Lấy bài viết trước đó
$blog_data['prevPost'] = array();
$sql = "SELECT title, alias FROM " . $BL->table_prefix . "_rows WHERE status=1 AND ( " . $BL->build_query_search_id( $catid, 'catids' ) . " ) AND pubtime<" . $blog_data['pubtime'] . " ORDER BY pubtime DESC LIMIT 1";
$result = $db->query( $sql );

if( $result->rowCount() )
{
	$blog_data['prevPost'] = $result->fetch( PDO::FETCH_ASSOC );
	$blog_data['prevPost']['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $blog_data['prevPost']['alias'] . $global_config['rewrite_exturl'];
}

// Url chính xác của bài đăng
$blog_data['href'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $blog_data['alias'] . $global_config['rewrite_exturl'], true );

// Open Graph
$meta_property['og:title'] = $page_title . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'];
$meta_property['og:type'] = 'article';
$meta_property['og:url'] = $blog_data['href'];
$meta_property['og:description'] = $description;
$meta_property['article:published_time'] = date( "Y-m-d", $blog_data['pubtime'] );
$meta_property['article:section'] = $global_array_cat[$catid]['title'];

 
// Khai báo thời gian cập nhật nếu bài đăng được cập nhật
if( $blog_data['pubtime'] != $blog_data['updatetime'] )
{
	$meta_property['article:modified_time'] = date( "Y-m-d", $blog_data['updatetime'] );	
 }

// Khai báo thời gian hết hạn nếu bài đăng hết hạn
if( ! empty( $blog_data['exptime'] ) )
{
	$meta_property['article:expiration_time'] = date( "Y-m-d", $blog_data['exptime'] );		
}

// Từ khóa bài đăng
if( ! empty( $blog_data['keywords'] ) )
{
	$keywords = array_map( 'trim', array_map( 'nv_strtolower', array_unique( array_filter( explode( ',', $blog_data['keywords'] ) ) ) ) );
	
	if( ! empty( $keywords ) )
	{
		arsort( $keywords );
		
		foreach( $keywords as $keyword )
		{
			$meta_property['article:tag'] = $keyword;			
		}
	}
}

if( ! empty( $blog_data['mediavalue'] ) )
{
	// Âm thanh
	if( $blog_data['mediatype'] == 2 )
	{
		$meta_property['og:audio'] = ( preg_match( "/^\//", $blog_data['mediavalue'] ) ? NV_MY_DOMAIN . $blog_data['mediavalue'] : $blog_data['mediavalue'] ) ;		
 	}
	// Video
	elseif( $blog_data['mediatype'] == 3 )
	{
		$meta_property['og:video'] = ( preg_match( "/^\//", $blog_data['mediavalue'] ) ? NV_MY_DOMAIN . $blog_data['mediavalue'] : $blog_data['mediavalue'] );	
	}
}

// Hình ảnh bài đăng
if( ! empty( $blog_data['mediavalue'] ) and $blog_data['mediatype'] == 1 )
{
	if( preg_match( "/^\//", $blog_data['mediavalue'] ) )
	{
		$meta_property['og:image'] = NV_MY_DOMAIN . $blog_data['mediavalue'];	
 
	}
	else
	{	
		$meta_property['og:image'] = $blog_data['mediavalue'];	
	}
}
elseif( ! empty( $blog_data['images'] ) )
{
	if( preg_match( "/^\//", $blog_data['images'] ) )
	{
		$meta_property['og:image'] = NV_MY_DOMAIN . $blog_data['images'];		
 	}
	else
	{
		$meta_property['og:image'] = $blog_data['images'];
	}
}
elseif( ! empty( $BL->setting['sysDefaultImage'] ) )
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

$contents = nv_detail_theme( $blog_data, $BL );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';