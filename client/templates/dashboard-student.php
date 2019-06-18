<?php

$tab_metadata = (object) [
    [
        'href'  =>  'course_summary',
        'tabTitle' =>  'My Courses Summary',
        'isActive' =>  true,
        'contentPath'  => get_dashboard_tab_content('student', 'course_summary')
    ],
];

include 'dashboard-item-tab.php';
