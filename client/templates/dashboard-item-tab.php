<?php

function tab_metadata_link($identifier){
    global $wp;
    $url = add_query_arg( array(
        'tab'  => $identifier
    ), home_url( $wp->request ) );
    return $url;
}

?>

<ul class="nav nav-tabs">
    <?php foreach ($tab_metadata as $tab_menu_data):?>
        <li <?php echo $tab_menu_data["isActive"]? 'class="active"': ''; ?>>
            <a
               href="<?php echo tab_metadata_link($tab_menu_data["href"]);?>">
                <?php echo $tab_menu_data["tabTitle"]; ?>
            </a>
        </li>
    <?php endforeach;?>
</ul>

<div class="tab-content">
    <?php foreach ($tab_metadata as $tab_content_data):?>
        <div id="<?php echo $tab_content_data["href"];?>"
             class="tab-pane fade <?php echo $tab_content_data["isActive"]? 'in active': ''; ?>">
            <h3><?php echo $tab_content_data["tabTitle"];?></h3>
            <?php if(!empty($tab_content_data["contentPath"])){
                include($tab_content_data["contentPath"]);
            }?>
            <?php if(!empty($tab_content_data["identifier"])){
                bp_lms_generate_table($tab_content_data["identifier"]);
            }?>
        </div>
    <?php endforeach; ?>
</div>