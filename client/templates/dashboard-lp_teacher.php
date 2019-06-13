<?php

$tab_metadata = (object) [
    [
        'href'  =>  'student',
        'tabTitle' =>  'Students',
        'isActive' =>  false,
        'identifier'    => 'Student'
    ],
    [
        'href'  =>  'parent',
        'tabTitle' =>  'Parents',
        'isActive' =>  false,
        'identifier'    => 'Parent'
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