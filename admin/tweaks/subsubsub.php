<?php

class BP_LP_UserSubItems {

    private $user_status;
    private $user_role;

    function __construct($user_role)
    {
        $this->user_role = $user_role;
        $this->user_status = array(
            null,
            'active',
            'pending',
            'inactive'
        );
        return $this->user_status;
    }

    function get_user_accepted($role){
        $usersearch = isset( $_REQUEST['search'] ) ? wp_unslash( trim( $_REQUEST['search'] ) ) : '';

        if($role=="auto"){
            if(isset($_GET['filter'])){
                switch ($_GET['filter']){
                    case "all":
                        $role = null;
                        break;
                    case "active":
                        if(strtolower($role)=='instructor'){
                            $role = 'lp_teacher';
                        }else{
                            $role = $this->user_role;
                            if(strtolower($role)=='instructor'){
                                $role = 'lp_teacher';
                            }
                        }
                        break;
                    case "pending":
                        $role = "subscriber";
                        break;
                    case "inactive":
                        $role = '';
                        break;
                }
            }else{
                $role = null;
            }
        }

        if(strtolower($role)=='instructor'){
            $role = 'lp_teacher';
        }

        $privilege_list = $this->get_user_privilege();
        $user_list = new WP_User_Query( array(
                'role' => $role,
                'search'  => $usersearch,
                'fields'  => 'all_with_meta'
            )
        );

        $user_accepted_list = array();
        foreach ($user_list->get_results() as $ul){
            $r = bp_get_profile_field_data('field=User Role&user_id='.$ul->ID);
            if(!in_array('lp_teacher', $privilege_list)){
                $school = bp_get_profile_field_data('field=School&user_id='.$ul->ID);
                $user_school = bp_get_profile_field_data('field=School&user_id='.wp_get_current_user()->ID);
                if($school==$user_school){
                    $user_accepted_list = $this->user_append_item($role, $r, $ul, $user_accepted_list);
                }
            }else{
                $user_accepted_list = $this->user_append_item($role, $r, $ul, $user_accepted_list);
            }
        }
        return $user_accepted_list;
    }

    function user_append_item($role, $item_role, $item_data, $user_accepted_list){
        if($item_role==$this->user_role){
            if($role!==''){
                foreach ($item_data->roles as $rle){
                    if($role==null){
                        array_push($user_accepted_list, $item_data);
                    }else{
                        if($rle==$role){
                            array_push($user_accepted_list, $item_data);
                        }elseif ($rle=='lp_teacher' && strtolower($role) == 'instructor'){
                            array_push($user_accepted_list, $item_data);
                        }
                    }
                }
            }else{
                if($item_role==$this->user_role && empty($item_data->roles)){
                    array_push($user_accepted_list, $item_data);
                }
            }
        }
        return $user_accepted_list;
    }


    function get_user_accepted_count($role){
        return count($this->get_user_accepted($role));
    }

    function get_user_count($status){
        switch ($status){
            case 'all':
                $total = $this->get_user_accepted_count(strtolower($this->user_role)) +
                    $this->get_user_accepted_count('subscriber');
                return $total;
                break;
            case 'active':
                return $this->get_user_accepted_count(strtolower($this->user_role));
                break;
            case 'pending':
                return $this->get_user_accepted_count('subscriber');
                break;
            case 'inactive':
                $user_list = new WP_User_Query( array(
                        'role' => '',
                        'fields'  => 'all_with_meta'
                    )
                );
                $user_accepted_list = array();
                foreach ($user_list->get_results() as $ul){
                    $r = bp_get_profile_field_data('field=User Role&user_id='.$ul->ID);
                    if($r==$this->user_role && empty($ul->roles)){
                        array_push($user_accepted_list, $ul);
                    }
                }
                return count($user_accepted_list);
                break;
        }
    }

    function link_template($url, $status){

    }

    function emphasize_template($status){
        ?>
        <b class="current" aria-current="page">
            <?php echo '&nbsp'.ucfirst($status);?>
            <span class="count">
                    <?php echo '('.$this->get_user_count($status).')'; ?>
                </span>
        </b>
        <?php
    }

    function render(){
        global $wp;
        ?>
        <ul class="subsubsub">
            <?php
            $total_item = count($this->user_status);
            $i = 0;
            ?>
            <?php foreach ($this->user_status as $status_item):?>
                <?php
                $i++;
                $status = is_null($status_item) ? "all" : $status_item;
                $url = add_query_arg( array(
                    'tab'  => strtolower($this->user_role),
                    'filter'  => $status,
                ), home_url( $wp->request ) );
                ?>
                <li class="<?php echo $status;?>">
                    <?php
                    $filter_status = '';
                    if(isset($_GET['filter'])){
                        if($_GET['filter']==$status){
                            $filter_status = 'current';
                        }
                    }else{
                        if($i==1){
                            $filter_status = 'current';
                        }
                    }?>

                    <a href="<?php echo $url?>" class="<?php echo $filter_status;?>" aria-current="page">
                        <?php echo '&nbsp'.ucfirst($status);?>
                        <span class="count">
                    <?php echo '('.$this->get_user_count($status).')'; ?>
                </span>
                    </a>
                    <?php echo ($total_item-$i)!=0? '|':'' ?>
                </li>
            <?php endforeach;?>
        </ul>
        <div class="bp-lms-search-box">
            <form method="get" action="<?php echo add_query_arg([]);?>">
                <?php $fields = array("tab", "filter", "paged", "orderby"); ?>
                <?php foreach ($fields as $field){
                    echo isset($_GET[$field])? sprintf('<input type="hidden" name="%s" value="%s">', $field, $_GET[$field]): '';
                }?>
                <label class="screen-reader-text" for="user-search-input">Search Users:</label>
                <input type="search" id="user-search-input" name="search" value="<?php echo isset($_GET['search'])? $_GET['search'] : ''?>">
                <input type="submit" id="search-submit" class="button" value="Search Users">
            </form>
        </div>
        <?php
    }

    protected function get_user_privilege(){
        $user_privilege = wp_get_current_user()->roles;

        $access_levels = (array) [
            "lp_teacher" => array('administrator', 'bbp_keymaster'),
            "student" => array('administrator', 'bbp_keymaster', 'lp_teacher'),
            "parent" => array('administrator', 'bbp_keymaster', 'lp_teacher'),
        ];

        $access_list = array();
        foreach ($access_levels as $al => $al_val){
            foreach ($user_privilege as $up){
                if(in_array($up, $al_val)){
                    array_push($access_list, $al);
                }
            }
        }

        $access_list = array_unique($access_list);
        return $access_list;
    }

    protected function get_role_list( $user_object ) {
        $wp_roles = wp_roles();
        $role_list = array();
        foreach ( $user_object->roles as $role ) {
            if ( isset( $wp_roles->role_names[ $role ] ) ) {
                $role_list[ $role ] = translate_user_role( $wp_roles->role_names[ $role ] );
            }
        }
        if ( empty( $role_list ) ) {
            $role_list['none'] = _x( 'None', 'no user roles' );
        }
        /**
         * Filters the returned array of roles for a user.
         *
         * @since 4.4.0
         *
         * @param string[] $role_list   An array of user roles.
         * @param WP_User  $user_object A WP_User object.
         */
        return apply_filters( 'get_role_list', $role_list, $user_object );
    }

}
