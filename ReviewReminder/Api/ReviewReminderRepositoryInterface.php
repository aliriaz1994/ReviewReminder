<?php

namespace PHPStudios\ReviewReminder\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPStudios\ReviewReminder\Api\Data\ReviewReminderInterface;

/**
 * Interface ReviewReminderRepositoryInterface
 * @package PHPStudios\ReviewReminder\Api
 */
interface ReviewReminderRepositoryInterface
{
    /**
     * @param ReviewReminderInterface $reminder
     * @return ReviewReminderInterface
     * @throws CouldNotSaveException
     */
    public function save(ReviewReminderInterface $reminder);

    /**
     * @param $id
     * @return ReviewReminderInterface
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param $order_id
     * @return ReviewReminderInterface
     * @throws NoSuchEntityException
     */
    public function getByOrderId($order_id);

    /**
     * @param ReviewReminderInterface $reminder
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ReviewReminderInterface $reminder);

    /**
     * @param $id
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById($id);

    /**
     *  getList
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
