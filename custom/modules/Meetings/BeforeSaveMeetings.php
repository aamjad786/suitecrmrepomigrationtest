<?php
if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class BeforeSaveMeetings {

    public function createReminderAndInvitee(&$bean, $event, $args) {

        if (empty($bean->fetched_row)) {

            $reminderBean = BeanFactory::newBean('Reminders');
            $reminderBean->popup = 1;
            $reminderBean->email = 0;
            $reminderBean->timer_popup = 900;
            $reminderBean->timer_email = 900;
            $reminderBean->related_event_module = "Meetings";
            $reminderBean->related_event_module_id = $bean->id;
            $reminderBean->save();

            $newInvitee = BeanFactory::newBean('Reminders_Invitees');
            $newInvitee->reminder_id = $reminderBean->id;
            $newInvitee->related_invitee_module = 'Users';
            $newInvitee->related_invitee_module_id = $bean->assigned_user_id;
            $newInvitee->save();
        }
    }
}
