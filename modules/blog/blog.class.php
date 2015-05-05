<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_mod_blog
 * 
 * @package Blog Phan Tan Dung
 * @author PHAN TAN DUNG
 * @copyright 2014
 * @version 1.0
 * @access public
 */
class nv_mod_blog
{
	public $author_email = 'phantandung92@gmail.com';
	
	private $lang_data = '';
	private $mod_data = '';
	private $mod_name = '';
	private $mod_file = '';
	private $db = null;
	
	public $db_prefix = '';
	public $db_prefix_lang = "";
	public $table_prefix = "";
	
	public $setting = null;
	
	public $indexViewType = array( "type_blog", "type_news" );
	public $catViewType = array( "type_blog", "type_news" );
	public $tagsViewType = array( "type_blog", "type_news" );
	public $blockTagsShowType = array( "random", "latest", "popular" );
	public $blogposttype = array( 0, 1, 2, 3, 4, 5, 6 );
	public $blogMediaType = array( 0, 1, 2, 3, 4 );
	public $blogExpMode = array( 0, 1, 2 );
	public $blogStatus = array( -2, -1, 0, 1, 2 );
	public $commentType = array( "none", "sys", "facebook", "disqus" );
	
	private $base_site_url = null;
	private $root_dir = null;
	private $upload_dir = null;
	private $currenttime = null;
	
	private $language = array();
	private $glanguage = array();
	
	private $frameworks_called = array();

	/**
	 * nv_mod_blog::__construct()
	 * 
	 * @param string $d
	 * @param string $n
	 * @param string $f
	 * @param string $lang
	 * @return
	 */
	public function __construct( $d = "", $n = "", $f = "", $lang = "" )
	{
		global $module_data, $module_name, $module_file, $db_config, $db, $lang_module, $lang_global;
		
		// Ten CSDL
		if( ! empty( $d ) ) $this->mod_data = $d;
		else $this->mod_data = $module_data;
		
		// Ten module
		if( ! empty( $n ) ) $this->mod_name = $n;
		else $this->mod_name = $module_name;
		
		// Ten thu muc
		if( ! empty( $f ) ) $this->mod_file = $f;
		else $this->mod_file = $module_file;
		
		// Ngon ngu
		if( ! empty( $lang ) ) $this->lang_data = $lang;
		else $this->lang_data = NV_LANG_DATA;
		
		$this->db_prefix = $db_config['prefix'];
		$this->db_prefix_lang = $this->db_prefix . '_' . $this->lang_data;
		$this->table_prefix = $this->db_prefix_lang . '_' . $this->mod_data;
		$this->db = $db;
		
		$this->setting = $this->get_setting();
			
		$this->base_site_url = NV_BASE_SITEURL;
		$this->root_dir = NV_ROOTDIR;
		$this->upload_dir = NV_UPLOADS_DIR;
		$this->language = $lang_module;
		$this->glanguage = $lang_global;
		$this->currenttime = NV_CURRENTTIME;
		
		// Chuẩn hóa cấu hình
		if( ! empty( $this->setting['sysDefaultImage'] ) )
		{
			if( preg_match( "/^\//i", $this->setting['sysDefaultImage'] ) )
			{
				$this->setting['sysDefaultImage'] = $this->upload_dir . "/" . $this->mod_name . $this->setting['sysDefaultImage'];
				
				if( is_file( $this->root_dir . '/' . $this->setting['sysDefaultImage'] ) )
				{
					$this->setting['sysDefaultImage'] = $this->base_site_url . $this->setting['sysDefaultImage'];
				}
				else
				{
					$this->setting['sysDefaultImage'] = '';
				}
			}
			elseif( ! nv_is_url( $this->setting['sysDefaultImage'] ) )
			{
				$this->setting['sysDefaultImage'] = '';
			}
		}
		
		// Check Execute Data
		if( ! empty( $this->setting['nextExecuteTime'] ) and $this->setting['nextExecuteTime'] <= $this->currenttime )
		{
			$this->executeData( true );
		}
	}
	
	/**
	 * nv_mod_blog::handle_error()
	 * 
	 * @param string $messgae
	 * @return
	 */
	private function handle_error( $messgae = '' )
	{
		trigger_error( "Error! " . ( $messgae ? ( string ) $messgae : "You are not allowed to access this feature now" ) . "!", 256 );
	}
	
