<?php

/**
 * Do not change this value if there is no DataTables translation available for your language.
 * You can check out the available translations using the following link.
 * @link https://cdn.datatables.net/plug-ins/1.10.19/i18n/
 */
$GLOBALS["dataTablesLanguage"] = "English";

$lang = [
    'deplete_credit_for_each_sms_part_label' => 'Deplete 1 credit for each SMS part',
    'success_blacklisted' => 'You will not receive any more messages from us. Let us know if you change your mind.',
    'use_credits_for_received_messages' => 'Use credits for received messages',
    'disabled' => 'Disabled',
    'received_message_notification' => 'Received message browser notification',
    'realtime_dashboard_updates' => 'Realtime dashboard status updates',
    'integrations' => 'Integrations',
    'features' => 'Features',
    'disabled_by_admin' => 'Disabled by Admin',
    'remaining' => 'Remaining',
    'per_message' => 'Per Message',
    'length' => 'Length',
    'encoding' => 'Encoding',
    'expired' => 'Expired',
    'admin' => 'Admin',
    'info' => 'Info',
    'subscription' => 'Subscription',
    'mass_mms_instructions' => '<p>You can send a mass MMS by separating the mobile numbers using pipe(|) symbol instead of comma(,). It will send an MMS to multiple numbers, like BCC functionality in email. For example, consider the following numbers.</p>
<p style="word-break: break-all">+11234567890|+12345678901|+13456789012,+14567890123,+15678901234</p>
<p>It will send three messages if you enter mobile numbers like this. First message will be a mass message to +11234567890,+12345678901 and +13456789012.</p>
<p>This will only work if your network operator supports mass MMS.</p>',
    'announcement_setting' => 'Announcement',
    'error_invalid_firebase_service_account_json' => 'The Firebase service account json is invalid!',
    'firebase_service_account' => 'Firebase Service Account',
    'canceled' => 'Canceled',
    'no_data' => 'No Data',
    'last_login_ip' => 'Last Login IP',
    'message_placeholder' => 'You can randomize the message by using spintax as shown below.
&quot;{Hi|Hello|Hey} %name%, How are you?&quot;',
    'ussd_delay' => 'Delay before USSD request',
    'how_to_get_it' => 'How to get it?',
    'tooltip_ussd' => 'A multistep USSD request wait up to 30 seconds for next input, so you can send another request with the input in that timeframe to go to the next step. For example, if you initiate a multistep request, and it accepts 1,2,3 or 4 as an input then you can send another request with one of the accepted input (1,2,3 or 4) and it will result in a request for that step.',
    'tooltip_ussd_delay' => 'You should set minimum 30 seconds delay if you are planning to use only single step USSD request. A multistep USSD request wait up to 30 seconds for next input, so if you send a new USSD request before that time, then it will be considered an input to the previous multistep request.',
    'ssl_verification' => 'SSL Verification',
    'off' => 'Off',
    'on_recommended' => 'On (Recommended)',
    'tooltip_only_shared_devices' => 'You can set it to 0 if you want the user to only use devices shared by Admin. It will prevent the user from adding her own device to the system.',
    'selected_users' => 'Selected Users',
    'demo_users' => 'Demo Users',
    'share_with' => 'Share With',
    'shared_devices' => 'devices shared by Admin',
    'skin_setting' => 'Skin',
    'black' => 'White',
    'black_light' => 'White Light',
    'blue' => 'Blue',
    'blue_light' => 'Blue Light',
    'green' => 'Green',
    'green_light' => 'Green Light',
    'purple' => 'Purple',
    'purple_light' => 'Purple Light',
    'red' => 'Red',
    'red_light' => 'Red Light',
    'yellow' => 'Yellow',
    'yellow_light' => 'Yellow Light',
    'add_numbers_blacklist_placeholder' => 'Enter one mobile number per line',
    'use_owner_settings' => 'Use device related settings (delay, delivery reports and progressive queue) set by Admin',
    'get_devices' => 'Get Devices',
    'use_progressive_queue_setting' => 'Send next message in campaign only after current message is processed by network operator',
    'hint' => 'Hint!',
    'blacklist_instruction' => 'Users can add their number to blacklist by replying "STOP" to your message. If you are also using devices shared by Admin, then they must use "STOP %userID%" instead of "STOP" to add their number to blacklist.',
    'error_no_valid_numbers_found' => 'There are no valid numbers found that can be added to blacklist.',
    'success_numbers_removed' => '%count% numbers removed successfully.',
    'success_number_removed' => '%count% number removed successfully.',
    'success_add_to_blacklist' => 'Successfully added to blacklist.',
    'remove_number_from_black_list_confirmation' => 'Are you sure you want to remove selected numbers from black list?',
    'add' => 'Add',
    'blacklist' => 'Blacklist',
    'unshareable' => 'Unshareable',
    'error_device_not_owned' => 'You can only send USSD requests using your own devices.',
    'no_one' => 'No One',
    'shared_with' => 'Shared With',
    'share_to_all' => 'Share to All',
    'success_device_shared' => 'Device shared/unshared successfully.',
    'users' => 'Users',
    'share_device' => 'Share Device',
    'all' => 'All',
    'error_unable_to_remove_plans' => 'Unable to remove selected plans.',
    'remove_plans_confirmation' => 'Are you sure you want to remove selected plans?',
    'success_plans_removed' => '%count% plans removed successfully.',
    'success_plan_removed' => '%count% plan removed successfully.',
    'success_regenerate_api_key' => 'Successfully regenerated API key.',
    'regenerate_api_key_confirmation' => 'Are you sure you want to regenerate API key? Your old key will stop working if you do this!',
    'resend_requests_confirmation' => 'Are you sure you want to resend selected USSD requests?',
    'success_ussd_requests_resent' => '%count% USSD requests resent successfully.',
    'success_ussd_request_resent' => '%count% USSD request resent successfully.',
    'move_contacts' => 'Move Contacts',
    'success_contacts_moved' => '%count% contacts moved successfully.',
    'success_contact_moved' => '%count% contact moved successfully.',
    'move_contacts_confirmation' => 'Are you sure you want to move selected contacts?',
    'move' => 'Move',
    'new_user_report_delivery_setting' => 'Request a delivery report for each message sent by the user (Only SMS)',
    'tooltip_sleep_time' => 'If enabled, it will prevent sending all non prioritized messages in this time interval.',
    'appearance' => 'Appearance',
    'reset_password_link' => 'Reset Password Link',
    'user_limits_update' => 'User Limits Update',
    'subject' => 'Subject',
    'unsubscribe' => 'Unsubscribe',
    'copyright' => 'Copyright',
    'android_app' => 'Android App',
    'links' => 'Links',
    'default_settings_new_users' => "Default settings for new users",
    'new_user_limitations' => "New user limitations",
    'message_footer_demo_users' => 'Message footer for demo users',
    'download_app' => 'Download App',
    'no_ussd_requests_found' => 'No USSD requests found.',
    'error_sim_not_exist' => 'Make sure you are logged in to app on the device #%deviceId% and there is sim present in slot %simSlot%. If you inserted it recently, then open the app on the phone to add it to the database.',
    'error_device_not_exist' => 'The device ID you specified is not found in the database, or you are not logged in to the app.',
    'success_ussd_requests_removed' => '%count% USSD requests removed successfully.',
    'success_ussd_request_removed' => '%count% USSD request removed successfully.',
    'success_sent_ussd_request' => 'Successfully sent USSD request.',
    'remove_requests_confirmation' => 'Are you sure you want to remove selected USSD requests?',
    'response_at' => 'Response At',
    'sent_at' => 'Sent At',
    'request' => 'Request',
    'ussd_requests' => 'USSD Requests',
    'ussd_request' => 'USSD Request',
    'send_ussd_request' => 'Send USSD request',
    'get_ussd_requests' => 'Get USSD requests',
    'ussd' => 'USSD',
    'error_missing_data' => 'Missing required data to unsubscribe from the list. Make sure you are copying the full URL from the message.',
    'error_paypal_requires_ssl' => 'You will need an SSL certificate installed for your domain or subdomain to use PayPal. If you already installed it, then make sure you are doing this from a URL starting with "https".',
    'tooltip_prioritize' => 'Send this message immediately, even if there is another campaign still in queue.',
    'add_device_instruction' => 'You can follow below steps to add your Android phone in the system.',
    'error_invalid_sleep_time' => 'Please provide valid sleep time value. Sleep time from and to value must be different.',
    'sleep_time_from' => 'Sleep Time From',
    'sleep_time_upto' => 'Sleep Time Upto',
    'sleep_time' => 'Sleep Time',
    'prioritize' => 'Prioritize',
    'success_contacts_list_updated' => 'Contacts List name updated successfully.',
    'edit_contacts_list' => 'Edit Contacts List',
    'edit_list' => 'Edit List',
    'error_creating_lock_file' => 'There is an error while creating lock file. Please make sure this script has necessary permissions to create a directory.',
    'refresh' => 'Refresh',
    'success_queued_contacts_import' => 'Contacts file import queued successfully.',
    'error_creating_directory' => 'There is an error while creating %name% directory. Please make sure this script has necessary permissions to create a directory.',
    'footer_text_label' => 'Footer text',
    'option' => 'Device Selection Criteria',
    'use_selected_devices' => 'Use Selected Devices',
    'use_all_devices' => 'Use Default SIM of All Devices',
    'use_all_sims' => 'Use All SIM From All Devices',
    'use_random_device' => 'Use a random device or SIM from selected devices',
    'tooltip_android_app_url' => 'This URL will be used as the link to the APK file in registration email and other places so make sure you change it to your APK URL.',
    'tooltip_mms' => 'MMS availability depends on your country and support from your network operator. The receiver must be capable of receiving MMS to receive it. This feature should not be confused with RCS or any other messaging capabilities provided by the device manufacturer (e.g. Samsung message, Mi message etc.).',
    'tooltip_attachments' => 'You can attach multiple files here but make sure you don\'t exceed the 600 KB total size limit. If the receiver still can\'t receive the MMS then you can try not exceeding 300 KB.',
    'attachments_invalid' => 'Image attachments you have provided are invalid.',
    'resend_messages' => 'Resend messages',
    'error_creating_upload_directory' => 'There is an error while creating upload directory. Make sure script have permission to create a folder.',
    'error_uploading_attachment' => 'There is an error while uploading attachments. Make sure script have permission to write to uploads folder.',
    'tooltip_attachments_links' => 'You can provide multiple image links by separating them with comma but make sure there is no space before or after the comma.',
    'attachments_links' => 'Links to attachments (*.png, *.jpg, *.gif, *.aac, *.3gp, *.amr, *.mp3, *.m4a, *.wav, *.mp4, *.txt, *.vcf, *.html)',
    'attachments' => 'Attachments (*.png, *.jpg, *.gif, *.aac, *.3gp, *.amr, *.mp3, *.m4a, *.wav, *.mp4, *.txt, *.vcf, *.html)',
    'type' => 'Type',
    'success_device_name_updated' => 'Device name saved successfully.',
    'edit_device' => 'Edit Device',
    'send_received_messages_to_email' => 'Send received messages to email',
    'error_server_requirements_not_met' => 'Your server doesn\'t meet the requirements. Please contact your server administrator to install missing dependencies.',
    'next' => 'Next',
    'none' => 'None',
    'success_templates_removed' => '%count% templates removed successfully.',
    'success_template_removed' => '%count% template removed successfully.',
    'success_update_template' => 'SMS template updated successfully.',
    'success_add_template' => 'SMS template added successfully.',
    'remove_templates_confirmation' => 'Are you sure you want to remove selected sms templates?',
    'manage_templates' => 'Manage templates',
    'add_template' => 'Add template',
    'templates' => 'Templates',
    'template' => 'Template',
    'tooltip_delay' => 'You can assign range for random delay. For example, if you assign 20-40 as the delay value then the delay will be chosen randomly for each message and it will be between 20 and 40 secs.',
    'error_invalid_delay' => 'The delay should be numeric (e.g. 20) or represent the range (e.g. 20-60).',
    'error_max_delay_smaller' => 'The maximum delay should be bigger than minimum delay.',
    'error_disposable_email_address' => 'Please use a non-disposable email address for registration!',
    'placeholder_auto_responder_message' => 'You can use | symbol to separate multiple messages. If you message contains | then you can use \| instead of |.',
    'tooltip_total_cycles' => 'Enable it to limit the number of times the subscription will be charged. If you don\'t enable it then a subscription for this plan will stay active until it\'s cancelled.',
    'cancelled' => 'cancelled',
    'renews_until' => 'Renews until',
    'refund' => 'Refund',
    'error_payment_not_found' => 'Unable to find the payment!',
    'success_payment_refunded' => 'Successfully refunded the payment.',
    'refund_payment_confirmation' => 'Are you sure you want to refund this payment?',
    'error_user_already_subscribed' => 'Unable to create a new subscription because there is an active subscription already exists for this user.',
    'success_create_subscription' => 'Successfully added a new subscription.',
    'year' => 'Year',
    'month' => 'Month',
    'week' => 'Week',
    'create_subscription' => 'Create Subscription',
    'error_total_cycles_not_number' => 'Total cycles should be a number and greater than zero.',
    'total_cycles' => 'Total Cycles',
    'excel_message_placeholder' => 'You can use a placeholder as shown in the following message and it will replace it automatically with an appropriate value. It also supports randomizing message using spintax.
&quot;{Hi|Hello|Hey} %name%, How are you?&quot;
In the above example, the system will replace %name% with data from the Name column. You must have Name column in your Excel file for it to work.',
    'do_not_have_an_account' => 'Don\'t have an account?',
    'user' => 'User',
    'from_name' => 'From name',
    'from_address' => 'From email address',
    'tooltip_unsubscribe_url' => 'You can add a shortened URL for the unsubscribe URL to save space in your message.',
    'tooltip_max_retries' => 'The maximum number of times the system should retry sending failed messages.',
    'tooltip_retry_time_interval' => 'The amount of time between each retry of failed messages.',
    'smtp_debug_information' => 'SMTP debug information',
    'show_debug_info' => 'Show debug information',
    'tooltip_message_multiple' => 'Only mobile numbers will be used from excel file when you enter anything in this field.',
    'buy_now' => 'Buy Now',
    'favicon' => 'Favicon',
    'error_uploading_logo' => 'There is an error while uploading logo. Make sure script have permission to write to uploads folder.',
    'logo' => 'Logo',
    'expires_after' => 'Expires After',
    'add_unsubscribe_link' => 'Add Unsubscribe Link',
    'cycles_competed' => 'Cycles Completed',
    'respond_if' => 'Respond if',
    'exact_case_sensitive' => 'Exact match (Case Sensitive)',
    'exact_case_insensitive' => 'Exact match (Case Insensitive)',
    'contains' => 'Contains the message',
    'regular_expression' => 'Matches the regular expression',
    'success_update_response' => 'Response updated successfully.',
    'edit_response' => 'Edit Response',
    'success_responses_removed' => '%count% responses removed successfully.',
    'success_response_removed' => '%count% response removed successfully.',
    'remove_responses_confirmation' => 'Are you sure you want to remove selected responses?',
    'error_unable_to_remove_responses' => 'Unable to remove selected Responses',
    'response' => 'Response',
    'success_add_response' => 'Response added successfully.',
    'responses' => 'Responses',
    'add_response' => 'Add Response',
    'auto_responder' => 'Auto Responder',
    'unlimited_credits' => 'unlimited credits',
    'credit' => 'credit',
    'error_contacts_limit_reached' => 'Max number of contacts allowed for this account is exceeded.',
    'contact' => 'contact',
    'unlimited_contacts' => 'unlimited contacts',
    'unlimited_devices' => 'unlimited devices',
    'max_contacts' => 'Max Contacts',
    'error_max_contacts_not_number' => 'Max number of contacts should be a number.',
    'earnings' => 'Earnings',
    'active_subscriptions' => 'Active Subscriptions',
    'auto_retry_setting' => 'Auto retry failed messages',
    'hour' => 'hour',
    'hours' => 'hours',
    'day' => 'day',
    'minutes' => 'minutes',
    'retry_time_interval_label' => 'Retry time interval',
    'max_retries_label' => 'Max retries',
    'error_subscription_expired' => 'Your subscription has expired. Renew your subscription to keep using this application.',
    'no' => 'No',
    'yes' => 'Yes',
    'all_time' => 'All Time',
    'never' => 'Never',
    'days' => 'Days',
    'cancel_subscription_confirmation' => 'Are you sure you want to cancel this subscription?',
    'id' => 'ID',
    'reply' => 'Reply',
    'unlimited' => 'Unlimited',
    'forever' => 'end of the world',
    'expiry_date' => 'Expiry Date',
    'payment_id' => 'Payment ID',
    'amount' => 'Amount',
    'transaction_fee' => 'Transaction Fee',
    'payments' => 'Payments',
    'payment_method' => 'Payment Method',
    'subscribed_date' => 'Subscribed Date',
    'renew_date' => 'Renew Date',
    'subscription_id' => 'Subscription ID',
    'plan_already_exists' => 'Plan with same name already exists.',
    'switch_plan' => 'Switch Plan',
    'success_subscription_canceled' => 'Successfully canceled subscription.',
    'error_subscription_not_found' => 'Unable to find subscription!',
    'success_subscribed' => 'You have successfully subscribed to subscription plan.',
    'cancel' => 'Cancel',
    'subscribe' => 'Subscribe',
    'subscriptions' => 'Subscriptions',
    'success_edit_plan' => 'Successfully updated the subscription plan.',
    'enabled' => 'Enabled',
    'billing_cycle' => 'Billing Cycle',
    'success_create_plan' => 'Successfully added a new subscription plan.',
    'edit_plan' => 'Edit Plan',
    'frequency_unit' => 'Frequency Unit',
    'frequency' => 'Frequency',
    'currency' => 'Currency',
    'price' => 'Price',
    'add_plan_form_title' => 'Add Plan',
    'plans' => 'Plans',
    'plan' => 'Plan',
    'error_updating_config' => 'Unable to update configuration file. Make sure this script has proper permission to update a file.',
    'enable_registration' => 'Enable registration',
    'error_invalid_integer_value' => 'Invalid value supplied for %field%. It should be a valid numerical value.',
    'credentials_type' => 'Credentials type',
    'client_id' => 'Client ID',
    'secret' => 'Secret',
    'payment_gateway' => 'Payment Gateway',
    'emails' => 'Emails',
    'success_save_settings' => 'Settings saved successfully.',
    'general' => 'General',
    'registration' => 'Registration',
    'site_name' => 'Site name',
    'site_description' => 'Site description',
    'copyright_name' => 'Copyright name',
    'language' => 'Language',
    'secret_key' => 'Secret key',
    'site_key' => 'Site key',
    'hostname' => 'Hostname',
    'port' => 'Port',
    'encryption' => 'Encryption',
    'debug' => 'Debug',
    'username' => 'Username',
    'application_copyright' => '<strong>Copyright &copy; 2018-%present% <a href="%url%" id="company_url">%name%</a></strong>. All rights reserved.',
    'add_device_instructions' => 'You can follow below steps to add your Android phone in the system.',
    'add_device_step_1' => 'Download the latest version of Android app from <a href="%app_url%">here</a>. It is better if you download it directly into your phone.',
    'add_device_step_2' => 'Install it on your phone. If you don\'t know how to install the apps not available on Play Store then you can follow <a href="https://android.gadgethacks.com/how-to/android-101-sideload-apps-by-enabling-unknown-sources-install-unknown-apps-0161947/" target="_blank">this guide</a>.',
    'add_device_step_3' => 'Open the app after the installation, you will be asked to give bunch of permissions. Just click Allow or Yes on all prompts you receive and you will be presented with login window. Touch the "SIGN IN USING QR CODE" button to scan the QR code shown below.',
    'add_device_step_4' => 'Congratulations, You successfully added your Android phone in to the system. Now just open the Sender page using navigation menu and start sending messages.',
    'add_device' => 'Add Device',
    'error_parsing_qr_code' => 'There is an error while parsing QR code. Please try to scan it again.',
    'null_value' => 'Unknown',
    'android_version' => 'Android Version',
    'app_version' => 'App Version',
    'error_invalid_schedule_time' => 'Schedule time should be greater than current time.',
    'success_messages_scheduled' => '%count% messages scheduled successfully.',
    'success_message_scheduled' => '%count% message scheduled successfully.',
    'success_scheduled' => 'Message scheduled successfully.',
    'faqs' => 'FAQs',
    'support' => 'Support',
    'faqs_link' => 'You can find frequently asked questions from <a target="_blank" href="https://support.rbsoft.org/knowledgebase/355">here</a>.',
    'support_link' => 'If FAQs doesn\'t resolve the issue you are experiencing then you can open a support ticket from <a target="_blank" href="https://support.rbsoft.org">here</a>.',
    'cron_job_instructions' => 'Add a cron job by executing below command in linux shell. If you are using the Cpanel or Vesta Panel then follow a guide from <a target="_blank" href="https://smsgateway.rbsoft.org/docs/setting_up_the_cron_job_1.htm">here</a> to setup a cron job.',
    'cron_job' => 'Cron job for scheduled messages',
    'success_installation' => 'Your installation has been completed successfully.',
    'schedule' => 'Schedule',
    'scheduled' => 'Scheduled',
    'tooltip_schedule' => 'Schedule uses timezone you set on your profile page so make sure it is same as your device timezone.',
    'error_missing_excel_file' => 'Please browse and select the excel file you want to upload.',
    'send_message_to_contacts' => 'Send a message to Contacts List',
    'contacts_message_placeholder' => 'You can use a placeholder as shown in the following message and it will replace it automatically with an appropriate value. It also supports randomizing message using spintax.
&quot;{Hi|Hello|Hey} %name%, How are you?&quot;',
    'change_subscription' => 'Change Subscription',
    'manage_contacts' => 'Manage Contacts',
    'subscribed' => 'Subscribed',
    'remove_list' => 'Remove List',
    'contacts_list' => 'Contacts List',
    'import_contacts' => 'Import Contacts',
    'unsubscribe_link' => 'To unsubscribe click on the following link or reply "UNSUBSCRIBE %listId%".\n%unsubscribeURL%?number=%number%&listID=%listID%',
    'error_invalid_list_id' => 'Invalid List ID! Unable to find the contacts list.',
    'change_subscription_confirmation' => 'Are you sure you want to change subscription status of selected contacts?',
    'error_already_unsubscribed' => 'This number is already unsubscribed from the list.',
    'error_no_subscribers' => 'There are no subscribers present in the contacts list.',
    'error_not_a_subscriber' => 'This number is not present in the subscribers list.',
    'error_invalid_number' => 'This mobile number is not found in the list. Please provide valid mobile number.',
    'success_unsubscribed' => 'You have been unsubscribed from the contacts list.',
    'success_contacts_subscription_changed' => 'Successfully changed subscription status for %count% contacts.',
    'success_contact_subscription_changed' => 'Successfully changed subscription status for %count% contact.',
    'success_contacts_removed' => '%count% contacts removed successfully.',
    'success_contacts_list_removed' => 'Contacts list removed successfully.',
    'success_contact_removed' => '%count% contact removed successfully.',
    'remove_contacts_confirmation' => 'Are you sure you want to remove selected contacts?',
    'error_no_contacts_found' => 'There are no new contacts found in uploaded file.',
    'success_contacts_saved' => '%count% contacts added successfully.',
    'success_contact_saved' => '%count% contact added successfully.',
    'error_contacts_list_exist' => 'Contacts list with this name already exists. Please provide a unique name.',
    'error_contact_exist' => 'This mobile number already exists in the contacts list.',
    'success_new_contact' => 'Added new contact successfully.',
    'error_list_name_required' => 'Contact list name must not be empty.',
    'success_create_list' => 'Successfully added a new contacts list.',
    'remove_contacts_list_confirmation' => 'Are you sure you want to remove selected contacts list?',
    'add_contact' => 'Add Contact',
    'contacts' => 'Contacts',
    'create_list' => 'Create Contacts List',
    'list_name' => 'Name of the Contacts List',
    'create' => 'Create',
    'delivered' => 'Delivered',
    'default' => 'Default',
    'sim' => 'SIM',
    'error_no_sim_present' => 'There is no SIM card present on slot index %slot%. Please restart the SMS Gateway app on your Android device if you recently inserted the SIM.',
    'sent_date' => 'Sent Date',
    'delivered_date' => 'Delivered Date',
    'sign_in' => 'Sign in',
    'sign_in_message' => 'Sign in to start your session',
    'forget_password_link' => 'I forgot my password',
    'error_removing_upgrade_script' => '%type% is successful but There is an error while removing "upgrade.php". Please remove it manually and after that click <a href="index.php">here</a> to login.',
    'error_removing_install_directory' => 'There is an error while removing "install" directory. Please remove it manually to continue.',
    'error_incorrect_credentials' => 'This email or password is incorrect.',
    'error_incorrect_api_key' => 'This API key is invalid.',
    'success_update' => 'Successfully updated to v%version%. Click <a href="index.php">here</a> to login.',
    'purchase_code' => 'Envato Purchase Code',
    'dashboard' => 'Dashboard',
    'more_info' => 'More info',
    'sent' => 'Sent',
    'failed' => 'Failed',
    'available' => 'Available Credits',
    'used' => 'Credits Used',
    'queued' => 'Queued',
    'pending' => 'Pending',
    'profile' => 'Profile',
    'edit_user' => 'Edit User',
    'close' => 'Close',
    'save_changes' => 'Save changes',
    'add_webhook' => 'Add WebHook for received messages',
    'webhook_url' => 'WebHook URL',
    'api' => 'API',
    'api_test' => 'Generate Link to Test API',
    'api_key' => 'API Key',
    'generate_link' => 'Generate Link',
    'manage_users' => 'Manage Users',
    'select_user' => 'Select User',
    'messages' => 'Messages',
    'sender' => 'Sender',
    'devices' => 'Devices',
    'select_device' => 'Select Device',
    'online' => 'Online',
    'download_example_excel' => 'Download example file',
    'installation' => 'Installation',
    'installation_of_app' => 'Installation of %app%',
    'toggle_navigation' => 'Toggle Navigation',
    'error_creating_config' => 'Unable to create configuration file. Make sure this script has proper permission to create a file.',
    'error_device_not_found' => 'This device doesn\'t exist in database.',
    'error_email_registered' => 'This email is already registered.',
    'error_credits_depleted' => 'You have insufficient credits to complete this operation. Please get more credits to continue.',
    'error_send_email_register' => 'There is an error while sending registration information email. %errorMessage%',
    'success_registration' => 'User added successfully to the database. They will receive registration information via email soon.',
    'add_user_form_title' => 'Add New User',
    'error_dialog_title' => 'Error!',
    'success_dialog_title' => 'Success',
    'name' => 'Name',
    'error_name_empty' => 'Name must not be empty.',
    'device_name' => 'Device Name',
    'email' => 'Email',
    'device_model' => 'Device Model',
    'password' => 'Password',
    'confirm_email' => 'Confirm Email',
    'create_user' => 'Create User',
    'remove' => 'Remove',
    'database_server' => 'Database Server',
    'database_name' => 'Database Name',
    'database_user' => 'Database User',
    'database_password' => 'Database Password',
    'install' => 'Install',
    'total_messages' => 'Total Messages',
    'connected_devices' => 'Connected Devices',
    'mobile_numbers' => 'Mobile Numbers',
    'mobile_numbers_placeholder' => '+911234567890,+912345678901',
    'device_status' => 'Device Status',
    'date_added' => 'Date Added',
    'error_uploading_excel_file' => 'There was a problem uploading this file. Please try again later.',
    'error_no_device_selected' => 'Please select at least one device to send messages.',
    'error_unable_to_remove_users' => 'Unable to remove selected users.',
    'error_unable_to_remove_devices' => 'Unable to remove selected devices.',
    'remove_devices_confirmation' => 'Are you sure you want to remove selected devices?',
    'remove_messages_confirmation' => 'Are you sure you want to remove selected messages?',
    'remove_users_confirmation' => 'Selected users will be removed along with their messages. Are you sure you want to remove selected users?)',
    'search_form_title' => 'Search Messages',
    'success_edit_user' => 'The data saved successfully for user %name% [<a href="mailto:%email%">%email%</a>].',
    'all_users' => 'All Users',
    'all_devices' => 'All Devices',
    'unknown_device' => 'Unknown',
    'credits' => 'Credits',
    'max_devices' => 'Max Devices',
    'mobile_number' => 'Mobile Number',
    'last_login' => 'Last Login',
    'split' => 'Split messages between devices',
    'status' => 'Status',
    'status_all' => 'All',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'page_no' => 'Page No.',
    'select_all' => 'Select all',
    'search' => 'Search',
    'export' => 'Export',
    'resend' => 'Resend',
    'delay' => 'Delay',
    'timezone' => 'Time Zone',
    'device' => 'Device',
    'save' => 'Save',
    'page_limit' => 'Number of messages per page',
    'received' => 'Received',
    'primary_device' => 'Primary Device',
    'device_settings' => 'Device Settings',
    'messages_count' => 'Showing ${start}-${end} of ${result.totalCount} messages',
    'no_messages_found' => 'No messages found.',
    'error_dialog_message' => 'The operation could not be completed.',
    'success_dialog_message' => 'The operation completed successfully.',
    'error_devices_limit_reached' => 'Limit of active devices allowed for this account is reached.',
    'error_max_devices_not_number' => 'Max number of devices should be a number.',
    'error_credits_not_number' => 'Message credits should be a number.',
    'error_password_incorrect' => 'Current password is incorrect.',
    'error_delay_limit' => "Delay must be between 0 to 120 seconds.",
    'error_delay_not_numeric' => "Delay must be numeric.",
    'error_device_name' => 'Device name should be less than 25 characters long.',
    'success_device_settings' => 'Device settings saved successfully',
    'success_password_changed' => 'Your password has been changed successfully!',
    'success_settings_changed' => 'Your settings have been changed successfully.',
    'change_password' => 'Change Password',
    'current_password' => 'Current Password',
    'new_password' => 'New Password',
    'confirm_password' => 'Confirm Password',
    'settings' => 'Settings',
    'delay_setting' => 'Delay between messages (0-120)',
    'report_delivery_setting' => 'Request a delivery report for each message you send (Only SMS)',
    'success_devices_removed' => '%count% devices removed successfully.',
    'success_device_removed' => '%count% device removed successfully.',
    'success_users_removed' => '%count% users removed successfully.',
    'success_user_removed' => '%count% user removed successfully.',
    'success_messages_removed' => '%count% messages removed successfully.',
    'success_message_removed' => '%count% message removed successfully.',
    'success_messages_sent' => '%count% messages sent successfully.',
    'success_message_sent' => '%count% message sent successfully.',
    'error_zero_messages' => 'There are no messages to send.',
    'error_invalid_request_format' => 'Invalid request format.',
    'error_use_valid_number' => 'Please use a valid Mobile Number.',
    'reset_password' => 'Reset Password',
    'error_email_not_exist' => 'This email doesn\'t exist in the database.',
    'error_link_expired' => 'Your password reset link is expired.',
    'error_link_invalid' => 'Your password reset link is invalid.',
    'error_send_email_reset_password' => 'There is an error while sending password reset information.',
    'success_reset_password' => 'Your password has been reset successfully! You will receive new login credentials via email soon.',
    'success_password_reset_link' => 'You will receive password reset link via email soon.',
    'forget_password' => 'Forget Password',
    'reset_your_password' => 'Reset your password',
    'message' => 'Message',
    'send' => 'Send',
    'send_multiple_messages' => 'Send Multiple Messages',
    'send_message' => 'Send Message',
    'php_integration' => 'PHP Integration',
    'php_integration_instruction' => 'Include following code in your PHP file to start sending messages.',
    'webhook_example' => 'WebHook Example Script',
    'webhook_instruction' => 'Create a script with the following content and provide its URL as WebHook.',
    'success_save_webhook' => 'WebHook saved successfully.',
    'error_invalid_webhook_url' => 'Please provide valid URL for WebHook.',
    'success_remove_webhook' => 'WebHook removed successfully.',
    'send_single_message' => 'Send Single Message',
    'send_bulk_messages' => 'Send Bulk Messages',
    'get_balance' => 'Get remaining message credits',
    'get_messages' => 'Get messages and their current status',
    'get_credits' => 'Get Credits',
    'c#_integration' => 'C# Integration (<a href="https://github.com/ravibpatel/Gateway-Sample-Application" target="_blank">Example Project</a>)',
    'c#_integration_instruction' => 'Just create a file named Gateway.cs in your project and paste the following code into it. You also need to add <a href="https://www.nuget.org/packages/Newtonsoft.Json" target="_blank">Newtonsoft.Json NuGet package</a> to your project.',
    'error_missing_fields' => 'All fields are required.',
    'success_sent' => 'Message sent successfully.',
    'disconnected' => 'Disconnected',
    'connected' => 'Connected',
    'success_register' => 'Thank you for taking interest in %app%. You will receive registration information via email soon.',
    'register' => 'Register',
    'register_demo' => 'Register for a demo',
    'register_sign_in_link' => 'I already have a membership',
    'error_captcha_failed' => 'Captcha verification failed, please try again.',
    'error_no_active_device_found' => 'No active device found associated with this user account.',
    'error_unable_to_connect_user' => 'Unable to connect to the device. Make sure you are logged in to the application on %device%.',
    'error_unable_to_connect_other' => 'Unable to connect to the device. Make sure user %user%(%userEmail%) is logged in to the application on %device%.',
    'error_no_messages_found' => 'There are no messages found in uploaded file.',
    'error_nothing_to_update' => 'There are no changes to update.',
    'error_blocked_file_extension' => 'Unable to upload file. This file extension is not allowed.',
    'upload_excel_file' => 'Drop excel file here or click to upload.',
    'upload_excel_file_mobile' => 'Touch here to upload excel file.',
    'register_email_subject' => '%app% Admin Panel | Registration Information',
    'register_email_body' => '<p>Hi %user%,</p>

<p>Welcome to the %app% Admin Panel.</p>

<p>Your registration has been confirmed and you now have access to %app% Admin Panel.</p>

<p>
Your Login:<br/>
Server: <a href="%server%">%server%</a><br/>
Email: %userEmail%<br/>
Password: %password%<br/>
</p>

<p>You need to download the android app from <a href="%appUrl%">here</a> and enter the above information to get started. If you don\'t know how to sideload apps then you can follow the <a href="https://android.gadgethacks.com/how-to/android-basics-enable-unknown-sources-sideload-apps-0161947/">guide to sideloading apps</a>.</p>

<p>You can sign in using credentials provided above or you can use the "SIGN IN USING QR CODE" button and scan the QR code in the attachment to sign in automatically.</p>

<p>You have %credits%. You can send messages using %devices%. You can store %contacts%. Your credits are valid until %expiryDate%. If you like to change any of these limits then please contact <a href="mailto:%adminEmail%">%adminEmail%</a>.</p>

<p>
Regards,<br/>
%admin%
</p>',

    'reset_password_email_subject' => '%app% Admin Panel | Reset Password',
    'reset_password_email_body' => '<p>Hi %user%,</p>
    
<p>Your password has been reset successfully. You can log in to %app% Admin Panel using the following credentials.</p>
    
<p>
Your Login:<br/>
Server: <a href="%server%">%server%</a><br/>       
Email: %userEmail%<br/>
Password: %password%<br/>
</p>

<p>If you have any questions, please contact <a href="mailto:%adminEmail%">%adminEmail%</a>.</p>
    
<p>
Regards,<br/>
%admin%<br/>
</p>',
    'reset_password_link_email_subject' => '%app% Admin Panel | Password Reset Link',
    'reset_password_link_email_body' => '<p>Hi %user%,</p>
        
<p>Please use the following link to reset your password. This link will expire after 24 hours.</p>

<h2><a href="%server%/reset-password.php?email=%userEmail%&code=%code%">Click here to reset your password</a></h2>
        
<p>If you have any questions, please contact <a href="mailto:%adminEmail%">%adminEmail%</a>.</p>
        
<p>
Regards,<br/>
%admin%<br/>
</p>',
    'edit_user_subject' => '%app% | Updated Limits',
    'edit_user_email_body' => '<p>Hi %user%,</p>

<p>You have now %credits%. You can send messages using %devices%. You can store %contacts%. Your credits are valid until %expiryDate%.</p>
    
<p>If you have any questions, please contact <a href="mailto:%adminEmail%">%adminEmail%</a>.</p>
    
<p>
Regards,<br/>
%admin%<br/>
</p>'
];

// Application specific
include __DIR__ . "/../app.php";

// Internal
if (file_exists(__DIR__ . "/../internal.php")) {
    include __DIR__ . "/../internal.php";
}