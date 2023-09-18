<?php

namespace PHPStudios\ReviewReminder\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use PHPStudios\ReviewReminder\Api\Data\ReviewReminderInterface;

/**
 * Class ReviewReminder
 * @package PHPStudios\ReviewReminder\Model\ResourceModel
 */
class ReviewReminder extends AbstractDb
{
    /**
     * Declaration of Table and Primary Key
     */
    protected function _construct()
    {
        $this->_init('phpstudios_review_reminder', ReviewReminderInterface::REMINDER_ID);
    }
}