	/**
	 * nv_mod_blog::check_admin()
	 * 
	 * @return
	 */
	private function check_admin()
	{
		if( ! defined( 'NV_IS_MODADMIN' ) ) $this->handle_error();
	}
	
	/**
	 * nv_mod_blog::nl2br()
	 * 
	 * @param mixed $string
	 * @return
	 */
	private function nl2br( $string )
	{
		return nv_nl2br( $string );
	}
	
	/**
	 * nv_mod_blog::db_cache()
	 * 
	 * @param mixed $sql
	 * @param string $id
	 * @param string $module_name
	 * @return
	 */
	private function db_cache( $sql, $id = '', $module_name = '' )
	{
		return nv_db_cache( $sql, $id, $module_name );
	}
	
	/**
	 * nv_mod_blog::del_cache()
	 * 
	 * @param mixed $module_name
	 * @return
	 */
	private function del_cache( $module_name )
	{
		return nv_del_moduleCache( $module_name );
	}
	
	/**
	 * nv_mod_blog::change_alias()
	 * 
	 * @param mixed $alias
	 * @return
	 */
	private function change_alias( $alias )
	{
		return change_alias( $alias );
	}
	
	/**
	 * nv_mod_blog::get_setting()
	 * 
	 * @return
	 */
	private function get_setting()
	{
		$sql = "SELECT config_name, config_value FROM " . $this->table_prefix . "_config";
		$result = $this->db_cache( $sql );
		
		$array = array();
		foreach ( $result as $values )
		{
			$array[$values['config_name']] = $values['config_value'];
		}

		return $array;
	}
	
	/**
	 * nv_mod_blog::findFrameWorks()
	 * 
	 * @param mixed $numargs
	 * @param mixed $list_args
	 * @param bool $allow_called
	 * @return
	 */
	private function findFrameWorks( $numargs, $list_args, $allow_called = false )
	{
		$frameWorks = '';
		
		if( $numargs > 0 )
		{
			for( $i = 0; $i < $numargs; $i ++ )
			{
				// Các frameworks trong thư mục frameworks của module
				if( ( is_dir( $this->root_dir . '/modules/' . $this->mod_file . '/frameworks/' . $list_args[$i] ) or preg_match( "/^ui\./i", $list_args[$i] ) ) and ( ! in_array( $list_args[$i], $this->frameworks_called ) or $allow_called === true ) )
				{
					// Highlight js
					if( $list_args[$i] == 'highlight' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						// Xác định giao diện
						$css_file = 'modules/' . $this->mod_file . '/frameworks/' . $list_args[$i] . '/styles/' . $this->setting['sysHighlightTheme'] . '.css';
						
						if( ! is_file( $this->root_dir . '/' . $css_file ) )
						{
							$css_file = $this->base_site_url . 'modules/' . $this->mod_file . '/frameworks/' . $list_args[$i] . '/styles/default.css';
						}
						else
						{
							$css_file = $this->base_site_url . $css_file;
						}
						
						$frameWorks .= "<link rel=\"stylesheet\" href=\"" . $css_file . "\"/>" . NV_EOL;
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/highlight/highlight.pack.js\"></script>\n";
						$frameWorks .= "<script type=\"text/javascript\">hljs.initHighlightingOnLoad();</script>" . NV_EOL;
					}
					elseif( $list_args[$i] == 'tipsy' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/tipsy/jquery.tipsy.js\"></script>\n";
						$frameWorks .= "<link type=\"text/css\" href=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/tipsy/tipsy.css\" rel=\"stylesheet\" />\n";
					}
					elseif( $list_args[$i] == 'autosize' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/autosize/jquery.autosize.js\"></script>\n";
					}
					elseif( $list_args[$i] == 'ui.autocomplete' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						$frameWorks .= $this->getFrameWorks( 'ui.core' );
						$frameWorks .= $this->getFrameWorks( 'ui.menu' );
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/ui/jquery.ui.autocomplete.js\"></script>\n";
					}
					elseif( $list_args[$i] == 'ui.core' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						$frameWorks .= "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
						$frameWorks .= "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.core.min.js\"></script>\n";		
					}
					elseif( $list_args[$i] == 'ui.sortable' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						$frameWorks .= $this->getFrameWorks( 'ui.core' );
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.sortable.min.js\"></script>\n";
					}
					elseif( $list_args[$i] == 'ui.datepicker' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						$frameWorks .= $this->getFrameWorks( 'ui.core' );
						$frameWorks .= "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";
					}
					elseif( $list_args[$i] == 'ui.menu' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						$frameWorks .= $this->getFrameWorks( 'ui.core' );
						$frameWorks .= "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.menu.css\" rel=\"stylesheet\" />\n";
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.menu.min.js\"></script>\n";
					}
				}
				// Các frameworks của hệ thống khác
				elseif( ! in_array( $list_args[$i], $this->frameworks_called ) or $allow_called === true )
				{
					if( $list_args[$i] == 'shadowbox' )
					{
						$this->frameworks_called[md5( $list_args[$i] )] = $list_args[$i];
						
						$frameWorks .= "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/shadowbox/shadowbox.js\"></script>\n";
						$frameWorks .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->base_site_url . "js/shadowbox/shadowbox.css\" />\n";
						$frameWorks .= "<script type=\"text/javascript\">Shadowbox.init();</script>\n";
					}
				}
			}
		}
		
		return $frameWorks;
	}
	
