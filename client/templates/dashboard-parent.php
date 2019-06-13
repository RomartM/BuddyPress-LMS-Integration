<?php

$tab_metadata = (object) [
    [
        'href'  =>  'search_student',
        'tabTitle' =>  'Search Student',
        'isActive' =>  true,
        'contentPath'  => get_dashboard_tab_content('parent', 'search_student')
    ],
];

include 'dashboard-item-tab.php';
?>