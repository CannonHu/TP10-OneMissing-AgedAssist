<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Notifications_Email_Prepare')):

/**
 * Listdom Notifications Email Prepare Class.
 *
 * @class LSD_Notifications_Email_Prepare
 * @version	1.0.0
 */
class LSD_Notifications_Email_Prepare extends LSD_Notifications
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function init()
    {
        // Contact Email
        add_action('lsd_contact_owner', array($this, 'contact'));

        // New Listing
        add_action('lsd_new_listing', array($this, 'new_listing'));

        // Listing Status Changed
        add_action('lsd_listing_status_changed', array($this, 'listing_status_changed'), 10, 2);

        // Listing Status Changed
        add_action('lsd_listing_report_abuse', array($this, 'abuse'), 10, 2);
	}

	public function contact($args)
    {
        return $this->form($args, 'lsd_contact_owner');
    }

    public function new_listing($listing_id)
    {
        $owner_id = get_post_field('post_author', $listing_id);
        $owner_name = get_the_author_meta('display_name', $owner_id);
        $owner_email = get_the_author_meta('user_email', $owner_id);

        // Results
        $mails = array();

        $notifications = $this->get('lsd_new_listing');
        foreach($notifications as $notification)
        {
            $content = get_post_meta($notification->ID, 'lsd_content', true);
            $subject = get_the_title($notification);

            // Send to original recipient?
            $original_to = get_post_meta($notification->ID, 'lsd_original_to', true);

            // Original Recipient
            if($original_to) $to = get_bloginfo('admin_email');
            // Custom Recipient
            else $to = trim(get_post_meta($notification->ID, 'lsd_to', true), ', ');

            $cc = trim(get_post_meta($notification->ID, 'lsd_cc', true), ', ');
            $bcc = trim(get_post_meta($notification->ID, 'lsd_bcc', true), ', ');

            // Specific Placeholders
            foreach(array('content', 'subject') as $item)
            {
                $$item = str_replace('#owner_name#', $owner_name, $$item);
                $$item = str_replace('#owner_email#', $owner_email, $$item);
            }

            $sender = new LSD_Notifications_Email_Sender();
            $mails[] = $sender->boot($notification->ID)->to($to)->cc($cc)->bcc($bcc)->subject($subject)->content($content)->render($listing_id)->send();
        }

        return $mails;
    }

    public function listing_status_changed($listing_id, $previous)
    {
        $owner_id = get_post_field('post_author', $listing_id);
        $owner_name = get_the_author_meta('display_name', $owner_id);
        $owner_email = get_the_author_meta('user_email', $owner_id);

        // Previous Status
        $status = get_post_status_object($previous);

        // Results
        $mails = array();

        $notifications = $this->get('lsd_listing_status_changed');
        foreach($notifications as $notification)
        {
            $content = get_post_meta($notification->ID, 'lsd_content', true);
            $subject = get_the_title($notification);

            // Send to original recipient?
            $original_to = get_post_meta($notification->ID, 'lsd_original_to', true);

            // Original Recipient
            if($original_to) $to = get_the_author_meta('email', $owner_id);
            // Custom Recipient
            else $to = trim(get_post_meta($notification->ID, 'lsd_to', true), ', ');

            $cc = trim(get_post_meta($notification->ID, 'lsd_cc', true), ', ');
            $bcc = trim(get_post_meta($notification->ID, 'lsd_bcc', true), ', ');

            // Specific Placeholders
            foreach(array('content', 'subject') as $item)
            {
                $$item = str_replace('#previous_status#', (isset($status->label) ? $status->label : ''), $$item);
                $$item = str_replace('#owner_name#', $owner_name, $$item);
                $$item = str_replace('#owner_email#', $owner_email, $$item);
            }

            $sender = new LSD_Notifications_Email_Sender();
            $mails[] = $sender->boot($notification->ID)->to($to)->cc($cc)->bcc($bcc)->subject($subject)->content($content)->render($listing_id)->send();
        }

        return $mails;
    }

    public function abuse($args)
    {
        return $this->form($args, 'lsd_listing_report_abuse');
    }

    public function form($args, $hook)
    {
        $listing_id = isset($args['post_id']) ? $args['post_id'] : NULL;
        $owner_id = get_post_field('post_author', $listing_id);

        $name = isset($args['name']) ? $args['name'] : '';
        $email = isset($args['email']) ? $args['email'] : '';
        $phone = isset($args['phone']) ? $args['phone'] : '';
        $message = isset($args['message']) ? $args['message'] : '';

        // Results
        $mails = array();

        $notifications = $this->get($hook);
        foreach($notifications as $notification)
        {
            $content = get_post_meta($notification->ID, 'lsd_content', true);
            $subject = get_the_title($notification);

            // Send to original recipient?
            $original_to = get_post_meta($notification->ID, 'lsd_original_to', true);

            // Original Recipient
            if($original_to)
            {
                if($hook === 'lsd_listing_report_abuse') $to = get_bloginfo('admin_email');
                else $to = get_the_author_meta('email', $owner_id);
            }
            // Custom Recipient
            else $to = trim(get_post_meta($notification->ID, 'lsd_to', true), ', ');

            $cc = trim(get_post_meta($notification->ID, 'lsd_cc', true), ', ');
            $bcc = trim(get_post_meta($notification->ID, 'lsd_bcc', true), ', ');

            // Specific Placeholders
            foreach(array('content', 'subject') as $item)
            {
                $$item = str_replace('#name#', $name, $$item);
                $$item = str_replace('#email#', $email, $$item);
                $$item = str_replace('#phone#', $phone, $$item);
                $$item = str_replace('#message#', '<i>'.nl2br($message).'</i>', $$item);
            }

            $sender = new LSD_Notifications_Email_Sender();
            $mails[] = $sender->boot($notification->ID)->to($to)->cc($cc)->bcc($bcc)->subject($subject)->content($content)->render($listing_id)->send();
        }

        return $mails;
    }
}

endif;