	/**
	 * nv_mod_blog::sortArrayFromArrayKeys()
	 * 
	 * @param mixed $keys
	 * @param mixed $array
	 * @return
	 */
	private function sortArrayFromArrayKeys( $keys, $array )
	{
		$return = array();
		
		foreach( $keys as $key )
		{
			if( isset( $array[$key] ) )
			{
				$return[$key] = $array[$key];
			}
		}
		return $return;
	}
	
	/**
	 * nv_mod_blog::IdHandle()
	 * 
	 * @param mixed $stroarr
	 * @param string $defis
	 * @return
	 */
	private function IdHandle( $stroarr, $defis = "," )
	{
		$return = array();
		
		if( is_array( $stroarr ) )
		{
			$return = array_filter( array_unique( array_map( "intval", $stroarr ) ) );
		}
		elseif( strpos( $stroarr, $defis ) !== false )
		{
			$return = array_map( "intval", $this->string2array( $stroarr, $defis ) );
		}
		else
		{
			$return = array( intval( $stroarr ) );
		}
		
		return $return;
	}
	
	/**
	 * nv_mod_blog::executeData()
	 * 
	 * @param bool $rmCache
	 * @return
	 */
	public function executeData( $rmCache = false )
	{
		// Cho dang nhung bai dang cho duyet
		$sql = "UPDATE " . $this->table_prefix . "_rows SET status=1 WHERE status=-1 AND pubtime<=" . $this->currenttime;
		$this->db->query( $sql );
		
		// Xu ly cac bai viet het han
		$sql = "SELECT id, expmode FROM " . $this->table_prefix . "_rows WHERE status=1 AND exptime!=0 AND exptime<=" . $this->currenttime;
		$result = $this->db->query( $sql );
		
		while( $row = $result->fetch() )
		{
			if( $row['expmode'] != 2 )
			{
				$status = $row['expmode'] == 0 ? 0 : 2;

				$sql = "UPDATE " . $this->table_prefix . "_rows SET status=" . $status . " WHERE id=" . $row['id'];
				$result = $this->db->query( $sql );
			}
			else
			{
				$this->delPost( $row['id'] );
			}
		}
		
		// Xac dinh lan thuc hien tiep theo
		$sql = "SELECT MIN(pubtime) AS nextpublic FROM " . $this->table_prefix . "_rows WHERE status=-1 AND pubtime>" . $this->currenttime;
		$result = $this->db->query( $sql );
		list( $nextpublic ) = $result->fetch(3);
		
		$sql = "SELECT MIN(exptime) AS nextexpire FROM " . $this->table_prefix . "_rows WHERE status=1 AND exptime>" . $this->currenttime;
		$result = $this->db->query( $sql );
		list( $nextexpire ) = $result->fetch(3);
		
		if( ! empty( $nextpublic ) or ! empty( $nextexpire ) )
		{
			if( empty( $nextpublic ) )
			{
				$nextime = intval( $nextexpire );
			}
			elseif( empty( $nextexpire ) )
			{
				$nextime = intval( $nextpublic );
			}
			elseif( $nextexpire > $nextpublic )
			{
				$nextime = intval( $nextpublic );
			}
			else
			{
				$nextime = intval( $nextexpire );
			}
		}
		else
		{
			$nextime = 0;
		}
		
		$sql = "UPDATE " . $this->table_prefix . "_config SET config_value='" . $nextime . "' WHERE config_name='nextExecuteTime'";
		$this->db->query( $sql );
		
		// Xoa cache neu co
		if( $rmCache )
		{
			$this->del_cache( $this->mod_name );
		}
	}
	
