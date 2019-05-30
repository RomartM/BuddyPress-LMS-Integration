<?php

$tab_metadata = (object) [
    [
        'href'  =>  'teachers',
        'tabTitle' =>  'Teachers',
        'isActive' =>  true,
        'contentPath'  => get_dashboard_tab_content('administrator', 'teachers')
    ],
    [
        'href'  =>  'learnPress',
        'tabTitle' =>  'Learnpress',
        'isActive' =>  false,
        'contentPath'  => get_dashboard_tab_content('administrator', 'learnpress')
    ],
];

include 'dashboard-item-tab.php';
?>