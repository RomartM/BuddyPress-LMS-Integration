<div class="bp-lms-dashboard-container">
    <header class="bp-lms-header" role="banner">
        <div class="bp-lms-title">
            <h1><?php echo $bp_lms_plugin_info['name'];?></h1>
        </div>
        <div class="bp-lms-description">
            <p><?php echo $bp_lms_plugin_info['description']; ?></p>
        </div>
    </header>
    <nav class="nav-tab-wrapper">
        <?php foreach($bp_lms_tabs_nav_object as $data): ?>
            <a href="<?php echo bp_lms_generate_tab_url($data['href']); ?>"
               class="nav-tab
            <?php echo ($bp_lms_selected_tab===$data['href']) ? 'nav-tab-active' : '';?>">
                <?php echo $data['title']; ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="bp-lms-dashboard-tab-view">
        <?php include (bp_lms_get_tab_page($bp_lms_selected_tab));?>
    </div>
</div>