	/**
	 * nv_mod_blog::lang()
	 * 
	 * @param mixed $key
	 * @return
	 */
	public function lang( $key )
	{
		return isset( $this->language[$key] ) ? $this->language[$key] : $key;
	}
	
	/**
	 * nv_mod_blog::glang()
	 * 
	 * @param mixed $key
	 * @return
	 */
	public function glang( $key )
	{
		return isset( $this->glanguage[$key] ) ? $this->glanguage[$key] : $key;
	}
	
	/**
	 * nv_mod_blog::string2array()
	 * 
	 * @param mixed $str
	 * @param string $defis
	 * @param bool $unique
	 * @param bool $empty
	 * @return
	 */
	public function string2array( $str, $defis = ",", $unique = false, $empty = false )
	{
		if( empty( $str ) ) return array();
		
		$str = array_map( "trim", explode( ( string ) $defis, ( string ) $str ) );
		
		if( ! $unique )
		{
			$str = array_unique( $str );
		}
		
		if( ! $empty )
		{
			$str = array_filter( $str );
		}
		
		return $str;
	}
	
	/**
	 * nv_mod_blog::checkExistsAlias()
	 * 
	 * @param string $alias
	 * @param string $mode
	 * @param integer $id
	 * @return
	 */
	public function checkExistsAlias( $alias = "", $mode = "", $id = 0 )
	{
		$this->check_admin();
		
		if( $mode == "cat" )
		{
			$array_table_check = array( "_rows", "_categories" );
			$mode = "_categories";
		}
		elseif( $mode == "tags" )
		{
			$array_table_check = array( "_tags" );
			$mode = "_tags";
		}
		else
		{
			$array_table_check = array( "_rows", "_categories" );
			$mode = "_rows";
		}
		$id = intval( $id );
		
		foreach( $array_table_check as $table_check )
		{
			$sql = "SELECT * FROM " . $this->table_prefix . $table_check . " WHERE alias=" . $this->db->quote( $alias ) . ( ( $mode == $table_check and ! empty( $id ) ) ? " AND id!=" . $id : "" );
			$result = $this->db->query( $sql );
			if( $result->rowCount() ) return true;
		}

		return false;
	}
	
	/**
	 * nv_mod_blog::creatAlias()
	 * 
	 * @param mixed $title
	 * @param mixed $mode
	 * @return
	 */
	public function creatAlias( $title, $mode )
	{
		if( empty( $title ) ) return "";
		
		$aliasRoot = $alias = strtolower( $this->change_alias( $title ) );
		$aliasAdd = 0;
		
		if( $mode == 'tags' )
		{
			$array_fetch_table = array( "_tags" );
		}
		else
		{
			$array_fetch_table = array( "_rows", "_categories" );
		}
		
		foreach( $array_fetch_table as $table )
		{
			while( 1 )
			{
				$count = $this->db->query( "SELECT COUNT(*) FROM " . $this->table_prefix . $table . " WHERE alias=" . $this->db->quote( $alias ) )->fetchColumn();
				if( empty( $count ) ) break;
				
				if( preg_match( "/^(.*)\-(\d+)$/", $alias, $m ) )
				{
					$alias = $m[1] . "-" . ( $m[2] + 1 );
				}
				else
				{
					$alias = $aliasRoot . "-" . ( ++ $aliasAdd );
				}
			}
		}
		
		return $alias;
	}

