<?php
    global $wp;
    $teacher_status = array(
        null,
        'active',
        'pending',
        'inactive'
    );

    function bp_lp_p_a($role){
        $user_list = get_users( array('role' => $role) );
        $user_accepted_list = array();
        foreach ($user_list as $ul){
            $r = bp_get_profile_field_data('field=User Role&user_id='.$ul->ID);
            if($r=="Teacher"){
                array_push($user_accepted_list, $ul);
            }
        }
        return count($user_accepted_list);
    }

    function bp_lp_get_user_count($status){
        switch ($status){
            case 'all':
                $total = bp_lp_p_a('teacher') +
                    bp_lp_p_a('subscriber');
                return $total;
                break;
            case 'active':
                return bp_lp_p_a('teacher');
                break;
            case 'pending':
                return bp_lp_p_a('subscriber');
                break;
            case 'inactive':
                $user_list = get_users(array('role' => null));
                $user_accepted_list = array();
                foreach ($user_list as $ul){
                    $r = bp_get_profile_field_data('field=User Role&user_id='.$ul->ID);
                    if($r=="Teacher" && empty($ul->roles)){
                        array_push($user_accepted_list, $ul);
                    }
                }
                return count($user_accepted_list);
                break;
        }
    }

?>
<ul class="subsubsub">
    <?php
        $total_item = count($teacher_status);
        $i = 0;
    ?>
    <?php foreach ($teacher_status as $status_item):?>
        <?php
            $i++;
            $status = is_null($status_item) ? "all" : $status_item;
            $url = add_query_arg( array(
                'tab'  => 'teacher',
                'filter'  => $status,
            ), home_url( $wp->request ) );
        ?>
        <li class="<?php echo $status;?>">
            <a href="<?php echo $url?>" class="current" aria-current="page">
                <?php echo '&nbsp'.ucfirst($status);?>
                <span class="count">
                    <?php echo '('.bp_lp_get_user_count($status).')'; ?>
                </span>
            </a> <?php echo ($total_item-$i)!=0? '|':'' ?>
        </li>
    <?php endforeach;?>
</ul>