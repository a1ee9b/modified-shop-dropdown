<?php
function smarty_function_dropdown( $params, &$smarty ) {
    $func_smarty = new Smarty();

    $level = $params['level'];
    $func_smarty->assign( 'menu', $params['menu'] );
    $func_smarty->assign( 'level', $level );
    $func_smarty->assign( 'nextLevel', $level+1 );

    return $func_smarty->fetch( CURRENT_TEMPLATE.'/smarty/dropdown.tpl' );
}