	/**
	 * nv_mod_blog::setCats()
	 * 
	 * @param mixed $list2
	 * @param mixed $id
	 * @param mixed $list
	 * @param integer $m
	 * @param integer $num
	 * @return
	 */
	private function setCats( $list2, $id, $list, $m = 0, $num = 0 )
	{
		$num++;
		$defis = "";
		for( $i = 0; $i < $num; $i++ )
		{
			$defis .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}

		if( isset( $list[$id] ) )
		{
			foreach( $list[$id] as $value )
			{
				if( $value['id'] != $m )
				{
					$list2[$value['id']] = $value;
					$list2[$value['id']]['name'] = "" . $defis . "" . $list2[$value['id']]['name'];
					$list2[$value['id']]['defis'] = $defis;
					$list2[$value['parentid']]['subcats'][] = $value['id'];
					
					if( isset( $list[$value['id']] ) )
					{
						$list2 = $this->setCats( $list2, $value['id'], $list, $m, $num );
					}
				}
			}
		}
		
		return $list2;
	}
	
	/**
	 * nv_mod_blog::listCat()
	 * 
	 * @param mixed $parentid
	 * @param integer $m
	 * @return
	 */
	public function listCat( $parentid, $m = 0 )
	{
		if( defined('NV_ADMIN') )
		{
			$sql = "SELECT * FROM " . $this->table_prefix . "_categories ORDER BY parentid, weight ASC";
			$result = $this->db->query( $sql );
			
			$list = array();
			while( $row = $result->fetch() )
			{
				$list[$row['parentid']][] = array(
					'id' => ( int ) $row['id'],
					'parentid' => ( int ) $row['parentid'],
					'title' => $row['title'],
					'alias' => $row['alias'],
					'keywords' => $row['keywords'],
					'description' => $row['description'],
					'numsubs' => ( int ) $row['numsubs'],
					'numposts' => ( int ) $row['numposts'],
					'numpostsFormat' => number_format( $row['numposts'], 0, '.', '.' ),
					'weight' => ( int ) $row['weight'],
					'status' => ( int ) $row['status'],
					'name' => $row['title'],
					'defis' => "",
					'subcats' => array(),
					'selected' => $parentid == $row['id'] ? " selected=\"selected\"" : ""
				);
			}
		}
		else
		{
			$sql = "SELECT * FROM " . $this->table_prefix . "_categories WHERE status=1 ORDER BY parentid, weight ASC";
			$result = $this->db_cache( $sql, 'id', $this->mod_name );
			
			$list = $list1 = array();
			
			foreach( $result as $row )
			{
				if( empty( $row['parentid'] ) or isset( $list1[$row['parentid']] ) )
				{
					$list1[$row['id']] = $row['id'];
					
					$list[$row['parentid']][] = array(
						'id' => ( int ) $row['id'],
						'parentid' => ( int ) $row['parentid'],
						'title' => $row['title'],
						'alias' => $row['alias'],
						'keywords' => $row['keywords'],
						'description' => $row['description'],
						'numsubs' => ( int ) $row['numsubs'],
						'numposts' => ( int ) $row['numposts'],
						'numpostsFormat' => number_format( $row['numposts'], 0, '.', '.' ),
						'weight' => ( int ) $row['weight'],
						'status' => ( int ) $row['status'],
						'name' => $row['title'],
						'defis' => "",
						'subcats' => array(),
						'selected' => $parentid == $row['id'] ? " selected=\"selected\"" : ""
					);
				}
			}
			
			unset( $list1 );
		}		

		if( empty( $list ) ) return $list;
		
		$list2 = array();
		foreach( $list[0] as $value )
		{
			if( $value['id'] != $m )
			{
				$list2[$value['id']] = $value;				
				if( isset( $list[$value['id']] ) )
				{
					$list2 = $this->setCats( $list2, $value['id'], $list, $m );
				}
			}
		}

		return $list2;
	}
	
