<ul class="nav nav-tabs">
    <?php foreach ($tab_metadata as $tab_menu_data):?>
        <li <?php echo $tab_menu_data["isActive"]? 'class="active"': ''; ?>>
            <a data-toggle="tab"
               href="#<?php echo $tab_menu_data["href"];?>">
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
            <?php include($tab_content_data["contentPath"])?>
        </div>
    <?php endforeach; ?>
</div>