<?php

// Categories that should be ignored
// Keep in mind, sub-categories will be ignored too
// since the don't have a parent to hold on to
// in the menu
$cat_exceptions = array();

$box_smarty = new smarty;
$box_smarty->assign( 'tpl_path','templates/'.CURRENT_TEMPLATE );
$box_smarty->assign('language', $_SESSION['language']);

// set cache ID
if ( !CacheCheck() ) {

  $cache = false;
  $box_smarty->caching = 0;

} else {

  $cache = true;
  $box_smarty->caching = 1;
  $box_smarty->cache_lifetime = CACHE_LIFETIME;
  $box_smarty->cache_modified_check = CACHE_CHECK;
  $cache_id = $_SESSION['language'];

}

if ( !$box_smarty->is_cached( CURRENT_TEMPLATE . '/boxes/box_dropdown.html', $cache_id ) || !$cache ) {

    $cat_exception = "";
    foreach ( $cat_exceptions as $ex ) {
      $cat_exception .= " AND c.categories_id <> '".$ex."'";
    }
    if ( GROUP_CHECK == 'true' ) {
     $group_check = "AND c.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
    }

    $query = xtc_db_query("
        SELECT c.categories_id, c.categories_image, cd.categories_name, cd.categories_description, c.parent_id
        FROM ".TABLE_CATEGORIES." AS c, ".TABLE_CATEGORIES_DESCRIPTION." AS cd
        WHERE c.categories_status = '1'
        ".$group_check."
        ".$cat_exception."
        and c.parent_id <> '1'
        AND c.categories_id = cd.categories_id
        AND cd.language_id='" . (int)$_SESSION['languages_id'] ."'
        ORDER BY sort_order, cd.categories_name");

    // Create a flat array where all the items are grouped by their parent id
    $menu_tmp = array();
    while ( $category = xtc_db_fetch_array( $query ) )  {
        $pid = $category['parent_id'];
        $cid = $category['categories_id'];

        $menu_tmp[$pid][$cid] = array();
        $menu_tmp[$pid][$cid]['id'] = $cid;
        $menu_tmp[$pid][$cid]['name'] = $category['categories_name'];
        $menu_tmp[$pid][$cid]['description'] = $category['categories_description'];
        $menu_tmp[$pid][$cid]['parent'] = $pid;
    }

    $parent = 0;
    $menu = build_menu( $parent, $menu_tmp );

    // use only the main category 'flowers'
    $box_smarty->assign( 'menu', $menu );
}

if (!$cache) {
    $box_dropdown = $box_smarty->fetch( CURRENT_TEMPLATE.'/boxes/box_dropdown.html' );
} else {
    $box_dropdown = $box_smarty->fetch( CURRENT_TEMPLATE.'/boxes/box_dropdown.html', $cache_id );
}

$smarty->assign( 'box_DROPDOWN', $box_dropdown );

// Recursive function to build a menu
// Takes the parent-id and the flat menu
function build_menu ( $parent, $menu_tmp ) {
    $menu = array();
    foreach ( $menu_tmp[$parent] as $id => $category ) {
        $menu[$id] = new stdClass();
        $menu[$id]->id = $category['id'];
        $menu[$id]->name = $category['name'];
        $menu[$id]->description = $category['description'];

        $children = $menu_tmp[$category["id"]];
        if ( count( $children ) > 0) {
            $menu[$id]->children = build_menu( $id, $menu_tmp );
        }
    }
    return $menu;
}