	/**
	 * nv_mod_blog::fixCat()
	 * 
	 * @param mixed $id
	 * @return
	 */
	public function fixCat( $id )
	{
		if( empty( $id ) ) return;
		
		$ids = $this->IdHandle( $id );
		
		foreach( $ids as $id )
		{
			// Lay thong tin cua danh muc
			$sql = "SELECT * FROM " . $this->table_prefix . "_categories WHERE id=" . $id;
			$result = $this->db->query( $sql );
			
			if( ! $result->rowCount() ) return;
			
			$row = $result->fetch( PDO::FETCH_ASSOC );
			
			// Cap nhat so bai viet
			$numposts = $this->db->query( "SELECT COUNT(*) FROM " . $this->table_prefix . "_rows WHERE " . $this->build_query_search_id( $id, 'catids' ) . "AND status=1" )->fetchColumn();
			$this->db->query( "UPDATE " . $this->table_prefix . "_categories SET numposts=" . $numposts . " WHERE id=" . $id );
			
			// Cap nhat so danh muc con
			$numsubs = $this->db->query( "SELECT COUNT(*) FROM " . $this->table_prefix . "_categories WHERE parentid=" . $id )->fetchColumn();
			$this->db->query( "UPDATE " . $this->table_prefix . "_categories SET numsubs=" . $numsubs . " WHERE id=" . $id );
			
			$this->fixCat( $row['parentid'] );
		}
		
		return;
	}
	
	/**
	 * nv_mod_blog::fixWeightCat()
	 * 
	 * @param integer $parentid
	 * @return
	 */
	public function fixWeightCat( $parentid = 0 )
	{
		$sql = "SELECT id FROM " . $this->table_prefix . "_categories WHERE parentid=" . $parentid . " ORDER BY weight ASC";
		$result = $this->db->query( $sql );
		
		$weight = 0;
		while( $row = $result->fetch() )
		{
			$weight ++;
			$this->db->query( "UPDATE " . $this->table_prefix . "_categories SET weight=" . $weight . " WHERE id=" . $row['id'] );
		}
		
		return;
	}
	
	/**
	 * nv_mod_blog::fixTags()
	 * 
	 * @param mixed $id
	 * @return
	 */
	public function fixTags( $id )
	{
		if( empty( $id ) ) return;
		
		$ids = $this->IdHandle( $id );
		
		foreach( $ids as $id )
		{
			// Lay thong tin cua tags
			$sql = "SELECT * FROM " . $this->table_prefix . "_tags WHERE id=" . $id;
			$result = $this->db->query( $sql );
			
			if( ! $result->rowCount() ) return;
			
			// Cap nhat so bai viet
			$numposts = $this->db->query( "SELECT COUNT(*) FROM " . $this->table_prefix . "_rows WHERE " . $this->build_query_search_id( $id, 'tagids' ) . " AND status=1" )->fetchColumn();
			$this->db->query( "UPDATE " . $this->table_prefix . "_tags SET numposts=" . $numposts . " WHERE id=" . $id );
		}
		
		return;
	}
	
	/**
	 * nv_mod_blog::getTagsByID()
	 * Lay tags tu id
	 * 
	 * @param mixed $id
	 * @param bool $sort
	 * @return
	 */
	public function getTagsByID( $id, $sort = false )
	{
		$id = $this->IdHandle( $id );
		
		if( empty( $id ) )
		{
			return array();
		}
		
		// Lay du lieu
		$tags = array();
		$result = $this->db->query( "SELECT * FROM " . $this->table_prefix . "_tags WHERE id IN(" . implode( ",", $id ) . ")" );
		
		while( $row = $result->fetch( PDO::FETCH_ASSOC ) )
		{
			$tags[$row['id']] = $row;
		}
		
		// Sap xep theo thu tu cua array
		if( $sort === true and sizeof( $tags ) > 1 )
		{
			$tags = $this->sortArrayFromArrayKeys( $id, $tags );
		}
		
		return $tags;
	}
	
	/**
	 * nv_mod_blog::getPostByID()
	 * Lay bai viet tu id
	 * 
	 * @param mixed $id
	 * @param bool $sort
	 * @return
	 */
	public function getPostByID( $id, $sort = false )
	{
		$id = $this->IdHandle( $id );
		
		if( empty( $id ) )
		{
			return array();
		}
		
		// Lay du lieu
		$posts = array();
		$result = $this->db->query( "SELECT * FROM " . $this->table_prefix . "_rows WHERE id IN(" . implode( ",", $id ) . ")" );
		
		while( $row = $result->fetch( PDO::FETCH_ASSOC ) )
		{
			$posts[$row['id']] = $row;
		}
		
		// Sap xep theo thu tu cua array
		if( $sort === true and sizeof( $posts ) > 1 )
		{
			$posts = $this->sortArrayFromArrayKeys( $id, $posts );
		}
		
		return $posts;
	}
	
