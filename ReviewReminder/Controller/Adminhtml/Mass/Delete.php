<?php

namespace PHPStudios\ReviewReminder\Controller\Adminhtml\Mass;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Ui\Component\MassAction\Filter;
use PHPStudios\ReviewReminder\Api\ReviewReminderRepositoryInterface;
use PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder\CollectionFactory;

/**
 * Class Delete
 * @package PHPStudios\ReviewReminder\Controller\Adminhtml\Mass
 */
class Delete extends \Magento\Backend\App\Action
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
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ReviewReminderRepositoryInterface $reviewReminderRepositoryInterface
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ReviewReminderRepositoryInterface $reviewReminderRepositoryInterface
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->reviewReminderRepositoryInterface = $reviewReminderRepositoryInterface;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $itemsDeleted = 0;
        foreach ($collection->getAllIds() as $id) {
            try {
                $this->reviewReminderRepositoryInterface->deleteById($id);
            } catch (CouldNotDeleteException $exception) {
                $this->messageManager->addErrorMessage(__($exception->getMessage()));
            }
            $itemsDeleted++;
        }
        if ($itemsDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $itemsDeleted)
            );
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/index/index');
    }
}
