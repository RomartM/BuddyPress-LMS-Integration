<?php

class BP_UserRole_List_Table extends WP_List_Table {

    private $data_sets;
    private $data_tab;

    function set_data_sets($data, $tab){
        $this->data_sets = $data;
        $this->data_tab = $tab;
    }

    function get_columns(){
        $columns = array(
            'username' => 'Username',
            'name'    => 'Name',
            'email'      => 'Email',
            'role_status' => 'Role Status'
        );
        return $columns;
    }

    function set_url($status){
        $success_url =  add_query_arg(
            array('status'  => $status),
            remove_query_arg( array('act_role', 'uid') )) ;
        return $success_url;
    }

    function role_management(){
        if(isset($_GET['act_role']) && isset($_GET['uid']) && isset($_GET['tab'])){

            switch ($_GET['act_role']){
                case 'approve':
                    $wp_user_object = new WP_User($_GET['uid']);
                    $wp_user_object->set_role(strtolower($_GET['tab'])=='instructor'? 'lp_teacher' : $_GET['tab']);
                    wp_redirect($this->set_url(1));
                    break;
                case 'disapprove':
                    $wp_user_object = new WP_User($_GET['uid']);
                    $wp_user_object->remove_role((strtolower($_GET['tab'])=='instructor'? 'lp_teacher' : $_GET['tab']));
                    $wp_user_object->set_role('subscriber');
                    wp_redirect($this->set_url(2));
                    break;
            }
        }
    }

    function prepare_items() {
        // Call Role Management
        $this->role_management();
        if(isset($_GET['status'])){
            $stylesheet =  'background-color: #C8E6C9;
                            padding: 7px;
                            margin: 8px 1px;
                            border-left-style: solid;
                            border-left-color: #4CAF50;
                            color: #212121;';
            if($_GET['status']==1){
                echo "<div style='$stylesheet'>Success: Approving User</div>";
            }elseif($_GET['status']==2){
                echo "<div style='$stylesheet'>Success: Removing Grant to User</div>";
            }
        }
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $filtered_sets = $this->data_sets;
        $this->items = $filtered_sets;
        $this->set_pagination_args(
            array(
                'total_items' => count($filtered_sets),
                'per_page'    => 'users_per_page',
            )
        );
    }

    public function display_rows() {
        foreach ( $this->items as $userid => $user_object ) {
            echo "\n\t" . $this->single_row( $user_object, '', '', isset( $post_counts ) ? $post_counts[ $userid ] : 0 );
        }
    }

    public function single_row( $user_object, $style = '', $role = '') {
        if ( ! ( $user_object instanceof WP_User ) ) {
            $user_object = get_userdata( (int) $user_object );
        }
        $user_object->filter = 'display';
        // User Email
        $email               = $user_object->user_email;
        // User Role List
        $user_roles = $this->get_role_list( $user_object );
        // User Profile Avatar
        $avatar = get_avatar( $user_object->ID, 32 );
        // User Name
        $edit = "<strong>{$user_object->user_login}</strong>";

        $checkbox    = '';
        $roles_list = implode( ', ', $user_roles );

        $pending_role = bp_get_profile_field_data('field=User Role&user_id='.$user_object->ID);

        // Add a link to the user's author archive, if not empty.
        $author_posts_url = get_author_posts_url( $user_object->ID );
        if ( $author_posts_url ) {
            $actions['view'] = sprintf(
                '<a href="%s" aria-label="%s">%s</a>',
                esc_url( $author_posts_url ),
                /* translators: %s: author's display name */
                esc_attr( sprintf( __( 'View posts by %s' ), $user_object->display_name ) ),
                __( 'View' )
            );
        }

        // Role Status
        $role_status = "";

        // Verify if user is approved
        foreach ($user_roles as $role){
            if($role!=$pending_role){
                $role_status = "Pending";
                $actions['approve_request'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    add_query_arg( array(
                        'act_role'  => 'approve',
                        'uid'  =>  $user_object->ID
                    ), add_query_arg([])),
                    'aria label',
                    __('Approve Role')
                );
            }else{
                $role_status = "Approved";
                $actions['remove grant'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    add_query_arg( array(
                        'act_role'  => 'disapprove',
                        'uid'  =>  $user_object->ID
                    ), add_query_arg([])),
                    'aria label',
                    __('Remove Grant')
                );
                break;
            }
        }
        /**
         * Filters the action links displayed under each user in the Users list table.
         *
         * @since 2.8.0
         *
         * @param string[] $actions     An array of action links to be displayed.
         *                              Default 'Edit', 'Delete' for single site, and
         *                              'Edit', 'Remove' for Multisite.
         * @param WP_User  $user_object WP_User object for the currently listed user.
         */
        $actions = apply_filters( 'user_row_actions', $actions, $user_object );

        $r = "<tr id='user-$user_object->ID'>";
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();
        foreach ( $columns as $column_name => $column_display_name ) {
            $classes = "$column_name column-$column_name";
            if ( $primary === $column_name ) {
                $classes .= ' has-row-actions column-primary';
            }
            if ( 'posts' === $column_name ) {
                $classes .= ' num'; // Special case for that column
            }
            if ( in_array( $column_name, $hidden ) ) {
                $classes .= ' hidden';
            }
            $data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';
            $attributes = "class='$classes' $data";
            if ( 'cb' === $column_name ) {
                $r .= "<th scope='row' class='check-column'>$checkbox</th>";
            } else {
                $r .= "<td $attributes>";
                switch ( $column_name ) {
                    case 'username':
                        $r .= "$avatar $edit";
                        break;
                    case 'name':
                        if ( $user_object->first_name && $user_object->last_name ) {
                            $r .= "$user_object->first_name $user_object->last_name";
                        } elseif ( $user_object->first_name ) {
                            $r .= $user_object->first_name;
                        } elseif ( $user_object->last_name ) {
                            $r .= $user_object->last_name;
                        } else {
                            $r .= '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . _x( 'Unknown', 'name' ) . '</span>';
                        }
                        break;
                    case 'email':
                        $r .= "<a href='" . esc_url( "mailto:$email" ) . "'>$email</a>";
                        break;
                    case 'role_status':
                        $r .= '<span>'.$role_status.'</span>';
                        break;
                    default:
                        /**
                         * Filters the display output of custom columns in the Users list table.
                         *
                         * @since 2.8.0
                         *
                         * @param string $output      Custom column output. Default empty.
                         * @param string $column_name Column name.
                         * @param int    $user_id     ID of the currently-listed user.
                         */
                        $r .= apply_filters( 'manage_users_custom_column', '', $column_name, $user_object->ID );
                }
                if ( $primary === $column_name ) {
                    $r .= $this->row_actions( $actions );
                }
                $r .= '</td>';
            }
        }
        $r .= '</tr>';
        return $r;
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

function bp_lms_generate_table($role){
    $user = new BP_LP_UserSubItems($role);
    $user->render();

    $gen_table = new BP_UserRole_List_Table();
    $gen_table->set_data_sets($user->get_user_accepted("auto"), strtolower($role));
    $gen_table->prepare_items();
    $gen_table->display();
    return true;
}