	/**
	 * nv_mod_blog::build_query_search_id()
	 * 
	 * @param mixed $id
	 * @param mixed $field
	 * @param string $logic
	 * @return
	 */
	public function build_query_search_id( $id, $field, $logic = 'OR' )
	{
		if( empty( $id ) ) return $field . "=''";
		
		$id = $this->IdHandle( $id );
		
		$query = array();
		foreach( $id as $_id )
		{
			$query[] = $field . " LIKE '%," . $_id . ",%'";
		}
		$query = implode( " " . $logic . " ", $query );
		
		return $query;
	}
	
	/**
	 * nv_mod_blog::delPost()
	 * 
	 * @param mixed $id
	 * @return
	 */
	public function delPost( $id )
	{
		// Lay thong tin cac bai viet
		$posts = $this->getPostByID( $id );
		
		// Cac tags se fix
		$array_tags_fix = array();
		
		// Cac danh muc se fix
		$array_cat_fix = array();
		
		foreach( $posts as $row )
		{
			// Xoa bang chinh
			$sql = "DELETE FROM " . $this->table_prefix . "_rows WHERE id=" . $row['id'];
			$this->db->query( $sql );
			
			// Xoa bang data
			$html_table = $this->table_prefix . "_data_" . ceil( $row['id'] / 4000 );
			$sql = "DELETE FROM " . $html_table . " WHERE id=" . $row['id'];
			$this->db->query( $sql );
			
			// Them cac danh muc can fix
			$row['catids'] = $this->string2array( $row['catids'] );
			foreach( $row['catids'] as $catid )
			{
				$array_cat_fix[$catid] = $catid;
			}
			
			// Them cac tags can fix
			$row['tagids'] = $this->string2array( $row['tagids'] );
			foreach( $row['tagids'] as $tagsid )
			{
				$array_tags_fix[$tagsid] = $tagsid;
			}
			
			// Xoa bang data neu khong con bai viet nao
			$sql = "SELECT COUNT(*) FROM " . $html_table;
			$result = $this->db->query( $sql );
			list( $numposts ) = $result->fetch( PDO::FETCH_BOTH );
			
			if( ! $numposts )
			{
				$sql = "DROP TABLE " . $html_table;
				$this->db->query( $sql );
			}
			
			// Xoa tien trinh gui email
			$sql = "DELETE FROM " . $this->table_prefix . "_send WHERE pid=" . $row['id'];
			$this->db->query( $sql );
		}
		
		// Cap nhat tags
		$this->fixTags( $array_tags_fix );
		
		// Cap nhat danh muc
		$this->fixCat( $array_cat_fix );
	}
	
	/**
	 * nv_mod_blog::callFrameWorks()
	 * 
	 * @return void
	 */
	public function callFrameWorks()
	{
		$frameWorks = $this->findFrameWorks( func_num_args(), func_get_args() );
		
		global $my_head;
		
		if( ! isset( $my_head ) or empty( $my_head ) )
		{
			$my_head = $frameWorks;
		}
		else
		{
			$my_head .= $frameWorks;
		}
	}
	
	/**
	 * nv_mod_blog::getFrameWorks()
	 * 
	 * @return
	 */
	public function getFrameWorks()
	{
		return $this->findFrameWorks( func_num_args(), func_get_args() );
	}
	
