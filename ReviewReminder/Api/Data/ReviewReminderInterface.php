<?php

namespace PHPStudios\ReviewReminder\Api\Data;

/**
 * Interface ReviewReminder
 * @package PHPStudios\ReviewReminder\Api\Data
 */
interface ReviewReminderInterface
{
    const REMINDER_ID = "reminder_id";
    /**
     *
     */
    const STATUS = "status";
    /**
     *
     */
    const ORDER_ID = "order_id";
    /**
     *
     */
    const CUSTOMER_EMAIL = "customer_email";
    /**
     *
     */
    const CUSTOMER_NAME = "customer_name";
    /**
     *
     */
    const STORE_ID = "store_id";
    /**
     *
     */
    const CREATED_AT = "created_at";
    /**
     *
     */
    const UPDATED_AT = "updated_at";

    /**
     * @return int
     */
    public function getReminderId();

    /**
     * @return boolean
     */
    public function getStatus();

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @return mixed
     */
    public function getCustomerEmail();

    /**
     * @return mixed
     */
    public function getCustomerName();

    /**
     * @return int
     */
    public function getStoreID();

    /**
     * @return string|null Created-at timestamp.
     */
    public function getCreatedAt();

    /**
     * @return string|null Updated-at timestamp.
     */
    public function getUpdatedAt();

    /**
     * @param $review_id
     * @return ReviewReminderInterface
     */
    public function setReminderId($reminder_id);

    /**
     * @param $status
     * @return ReviewReminderInterface
     */
    public function setStatus($status);

    /**
     * @param $order_id
     * @return ReviewReminderInterface
     */
    public function setOrderId($order_id);

    /**
     * @param $customer_email
     * @return ReviewReminderInterface
     */
    public function setCustomerEmail($customer_email);

    /**
     * @param $customer_name
     * @return ReviewReminderInterface
     */
    public function setCustomerName($customer_name);

    /**
     * @param $store_id
     * @return ReviewReminderInterface
     */
    public function setStoreID($store_id);

    /**
     * @param $created_at
     * @return ReviewReminderInterface
     */
    public function setCreatedAt($created_at);

    /**
     * @param $updated_at
     * @return ReviewReminderInterface
     */
    public function setUpdatedAt($updated_at);
}
