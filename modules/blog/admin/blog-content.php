<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Xu ly thu muc uploads
$username_alias = change_alias( $admin_info['username'] );
$array_structure_image = array();
$array_structure_image[''] = $module_name;
$array_structure_image['Y'] = $module_name . '/' . date( 'Y' );
$array_structure_image['Ym'] = $module_name . '/' . date( 'Y_m' );
$array_structure_image['Y_m'] = $module_name . '/' . date( 'Y/m' );
$array_structure_image['Ym_d'] = $module_name . '/' . date( 'Y_m/d' );
$array_structure_image['Y_m_d'] = $module_name . '/' . date( 'Y/m/d' );
$array_structure_image['username'] = $module_name . '/' . $username_alias;

$array_structure_image['username_Y'] = $module_name . '/' . $username_alias . '/' . date( 'Y' );
$array_structure_image['username_Ym'] = $module_name . '/' . $username_alias . '/' . date( 'Y_m' );
$array_structure_image['username_Y_m'] = $module_name . '/' . $username_alias . '/' . date( 'Y/m' );
$array_structure_image['username_Ym_d'] = $module_name . '/' . $username_alias . '/' . date( 'Y_m/d' );
$array_structure_image['username_Y_m_d'] = $module_name . '/' . $username_alias . '/' . date( 'Y/m/d' );

$currentpath = isset( $array_structure_image[$BL->setting['folderStructure']] ) ? $array_structure_image[$BL->setting['folderStructure']] : '';

if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $currentpath ) )
{
	$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
}
else
{
	$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_name;
	$e = explode( "/", $currentpath );
	if( ! empty( $e ) )
	{
		$cp = "";
		foreach( $e as $p )
		{
			if( ! empty( $p ) and ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
			{
				$mk = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
				nv_loadUploadDirList( false );
				if( $mk[0] > 0 )
				{
					$upload_real_dir_page = $mk[2];
				}
			}
			elseif( ! empty( $p ) )
			{
				$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
			}
			$cp .= $p . '/';
		}
	}
	$upload_real_dir_page = str_replace( "\\", "/", $upload_real_dir_page );
}

$currentpath = str_replace( NV_ROOTDIR . "/", "", $upload_real_dir_page );

// Goi js
$BL->callFrameWorks( 'ui.sortable', 'tipsy', 'autosize', 'ui.autocomplete', 'ui.datepicker', 'shadowbox' );

$page_title = $BL->lang('blogManager');

// Lay va khoi tao cac bien
$error = "";
$complete = false;
$id = $nv_Request->get_int( 'id', 'get, post', 0 );

// Xu ly
if( $id )
{
	$sql = "SELECT * FROM " . $BL->table_prefix . "_rows WHERE id=" . $id;
	$result = $db->query( $sql );
	
	if( $result->rowCount() != 1 )
	{
		nv_info_die( $BL->glang('error_404_title'), $BL->glang('error_404_title'), $BL->glang('error_404_content') );
	}
	
	$row = $result->fetch();
	
	$array_old = $array = array(
		"postid" => ( int ) $row['postid'],
		"postgoogleid" => $row['postgoogleid'],
		"sitetitle" => $row['sitetitle'],
		"title" => $row['title'],
		"alias" => $row['alias'],
		"keywords" => $row['keywords'],
		"images" => $row['images'],
		"mediatype" => ( int ) $row['mediatype'],
		"mediaheight" => ( int ) $row['mediaheight'],
		"mediavalue" => $row['mediavalue'],
		"hometext" => nv_br2nl( $row['hometext'] ),
		"bodytext" => $row['bodytext'],
		"bodyhtml" => '',
		"posttype" => ( int ) $row['posttype'],
		"fullpage" => ( int ) $row['fullpage'],
		"inhome" => ( int ) $row['inhome'],
		"catids" => $BL->string2array( $row['catids'] ),
		"tagids" => $BL->string2array( $row['tagids'] ),
		"numwords" => ( int ) $row['numwords'],
		"pubtime" => ( int ) $row['pubtime'],
		"pubtime_h" => date( "G", $row['pubtime'] ),
		"pubtime_m" => ( int ) date( "i", $row['pubtime'] ),
		"exptime" => ( int ) $row['exptime'],
		"exptime_h" => $row['exptime'] ? date( "G", $row['exptime'] ) : 0,
		"exptime_m" => $row['exptime'] ? ( int ) date( "i", $row['exptime'] ) : 0,
		"expmode" => ( int ) $row['expmode'],
		"status" => ( int ) $row['status'],
	);
	
	$sql = "SELECT * FROM " . $BL->table_prefix . "_data_" . ceil( $id / 4000 ) . " WHERE id=" . $id;
	$result = $db->query( $sql );
	
	if( $result->rowCount() )
	{
		$row = $result->fetch();
		$array_old['bodyhtml'] = $array['bodyhtml'] = $row['bodyhtml'];
	}
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $BL->lang('blogEdit');
	
	// Gui email den cac email dang ky nhan tin
	$newsletters = 0;
	$isAutoKeywords = 0;
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $BL->lang('blogAdd');
	
	$array = array(
		"postid" => $admin_info['userid'],
		"postgoogleid" => $BL->setting['sysGoogleAuthor'],
		"sitetitle" => '',
		"title" => '',
		"alias" => '',
		"keywords" => '',
		"images" => '',
		"mediatype" => $BL->setting['initMediaType'],
		"mediaheight" => $BL->setting['initMediaHeight'],
		"mediavalue" => '',
		"hometext" => '',
		"bodytext" => '',
		"bodyhtml" => '',
		"posttype" => $BL->setting['initPostType'],
		"fullpage" => 0,
		"inhome" => 1,
		"catids" => array(),
		"tagids" => array(),
		"numwords" => 0,
		"pubtime" => NV_CURRENTTIME,
		"pubtime_h" => date( "G", NV_CURRENTTIME ),
		"pubtime_m" => ( int ) date( "i", NV_CURRENTTIME ),
		"exptime" => 0,
		"exptime_h" => 0,
		"exptime_m" => 0,
		"expmode" => $BL->setting['initPostExp'],
		"status" => -2,
	);
	
	// Gui email den cac email dang ky nhan tin
	$newsletters = $BL->setting['initNewsletters'];
	
	// Tự động xác định từ khóa
	$isAutoKeywords = $BL->setting['initAutoKeywords'];
}

// Thao tac xu ly
$prosessMode = "none";
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$prosessMode = "public";
}
elseif( $nv_Request->isset_request( 'draft', 'post' ) )
{
	$prosessMode = "draft";
}