	/**
	 * nv_mod_blog::pagination()
	 * 
	 * @param mixed $title
	 * @param mixed $base_url
	 * @param mixed $num_items
	 * @param mixed $per_page
	 * @param mixed $on_page
	 * @param bool $add_prevnext_text
	 * @param string $defis
	 * @return
	 */
	public function pagination( $title, $base_url, $num_items, $per_page, $on_page, $add_prevnext_text = true, $defis = '/' )
	{
		global $lang_global;
	
		$total_pages = ceil( $num_items / $per_page );
		
		if( $total_pages < 2 ) return '';
	
		$title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $this->glang('page');
		$page_string = ( $on_page == 1 ) ? "<li><span class=\"deactive\">1</span></li>" : "<li><a class=\"tbutton\" title=\"" . $title . " 1\" href=\"" . $base_url . "\"><span>1</span></a></li>";
	
		if( $total_pages > 10 )
		{
			$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
		
			for( $i = 2; $i <= $init_page_max; ++$i )
			{
				$page_string .= ( $i == $on_page ) ? "<li><span class=\"deactive\">" . $i . "</span></li>" : "<li><a class=\"tbutton\" title=\"" . $title . " " . $i . "\" href=\"" . $base_url . $defis . "page" . ( $defis == '/' ? '-' : '=' ) . $i . "\"><span>" . $i . "</span></a></li>";
			}
		
			if( $total_pages > 3 )
			{
				if( $on_page > 1 && $on_page < $total_pages )
				{
					if( $on_page > 5 )
					{
						$page_string .= "<li><span class=\"deactive\"> ... </span></li>";
					}
					
					$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
					$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
				
					for( $i = $init_page_min - 1; $i < $init_page_max + 2; ++$i )
					{
						$page_string .= ( $i == $on_page ) ? "<li><span class=\"deactive\">" . $i . "</span></li>" : "<li><a class=\"tbutton\" title=\"" . $title . " " . $i . "\" href=\"" . $base_url . $defis . "page" . ( $defis == '/' ? '-' : '=' ) . $i . "\"><span>" . $i . "</span></a></li>";
					}
					
					if( $on_page < $total_pages - 4 )
					{
						$page_string .= "<li><span class=\"deactive\"> ... </span></li>";
					}
				}
				else
				{
					$page_string .= "<li><span class=\"deactive\"> ... </span></li>";
				}
	
				for( $i = $total_pages - 2; $i < $total_pages + 1; ++$i )
				{
					$page_string .= ( $i == $on_page ) ? "<li><span class=\"deactive\">" . $i . "</span></li>" : "<li><a class=\"tbutton\" title=\"" . $title . " " . $i . "\" href=\"" . $base_url . $defis . "page" . ( $defis == '/' ? '-' : '=' ) . $i . "\"><span>" . $i . "</span></a></li>";
				}
			}
		}
		else
		{
			for( $i = 2; $i < $total_pages + 1; ++$i )
			{
				$page_string .= ( $i == $on_page ) ? "<li><span class=\"deactive\">" . $i . "</span></li>" : "<li><a class=\"tbutton\" title=\"" . $title . " " . $i . "\" href=\"" . $base_url . $defis . "page" . ( $defis == '/' ? '-' : '=' ) . $i . "\"><span>" . $i . "</span></a></li>";
			}
		}
	
		if( $add_prevnext_text )
		{
			if( $on_page > 1 )
			{
				$page_string = "<li><a class=\"tbutton\" title=\"" . $title . " " . ( $on_page - 1 ) . "\" href=\"" . $base_url . $defis . "page" . ( $defis == '/' ? '-' : '=' ) . ( $on_page - 1 ) . "\"><span>" . $this->glang('pageprev') . "</span></a></li>" . $page_string;
			}
		
			if( $on_page < $total_pages )
			{
				$page_string .= "<li><a class=\"tbutton\" title=\"" . $title . " " . ( $on_page + 1 ) . "\"  href=\"" . $base_url . $defis . "page" . ( $defis == '/' ? '-' : '=' ) . ( $on_page + 1 ) . "\"><span>" . $this->glang('pagenext') . "</span></a></li>";
			}
		}
		
		return '<ul class="pagination">' . $page_string . '</ul>';
	}
	
	public function BoldKeywordInStr( $str, $keyword )
	{
		$tmp = explode( " ", $keyword );
	
		foreach( $tmp as $k )
		{
			$tp = nv_strtolower( $k );
			$str = str_replace( $tp, "<span class=\"highlight\">" . $tp . "</span>", $str );
			$tp = nv_strtoupper( $k );
			$str = str_replace( $tp, "<span class=\"highlight\">" . $tp . "</span>", $str );
			$k[0] = nv_strtoupper( $k[0] );
			$str = str_replace( $k, "<span class=\"highlight\">" . $k . "</span>", $str );
		}
	
		return $str;
	}
}