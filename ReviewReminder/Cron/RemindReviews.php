<?php

namespace PHPStudios\ReviewReminder\Cron;

use PHPStudios\ReviewReminder\Api\ReviewReminderRepositoryInterface;
use PHPStudios\ReviewReminder\Helper\Data;
use PHPStudios\ReviewReminder\Model\Reminder\EmailSender;
use PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder\CollectionFactory;

/**
 * Class RemindReviews
 * @package PHPStudios\ReviewReminder\Cron
 */
class RemindReviews
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var EmailSender
     */
    protected $emailSender;
    /**
     * @var ReviewReminderRepositoryInterface
     */
    protected $reviewReminderRepositoryInterface;

    /**
     * RemindReviews constructor.
     * @param Data $helperData
     * @param CollectionFactory $collectionFactory
     * @param EmailSender $emailSender
     * @param ReviewReminderRepositoryInterface $reviewReminderRepositoryInterface
     */
    public function __construct(
        Data $helperData,
        CollectionFactory $collectionFactory,
        EmailSender $emailSender,
        ReviewReminderRepositoryInterface $reviewReminderRepositoryInterface
    ) {
        $this->helperData = $helperData;
        $this->collectionFactory = $collectionFactory;
        $this->emailSender = $emailSender;
        $this->reviewReminderRepositoryInterface = $reviewReminderRepositoryInterface;
    }

    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $numberOfEmails = $this->helperData->getEmailPerCrone();
        $numberOfDays = $this->helperData->getDaysAfter();
        $requireDate = date(
            'Y-m-d h:i:s',
            strtotime(
                '-' . $numberOfDays . ' day',
                strtotime(
                    date('Y-m-d h:i:s')
                )
            )
        );
        /** @var \PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder\Collection $collection */
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter(
                'status',
                [
                    'eq' => \PHPStudios\ReviewReminder\Model\Config\Source\Status::NOT_SEND
                ]
            )
            ->addFieldToFilter('created_at', ['lteq' => $requireDate])
            ->setOrder('created_at', 'ASC')
            ->setPageSize($numberOfEmails);
        if ($collection->count()) {
            foreach ($collection as $item) {
                $this->emailSender->sendApprovalEmail($item);
                $reminder = $this->reviewReminderRepositoryInterface->getById($item->getReminderId());
                $reminder->setStatus(\PHPStudios\ReviewReminder\Model\Config\Source\Status::SENT);
                $this->reviewReminderRepositoryInterface->save($reminder);
            }
        }
    }
}
