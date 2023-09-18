<?php

namespace PHPStudios\ReviewReminder\Model;

use Magento\Framework\Model\AbstractModel;
use PHPStudios\ReviewReminder\Api\Data\ReviewReminderInterface;

/**
 * Class ReviewReminder
 * @package PHPStudios\ReviewReminder\Model
 */
class ReviewReminder extends AbstractModel implements ReviewReminderInterface
{
    /**
     * Resource Model
     */
    public function _construct()
    {
        $this->_init(\PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder::class);
    }

    /**
     * @return int
     */
    public function getReminderId()
    {
        return $this->getData(ReviewReminderInterface::REMINDER_ID);
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->getData(ReviewReminderInterface::STATUS);
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->getData(ReviewReminderInterface::ORDER_ID);
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->getData(ReviewReminderInterface::CUSTOMER_EMAIL);
    }

    /**
     * @return mixed
     */
    public function getCustomerName()
    {
        return $this->getData(ReviewReminderInterface::CUSTOMER_NAME);
    }

    /**
     * @return int|mixed
     */
    public function getStoreID()
    {
        return $this->getData(ReviewReminderInterface::STORE_ID);
    }

    /**
     * @return mixed|string
     */
    public function getCreatedAt()
    {
        return $this->getData(ReviewReminderInterface::CREATED_AT);
    }

    /**
     * @return mixed|string
     */
    public function getUpdatedAt()
    {
        return $this->getData(ReviewReminderInterface::UPDATED_AT);
    }

    /**
     * @param $reminder_id
     * @return ReviewReminderInterface|ReviewReminder
     */
    public function setReminderId($reminder_id)
    {
        return $this->setData(ReviewReminderInterface::REMINDER_ID, $reminder_id);
    }

    /**
     * @param $status
     * @return ReviewReminderInterface|ReviewReminder
     */
    public function setStatus($status)
    {
        return $this->setData(ReviewReminderInterface::STATUS, $status);
    }

    /**
     * @param $order_id
     * @return ReviewReminderInterface|ReviewReminder
     */
    public function setOrderId($order_id)
    {
        return $this->setData(ReviewReminderInterface::ORDER_ID, $order_id);
    }

    /**
     * @param $customer_email
     * @return ReviewReminderInterface|ReviewReminder
     */
    public function setCustomerEmail($customer_email)
    {
        return $this->setData(ReviewReminderInterface::CUSTOMER_EMAIL, $customer_email);
    }

    /**
     * @param $customer_name
     * @return ReviewReminderInterface|ReviewReminder
     */
    public function setCustomerName($customer_name)
    {
        return $this->setData(ReviewReminderInterface::CUSTOMER_NAME, $customer_name);
    }

    /**
     * @param $store_id
     * @return ReviewReminderInterface|ReviewReminder
     */
    public function setStoreID($store_id)
    {
        return $this->setData(ReviewReminderInterface::STORE_ID, $store_id);
    }

    /**
     * @param $created_at
     * @return ReviewReminderInterface|ReviewReminder
     */
    public function setCreatedAt($created_at)
    {
        return $this->setData(ReviewReminderInterface::CREATED_AT, $created_at);
    }

    /**
     * @param $updated_at
     * @return ReviewReminderInterface|ReviewReminder
     */
    public function setUpdatedAt($updated_at)
    {
        return $this->setData(ReviewReminderInterface::UPDATED_AT, $updated_at);
    }
}
