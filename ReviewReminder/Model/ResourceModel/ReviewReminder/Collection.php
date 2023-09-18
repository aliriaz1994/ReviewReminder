<?php

namespace PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use PHPStudios\ReviewReminder\Api\Data\ReviewReminderInterface;

/**
 * Class Collection
 * @package PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = ReviewReminderInterface::REMINDER_ID;

    /**
     * Declaration of Model and Resource Model
     */
    public function _construct()
    {
        $this->_init(
            \PHPStudios\ReviewReminder\Model\ReviewReminder::class,
            \PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder::class
        );
    }
}
