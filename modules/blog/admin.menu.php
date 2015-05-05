<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$submenu['blog-list'] = $lang_module['blogList'];
$submenu['blog-content'] = $lang_module['blogAdd'];
$submenu['categories'] = $lang_module['categoriesManager'];
$submenu['tags'] = $lang_module['tagsMg'];
$submenu['newsletter-manager'] = $lang_module['nltList'];
$submenu['config-block-tags'] = $lang_module['cfgBlockTags'];
$submenu['config-structured-data'] = $lang_module['cfgStructureData'];
$submenu['config-comment'] = $lang_module['cfgComment'];
$submenu['config-master'] = $lang_module['cfgMaster'];

$allow_func = array( 'main', 'categories', 'newsletter-manager', 'config-master', 'tags', 'blog-list', 'blog-content', 'config-block-tags', 'config-sys', 'config-structured-data', 'config-comment' );