// Xu ly khi submit
if( $prosessMode != 'none' )
{
	$array['postgoogleid'] = nv_substr( $nv_Request->get_title( 'postgoogleid', 'post', '', 1 ), 0, 255 );
	$array['sitetitle'] = nv_substr( $nv_Request->get_title( 'sitetitle', 'post', '', 1 ), 0, 255 );
	$array['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$array['alias'] = nv_substr( $nv_Request->get_title( 'alias', 'post', '', 1 ), 0, 255 );
	$array['keywords'] = nv_substr( $nv_Request->get_title( 'keywords', 'post', '', 1 ), 0, 255 );
	$array['images'] = $nv_Request->get_string( 'images', 'post', '' );
	$array['mediatype'] = $nv_Request->get_int( 'mediatype', 'post', 0 );
	$array['mediaheight'] = $nv_Request->get_int( 'mediaheight', 'post', 0 );
	$array['mediavalue'] = $nv_Request->get_string( 'mediavalue', 'post', '' );
	$array['hometext'] = $nv_Request->get_textarea( 'hometext', '', NV_ALLOWED_HTML_TAGS );
	$array['bodyhtml'] = $nv_Request->get_editor( 'bodyhtml', '', NV_ALLOWED_HTML_TAGS );
	$array['posttype'] = $nv_Request->get_int( 'posttype', 'post', 0 );
	$array['fullpage'] = $nv_Request->get_int( 'fullpage', 'post', 0 );
	$array['inhome'] = $nv_Request->get_int( 'inhome', 'post', 0 );
	$array['catids'] = $nv_Request->get_typed_array( 'catids', 'post', 'int' );
	$array['tagids'] = nv_substr( $nv_Request->get_title( 'tagids', 'post', '', 1 ), 0, 255 );
	$array['pubtime'] = $nv_Request->get_string( 'pubtime', 'post', '' );
	$array['pubtime_h'] = $nv_Request->get_int( 'pubtime_h', 'post', 0 );
	$array['pubtime_m'] = $nv_Request->get_int( 'pubtime_m', 'post', 0 );
	$array['exptime'] = $nv_Request->get_string( 'exptime', 'post', '' );
	$array['exptime_h'] = $nv_Request->get_int( 'exptime_h', 'post', 0 );
	$array['exptime_m'] = $nv_Request->get_int( 'exptime_m', 'post', 0 );
	$array['expmode'] = $nv_Request->get_int( 'expmode', 'post', 0 );
	
	$newsletters = $nv_Request->get_int( 'newsletters', 'post', 0 );
	$isAutoKeywords = $nv_Request->get_int( 'isAutoKeywords', 'post', 0 );
	
	// Chuẩn hóa google author
	if( ! preg_match( "/^([0-9]{1,30})$/", $array['postgoogleid'] ) )
	{
		$array['postgoogleid'] = $BL->setting['sysGoogleAuthor'];
	}
	
	// Tự động lấy từ khóa
	if( ! empty( $isAutoKeywords ) and empty( $array['keywords'] ) )
	{
		$array['keywords'] = nv_get_keywords( $array['bodyhtml'] );
	}
	
	// Chuan hoa tu khoa
	$array['keywords'] = $array['keywords'] ? implode( ", ", array_filter( array_unique( array_map( "trim", explode( ",", $array['keywords'] ) ) ) ) ) : "";
	
	// Chinh duong dan anh
	if( ! empty( $array['images'] ) )
	{
		if( preg_match( "/^\//i", $array['images'] ) )
		{
			$array['images'] = substr( $array['images'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name ) );
		}
	}
	
	// Chinh duong dan media
	if( ! empty( $array['mediavalue'] ) )
	{
		if( preg_match( "/^\//i", $array['mediavalue'] ) )
		{
			$array['mediavalue'] = substr( $array['mediavalue'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name ) );
		}
	}
	
	// Chuan hoa, khoi tao lien ket tinh
	if( ! empty( $array['alias'] ) )
	{
		$array['alias'] = strtolower( change_alias( $array['alias'] ) );
	}
	else
	{
		if( ! empty( $array['title'] ) )
		{
			$array['alias'] = strtolower( change_alias( $array['title'] ) );
		}
		elseif( $prosessMode == "draft" )
		{
			$array['alias'] = "draft";
		}
	}
	
	// Tao bodytext
	$array['bodytext'] = trim( nv_nl2br( strip_tags( $array['bodyhtml'] ), " " ) );
	
	// Chuan hoa loai bai viet
	if( ! in_array( $array['posttype'], $BL->blogposttype ) )
	{
		$array['posttype'] = 0;
	}
	
	// Chuan hoa loai media
	if( ! in_array( $array['mediatype'], $BL->blogMediaType ) )
	{
		$array['mediatype'] = 0;
	}
	
	// Thay doi danh muc, tags string => array
	$array['tagids'] = $BL->string2array( $array['tagids'] );
	
	// Chuan hoa va xu ly thoi gian
	if( $array['pubtime_h'] > 23 or $array['pubtime_h'] < 0 )
	{
		$array['pubtime_h'] = 0;
	}
	if( $array['pubtime_m'] > 59 or $array['pubtime_m'] < 0 )
	{
		$array['pubtime_m'] = 0;
	}
	if( $array['exptime_h'] > 23 or $array['exptime_h'] < 0 )
	{
		$array['exptime_h'] = 0;
	}
	if( $array['exptime_m'] > 59 or $array['exptime_m'] < 0 )
	{
		$array['exptime_m'] = 0;
	}
	
	if( preg_match( "/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $array['pubtime'], $m ) )
	{
		$array['pubtime'] = mktime( $array['pubtime_h'], $array['pubtime_m'], 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array['pubtime'] = NV_CURRENTTIME;
	}
	if( preg_match( "/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $array['exptime'], $m ) )
	{
		$array['exptime'] = mktime( $array['exptime_h'], $array['exptime_m'], 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array['exptime'] = 0;
	}
	
	// Chuan hoa kieu xu ly khi het han
	if( ! in_array( $array['expmode'], $BL->blogExpMode ) )
	{
		$array['expmode'] = 0;
	}
	
	// Chuẩn hóa giá trị 0, 1
	$array['fullpage'] = $array['fullpage'] ? 1 : 0;
	$array['inhome'] = $array['inhome'] ? 1 : 0;
	
	// Kiem tra loi, khong kiem tra neu luu ban nhap
	if( $prosessMode == "public" )
	{
		if( empty( $array['title'] ) )
		{
			$error = $BL->lang('blogErrorTitle');
		}
		elseif( empty( $array['keywords'] ) )
		{
			$error = $BL->lang('errorKeywords');
		}
		elseif( empty( $array['hometext'] ) )
		{
			$error = $BL->lang('blogErrorHometext');
		}
		elseif( empty( $array['bodytext'] ) )
		{
			$error = $BL->lang('blogErrorBodyhtml');
		}
		elseif( empty( $array['catids'] ) )
		{
			$error = $BL->lang('blogErrorCategories');
		}
		elseif( ! empty( $array['exptime'] ) and $array['exptime'] <= $array['pubtime'] )
		{
			$error = $BL->lang('blogErrorExpThanPub');
		}
		elseif( ! empty( $array['exptime'] ) and $array['exptime'] <= NV_CURRENTTIME and $array['expmode'] == 2 )
		{
			$error = $BL->lang('blogErrorExp');
		}
		elseif( ! empty( $array['mediatype'] ) and empty( $array['mediavalue'] ) )
		{
			$error = $BL->lang('blogErrorMediaValue');
		}
		elseif( $array['mediatype'] > 1 and empty( $array['mediaheight'] ) )
		{
			$error = $BL->lang('blogErrorMediaHeight');
		}
	}
	
	// Kiem tra lien ket tinh ton tai va tao lien ket tinh khac neu la luu ban nhap
	$sql = "SELECT * FROM " . $BL->table_prefix . "_rows WHERE alias=" . $db->quote( $array['alias'] ) . ( ! empty( $id ) ? " AND id!=" . $id : "" );
	$result = $db->query( $sql );
	
	if( $result->rowCount() )
	{
		// Neu la xuat ban thi bao loi ton tai
		if( $prosessMode == "public" )
		{
			$error = $BL->lang('errorAliasExists');
		}
		// Tao lien ket tinh khac
		else
		{
			$array['alias'] = $BL->creatAlias( $array['alias'], 'post' );
		}
	}
	
	// Xac dinh status
	if( $prosessMode == "draft" )
	{
		$array['status'] = -2;
	}
	// Bai viet het han
	elseif( $array['exptime'] <= NV_CURRENTTIME and ! empty( $array['exptime'] ) )
	{
		$array['status'] = $array['expmode'] == 0 ? 0 : 2;
	}
	// Bai viet cho dang
	elseif( $array['pubtime'] > NV_CURRENTTIME )
	{
		// Tao bai viet thi -1
		if( empty( $id ) )
		{
			$array['status'] = -1;
		}
		// Sua bai viet
		else
		{
			// Neu khong bi dung thi cho dang
			if( ! in_array( $array_old['status'], array( 0 ) ) )
			{
				$array['status'] = -1;
			}
			// Bi dung thi tiep tuc dung, nhap thi tiep tuc nhap
			else
			{
				$array['status'] = $array_old['status'];
			}
		}
	}
	else
	{
		// Tao bai viet thi 1
		if( empty( $id ) )
		{
			$array['status'] = 1;
		}
		// Sua bai viet
		else
		{
			// Neu khong bi dung thi cho dang
			if( ! in_array( $array_old['status'], array( 0 ) ) )
			{
				$array['status'] = 1;
			}
			// Bi dung thi tiep tuc dung, nhap thi tiep tuc nhap
			else
			{
				$array['status'] = $array_old['status'];
			}
		}
	}
	
	if( empty( $error ) )
	{
		$array['hometext'] = nv_nl2br( $array['hometext'] );
		
		if( empty( $id ) )
		{
			$sql = "INSERT INTO " . $BL->table_prefix . "_rows VALUES(
				NULL,
				" . $array['postid'] . ", 
				" . $db->quote( $array['postgoogleid'] ) . ",
				" . $db->quote( $array['sitetitle'] ) . ",
				" . $db->quote( $array['title'] ) . ",
				" . $db->quote( $array['alias'] ) . ",
				" . $db->quote( $array['keywords'] ) . ",
				" . $db->quote( $array['images'] ) . ",
				" . $array['mediatype'] . ", 
				" . $array['mediaheight'] . ", 
				" . $db->quote( $array['mediavalue'] ) . ",
				" . $db->quote( $array['hometext'] ) . ",
				" . $db->quote( $array['bodytext'] ) . ",
				" . $array['posttype'] . ", 
				" . $array['fullpage'] . ", 
				" . $array['inhome'] . ", 
				" . $db->quote( $array['catids'] ? "0," . implode( ",", $array['catids'] ) . ",0" : "" ) . ",
				" . $db->quote( $array['tagids'] ? "0," . implode( ",", $array['tagids'] ) . ",0" : "" ) . ",
				" . $array['numwords'] . ", 
				0, 0, 0, 0, '', 
				" . NV_CURRENTTIME . ", 
				" . NV_CURRENTTIME . ", 
				" . $array['pubtime'] . ", 
				" . $array['exptime'] . ", 
				" . $array['expmode'] . ", 
				" . $array['status'] . "
			)";
			
			$id = $db->insert_id( $sql );
			
			if( $id )
			{
				// Tao bang HTML
				$html_table = $BL->table_prefix . "_data_" . ceil( $id / 4000 );
				
				$sql = "CREATE TABLE IF NOT EXISTS " . $html_table . " (
					id mediumint(8) unsigned NOT NULL, 
					bodyhtml longtext NOT NULL,
					PRIMARY KEY  (id) 
				) ENGINE=MyISAM";
				
				if( ! $db->query( $sql ) and $prosessMode != "draft" )
				{
					$error = $BL->lang('blogErrorCreatTable');
				}
				
				// Luu noi dung bodyhtml vao
				$sql = "INSERT INTO " . $html_table . " VALUES( " . $id . ", " . $db->quote( $array['bodyhtml'] ) . " )";
				if( ! $db->query( $sql ) and $prosessMode != "draft" )
				{
					$error = $BL->lang('blogErrorSaveHtml');
				}
				
				if( empty( $error ) )
				{
					$complete = true;
					
					if( $prosessMode != "draft" )
					{
						// Cap nhat danh muc
						$BL->fixCat( $array['catids'] );
						
						// Cap nhat tags
						$BL->fixTags( $array['tagids'] );
						
						// Gui newsletters
						if( ! empty( $newsletters ) )
						{
							$sql = "INSERT INTO " . $BL->table_prefix . "_send VALUES( NULL, " . $id . ", 0, 0, 0, 1, 0, '', '' )";
							$db->query( $sql );
						}
						
						// Xoa cache
						nv_del_moduleCache( $module_name );
					}
					
					// Xu ly tin
					$BL->executeData();
				}
			}
			else
			{
				$error = $BL->lang('errorSaveUnknow');
			}
		}
		else
		{
			$sql = "UPDATE " . $BL->table_prefix ."_rows SET 
				postid=" . $array['postid'] . ", 
				postgoogleid=" . $db->quote( $array['postgoogleid'] ) . ",
				sitetitle=" . $db->quote( $array['sitetitle'] ) . ",
				title=" . $db->quote( $array['title'] ) . ",
				alias=" . $db->quote( $array['alias'] ) . ",
				keywords=" . $db->quote( $array['keywords'] ) . ",
				images=" . $db->quote( $array['images'] ) . ",
				mediatype=" . $array['mediatype'] . ", 
				mediaheight=" . $array['mediaheight'] . ", 
				mediavalue=" . $db->quote( $array['mediavalue'] ) . ",
				hometext=" . $db->quote( $array['hometext'] ) . ",
				bodytext=" . $db->quote( $array['bodytext'] ) . ",
				posttype=" . $array['posttype'] . ", 
				fullpage=" . $array['fullpage'] . ", 
				inhome=" . $array['inhome'] . ", 
				catids=" . $db->quote( $array['catids'] ? "0," . implode( ",", $array['catids'] ) . ",0" : "" ) . ",
				tagids=" . $db->quote( $array['tagids'] ? "0," . implode( ",", $array['tagids'] ) . ",0" : "" ) . ",
				numwords=" . $array['numwords'] . ", 
				updatetime=" . NV_CURRENTTIME . ", 
				pubtime=" . $array['pubtime'] . ", 
				exptime=" . $array['exptime'] . ", 
				expmode=" . $array['expmode'] . ", 
				status=" . $array['status'] . "
			WHERE id=" . $id;
			
			if( $db->query( $sql ) )
			{
				$html_table = $BL->table_prefix . "_data_" . ceil( $id / 4000 );
				
				// Luu noi dung bodyhtml vao
				$sql = "UPDATE " . $html_table . " SET bodyhtml=" . $db->quote( $array['bodyhtml'] ) . " WHERE id=" . $id;
				
				if( ! $db->query( $sql ) and $prosessMode != "draft" )
				{
					$error = $BL->lang('blogErrorUpdateHtml');
				}
				
				if( empty( $error ) )
				{
					$complete = true;
					
					if( $prosessMode != "draft" )
					{
						// Cap nhat danh muc
						$BL->fixCat( array_unique( array_filter( array_merge_recursive( $array_old['catids'], $array['catids'] ) ) ) );
						
						// Cap nhat tags
						$BL->fixTags( array_unique( array_filter( array_merge_recursive( $array_old['tagids'], $array['tagids'] ) ) ) );
						
						// Xoa cache
						nv_del_moduleCache( $module_name );
					}
					
					// Xu ly tin
					$BL->executeData();
				}
			}
			else
			{
				$error = $BL->lang('errorUpdateUnknow');
			}
		}
	}
}

// Sua lai noi dung
if( ! empty( $array['hometext'] ) ) $array['hometext'] = nv_htmlspecialchars( $array['hometext'] );
$array['bodyhtml'] = htmlspecialchars( nv_editor_br2nl( $array['bodyhtml'] ) );
// Trinh soan thao
if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$array['bodyhtml'] = nv_aleditor( 'bodyhtml', '100%', '500px', $array['bodyhtml'] );
}
else
{
	$array['bodyhtml'] = "<textarea style=\"width:100%; height:500px\" name=\"bodyhtml\" id=\"bodyhtml\">" . $array['bodyhtml'] . "</textarea>";
}

// Sua lai gio tu so thanh text
$array['pubtime'] = $array['pubtime'] ? date( "d/m/Y", $array['pubtime'] ) : "";
$array['exptime'] = $array['exptime'] ? date( "d/m/Y", $array['exptime'] ) : "";

// Chuyen so thanh chuoi
if( empty( $array['mediaheight'] ) )
{
	$array['mediaheight'] = "";
}

// Chinh duong dan anh
if( ! empty( $array['images'] ) and preg_match( "/^\//i", $array['images'] ) )
{
	$array['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . $array['images'];
}

// Chinh duong dan media
if( ! empty( $array['mediavalue'] ) and preg_match( "/^\//i", $array['mediavalue'] ) )
{
	$array['mediavalue'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . $array['mediavalue'];
}

$xtpl = new XTemplate( "blog-content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->assign( 'ID', $id );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TAGIDS', implode( ",", $array['tagids'] ) );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'EDITOR', ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) ) ? "true" : "false" );

// Tra ve JSON neu luu ban nhap
if( $prosessMode == "draft" )
{
	$contents = array(
		"error" => empty( $error ) ? 0 : 1,
		"message" => $complete ? $BL->lang('blogSaveDraftOk') : $error,
		"id" => $id,
	);
	
	include NV_ROOTDIR . '/includes/header.php';
	echo json_encode( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}

// Neu la xuat ban thanh cong
if( $complete )
{
	$my_head = "<meta http-equiv=\"refresh\" content=\"3;url=" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=blog-list\" />";
	
	$xtpl->assign( 'MESSAGE', $BL->lang('blogSaveOk') );

	$xtpl->parse( 'complete' );
	$contents = $xtpl->text( 'complete' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}

// Xuat danh muc
$list_cats = $BL->listCat( 0, 0 );

foreach( $list_cats as $cat )
{
	$cat['checked'] = in_array( $cat['id'], $array['catids'] ) ? " checked=\"checked\"" : "";

	$xtpl->assign( 'CAT', $cat );
	$xtpl->parse( 'main.cat' );
}

// Xuat loai bai viet
foreach( $BL->blogposttype as $posttype )
{
	$posttype = array(
		"key" => $posttype,
		"title" => $BL->lang('blogposttype' . $posttype),
		"checked" => $posttype == $array['posttype'] ? " checked=\"checked\"" : "",
	);

	$xtpl->assign( 'POSTTYPE', $posttype );
	$xtpl->parse( 'main.posttype' );
}

// Xuat gio
for( $i = 0; $i <= 23; $i ++ )
{
	$hour = array(
		"key" => $i,
		"title" => str_pad( $i, 2, "0", STR_PAD_LEFT ),
		"pub" => $i == $array['pubtime_h'] ? " selected=\"selected\"" : "",
		"exp" => $i == $array['exptime_h'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'HOUR', $hour );
	
	$xtpl->parse( 'main.pubtime_h' );
	$xtpl->parse( 'main.exptime_h' );
}

// Xuat phut
for( $i = 0; $i <= 59; $i ++ )
{
	$min = array(
		"key" => $i,
		"title" => str_pad( $i, 2, "0", STR_PAD_LEFT ),
		"pub" => $i == $array['pubtime_m'] ? " selected=\"selected\"" : "",
		"exp" => $i == $array['exptime_m'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'MIN', $min );
	
	$xtpl->parse( 'main.pubtime_m' );
	$xtpl->parse( 'main.exptime_m' );
}

// Xuat thao tac sau khi het han
foreach( $BL->blogExpMode as $expmode )
{
	$expmode = array(
		"key" => $expmode,
		"title" => $BL->lang('blogExpMode_' . $expmode),
		"selected" => $expmode == $array['expmode'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'EXPMODE', $expmode );
	$xtpl->parse( 'main.expmode' );
}

// Lay mot so tag co so bai viet nhieu
$sql = "SELECT * FROM " . $BL->table_prefix . "_tags ORDER BY numposts DESC LIMIT 0, 10";
$result = $db->query( $sql );

if( $result->rowCount() )
{
	while( $row = $result->fetch() )
	{
		$xtpl->assign( 'MOSTTAGS', $row );
		$xtpl->parse( 'main.mostTags.loop' );
	}
	
	$xtpl->parse( 'main.mostTags' );
}

// Xuat tags da chon
$tags = $BL->getTagsByID( $array['tagids'], true );
if( ! empty( $tags ) )
{
	foreach( $tags as $tag )
	{
		$xtpl->assign( 'TAG', $tag );
		$xtpl->parse( 'main.tag' );
	}
}

// Xuat loi
if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

// Xuat kieu media
foreach( $BL->blogMediaType as $mediatype )
{
	$mediatype = array(
		"key" => $mediatype,
		"title" => $BL->lang('blogmediatype' . $mediatype),
		"selected" => $mediatype == $array['mediatype'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'MEDIATYPE', $mediatype );
	$xtpl->parse( 'main.mediatype' );
}

// Bien chon media
$xtpl->assign( 'UPLOADS_PATH', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'CURRENT_PATH', $currentpath );

// Cac checkbox
$xtpl->assign( 'NEWSLETTERS', $newsletters ? " checked=\"checked\"" : "" );
$xtpl->assign( 'ISAUTOKEYWORDS', $isAutoKeywords ? " checked=\"checked\"" : "" );
$xtpl->assign( 'FULLPAGE', $array['fullpage'] ? " checked=\"checked\"" : "" );
$xtpl->assign( 'INHOME', $array['inhome'] ? " checked=\"checked\"" : "" );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';