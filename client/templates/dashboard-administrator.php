<?php

$tab_metadata = (object) [
    [
        'href'  =>  'instructor',
        'tabTitle' =>  'Teachers',
        'identifier'  =>  'Instructor',
        'isActive' =>  false,
        //'contentPath'  => get_dashboard_tab_content('administrator', 'teachers')
    ],
    [
        'href'  =>  'student',
        'tabTitle' =>  'Students',
        'identifier'  =>  'Student',
        'isActive' =>  false,
        //'contentPath'  => get_dashboard_tab_content('administrator', 'students')
    ],
    [
        'href'  =>  'parent',
        'tabTitle' =>  'Parents',
        'identifier'  =>  'Parent',
        'isActive' =>  false,
        //'contentPath'  => get_dashboard_tab_content('administrator', 'parents')
    ]
];


if(isset($_GET['tab'])){
    $i = 0;
    foreach ($tab_metadata as $tab_data){
        if($tab_data['href']==$_GET['tab']){
            $tab_metadata->{$i}['isActive'] = true;
        }
        $i++;
    }
}else{
    $tab_metadata->{0}['isActive']= true;
}



include 'dashboard-item-tab.php';
?>