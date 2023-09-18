<?php

namespace PHPStudios\ReviewReminder\Controller\Adminhtml\Mass;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use PHPStudios\ReviewReminder\Api\ReviewReminderRepositoryInterface;
use PHPStudios\ReviewReminder\Model\Reminder\EmailSender;
use PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder\CollectionFactory;

/**
 * Class Send
 * @package PHPStudios\ReviewReminder\Controller\Adminhtml\Mass
 */
class Send extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ReviewReminderRepositoryInterface
     */
    protected $reviewReminderRepositoryInterface;

    /**
     * @var EmailSender
     */
    protected $emailSender;

    /**
     * Send constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ReviewReminderRepositoryInterface $reviewReminderRepositoryInterface
     * @param EmailSender $emailSender
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ReviewReminderRepositoryInterface $reviewReminderRepositoryInterface,
        EmailSender $emailSender
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->reviewReminderRepositoryInterface = $reviewReminderRepositoryInterface;
        $this->emailSender = $emailSender;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $items = 0;
        foreach ($collection as $reminderItem) {
            try {
                $this->emailSender->sendApprovalEmail($reminderItem);
                $reminder = $this->reviewReminderRepositoryInterface->getById($reminderItem->getReminderId());
                $reminder->setStatus(\PHPStudios\ReviewReminder\Model\Config\Source\Status::SENT);
                $this->reviewReminderRepositoryInterface->save($reminder);
                $items++;
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage(__($exception->getMessage()));
            }
        }

        if ($items) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been updated.', $items)
            );
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/index/index');
    }
}
