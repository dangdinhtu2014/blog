<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

$url = array();
$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_name . "_Sitemap.cache";
$pa = NV_CURRENTTIME - 7200;

if ( ( $cache = nv_get_cache( $module_name, $cacheFile ) ) != false AND filemtime( $cacheFile ) >= $pa )
{
	$url = unserialize( $cache );
}
else
{
	$sql = "SELECT title, alias, posttime FROM " . $BL->table_prefix . "_rows WHERE status=1 ORDER BY posttime DESC LIMIT 1000";
	$result = $db->query( $sql );

	while( list( $title, $alias, $posttime ) = $result->fetch( 3 ) )
	{
		$url[] = array(
			'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias . $global_config['rewrite_exturl'],
			'publtime' => $posttime
		);
	}
	
	$cache = serialize( $url );
	nv_set_cache( $module_name, $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();