<?php
/**
 * Custom BuddyPress XProfile Classes.
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class to help set up XProfile fields.
 *
 * @since 1.0.0
 */
class BP_LMS_XProfile_Field extends BP_XProfile_Field {

    public $identifier;

    public $tab;

    function __construct($field)
    {
        parent::__construct($field);
    }

    public function render_tab_form( $message = '' ) {

        // Users Admin URL
        $users_url = bp_get_admin_url( 'admin.php' );

        // Edit
        $button	= __( 'Save');
        $action = add_query_arg( array(
           'page'     => 'buddypress-lms-integration',
           'tab'      => $this->tab,
           'mode'     => 'edit',
           'field_id' => (int) $this->id
        ), $users_url . '#tabs-' . (int) $this->group_id ); ?>

        <div class="wrap">
            <form id="bp-lms-xprofile-edit-field" action="<?php echo esc_url( $action ); ?>" method="post">
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-<?php echo ( 1 == get_current_screen()->get_columns() ) ? '1' : '2'; ?>">
                        <div id="postbox-container-1" class="postbox-container">

							<?php

							// Output the sumbit metabox.
							$this->submit_metabox( $button );

							// Output the Member Types metabox.
							$this->member_type_metabox();

							/**
							 * Fires after XProfile Field sidebar metabox.
							 *
							 * @since 2.2.0
							 *
							 * @param BP_XProfile_Field $this Current XProfile field.
							 */
							do_action( 'xprofile_field_after_sidebarbox', $this ); ?>

						</div>

                        <div id="postbox-container-2" class="postbox-container">

							<?php

							/**
							 * Fires before XProfile Field content metabox.
							 *
							 * @since 2.3.0
							 *
							 * @param BP_XProfile_Field $this Current XProfile field.
							 */
							do_action( 'xprofile_field_before_contentbox', $this );

							// Output the field attributes metabox.
							$this->type_metabox();

							/**
							 * Fires after XProfile Field content metabox.
							 *
							 * @since 2.2.0
							 *
							 * @param BP_XProfile_Field $this Current XProfile field.
							 */
							do_action( 'xprofile_field_after_contentbox', $this ); ?>

						</div>
                    </div><!-- #post-body -->
                </div><!-- #poststuff -->
            </form>
        </div>

        <?php
    }

    /**
     * Private method used to display the submit metabox.
     *
     * @since 2.3.0
     *
     * @param string $button_text Text to put on button.
     */
    private function submit_metabox( $button_text = '' ) {

        // Setup the URL for deleting
        $users_url  = bp_get_admin_url( 'users.php' );
        $cancel_url = add_query_arg( array(
            'page' => 'bp-profile-setup'
        ), $users_url );

        /**
         * Fires before XProfile Field submit metabox.
         *
         * @since 2.1.0
         *
         * @param BP_XProfile_Field $this Current XProfile field.
         */
        do_action( 'xprofile_field_before_submitbox', $this ); ?>

        <div id="submitdiv" class="postbox">
            <h2><?php esc_html_e( 'Submit', 'buddypress' ); ?></h2>
            <div class="inside">
                <div id="submitcomment" class="submitbox">
                    <div id="major-publishing-actions">

                        <?php

                        /**
                         * Fires at the beginning of the XProfile Field publishing actions section.
                         *
                         * @since 2.1.0
                         *
                         * @param BP_XProfile_Field $this Current XProfile field.
                         */
                        do_action( 'xprofile_field_submitbox_start', $this ); ?>

                        <input type="hidden" name="field_order" id="field_order" value="<?php echo esc_attr( $this->field_order ); ?>" />

                        <?php if ( ! empty( $button_text ) ) : ?>

                            <div id="publishing-action">
                                <input type="submit" name="saveField" value="<?php echo esc_attr( $button_text ); ?>" class="button-primary" />
                            </div>

                        <?php endif; ?>

                        <div id="delete-action">
                            <a href="<?php echo esc_url( $cancel_url ); ?>" class="deletion"><?php esc_html_e( 'Cancel', 'buddypress' ); ?></a>
                        </div>

                        <?php wp_nonce_field( 'xprofile_delete_option' ); ?>

                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php

        /**
         * Fires after XProfile Field submit metabox.
         *
         * @since 2.1.0
         *
         * @param BP_XProfile_Field $this Current XProfile field.
         */
        do_action( 'xprofile_field_after_submitbox', $this );
    }

    /**
     * Private method used to output field Member Type metabox.
     *
     * @since 2.4.0
     */
    private function member_type_metabox() {

        // The primary field is for all, so bail.
        if ( 1 === (int) $this->id ) {
            return;
        }

        // Bail when no member types are registered.
        if ( ! $member_types = bp_get_member_types( array(), 'objects' ) ) {
            return;
        }

        $field_member_types = $this->get_member_types();

        ?>

        <div id="member-types-div" class="postbox">
            <h2><?php _e( 'Member Types', 'buddypress' ); ?></h2>
            <div class="inside">
                <p class="description"><?php _e( 'This field should be available to:', 'buddypress' ); ?></p>

                <ul>
                    <?php foreach ( $member_types as $member_type ) : ?>
                        <li>
                            <label for="member-type-<?php echo $member_type->labels['name']; ?>">
                                <input name="member-types[]" id="member-type-<?php echo $member_type->labels['name']; ?>" class="member-type-selector" type="checkbox" value="<?php echo $member_type->name; ?>" <?php checked( in_array( $member_type->name, $field_member_types ) ); ?>/>
                                <?php echo $member_type->labels['name']; ?>
                            </label>
                        </li>
                    <?php endforeach; ?>

                    <li>
                        <label for="member-type-none">
                            <input name="member-types[]" id="member-type-none" class="member-type-selector" type="checkbox" value="null" <?php checked( in_array( 'null', $field_member_types ) ); ?>/>
                            <?php _e( 'Users with no member type', 'buddypress' ); ?>
                        </label>
                    </li>

                </ul>
                <p class="description member-type-none-notice<?php if ( ! empty( $field_member_types ) ) : ?> hide<?php endif; ?>"><?php _e( 'Unavailable to all members.', 'buddypress' ) ?></p>
            </div>

            <input type="hidden" name="has-member-types" value="1" />
        </div>

        <?php
    }

    /**
     * Output the metabox for setting what type of field this is.
     *
     * @since 2.3.0
     *
     * @return void If default field.
     */
    private function type_metabox() {

        // Default field cannot change type.
        if ( true === $this->is_default_field() ) {
            return;
        } ?>

        <div class="postbox">
            <h2>Options</h2>
            <div class="inside" aria-live="polite" aria-atomic="true" aria-relevant="all">
                <?php

                // Deprecated filter, don't use. Go look at {@link BP_XProfile_Field_Type::admin_new_field_html()}.
                do_action( 'xprofile_field_additional_options', $this );

                $this->render_admin_form_children(); ?>

            </div>
        </div>

        <?php
    }


    /**
     * Return if a field ID is the default field.
     *
     * @since 2.3.0
     *
     * @param int $field_id ID of field to check.
     * @return bool
     */
    private function is_default_field( $field_id = 0 ) {

        // Fallback to current field ID if none passed.
        if ( empty( $field_id ) ) {
            $field_id = $this->id;
        }

        // Compare & return.
        return (bool) ( 1 === (int) $field_id );
    }
}