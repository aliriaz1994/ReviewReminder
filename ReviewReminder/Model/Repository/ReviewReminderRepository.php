<?php

namespace PHPStudios\ReviewReminder\Model\Repository;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use PHPStudios\ReviewReminder\Api\Data\ReviewReminderInterface;
use PHPStudios\ReviewReminder\Api\Data\ReviewReminderInterfaceFactory;
use PHPStudios\ReviewReminder\Api\ReviewReminderRepositoryInterface;
use PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder;
use PHPStudios\ReviewReminder\Model\ResourceModel\ReviewReminder\CollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ReviewReminderRepository
 * @package PHPStudios\ReviewReminder\Model\Repository
 */
class ReviewReminderRepository implements ReviewReminderRepositoryInterface
{

    /**
     * @var
     */
    private $reviewReminder;

    /**
     * @var ReviewReminderInterfaceFactory
     */
    protected $reviewReminderFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ReviewReminder
     */
    protected $resourceModel;

    /**
     * @var SearchResultsInterfaceFactory
     */
    protected $reviewReminderResultsFactory;

    /**
     * @var mixed
     */
    protected $collectionProcessor;

    /**
     * ReviewReminderRepository constructor.
     * @param ReviewReminder $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param ReviewReminderInterfaceFactory $reviewReminderFactory
     * @param SearchResultsInterfaceFactory $reviewReminderResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ReviewReminder $resourceModel,
        CollectionFactory $collectionFactory,
        ReviewReminderInterfaceFactory $reviewReminderFactory,
        SearchResultsInterfaceFactory $reviewReminderResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->reviewReminderFactory = $reviewReminderFactory;
        $this->reviewReminderResultsFactory = $reviewReminderResultsFactory;
        $this->collectionProcessor = $collectionProcessor ?:
            \Magento\Framework\App\ObjectManager::getInstance()->get(CollectionProcessorInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function save(ReviewReminderInterface $reminder)
    {
        try {
            if ($reminder->getReminderId()) {
                $reminder = $this->getById($reminder->getReminderId())->addData($reminder->getData());
            }
            $this->resourceModel->save($reminder);
            unset($this->reviewReminder[$reminder->getReminderId()]);

        } catch (\Exception $e) {
            if ($reminder->getReminderId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save Order with %1. error %2',
                        [$reminder->getReminderId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new Order. Error: %1', $e->getMessage()));
        }

        return $reminder;
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        if (!isset($this->reviewReminder[$id])) {
            $reminder=$this->reviewReminderFactory->create();
            $this->resourceModel->load($reminder, $id);
            if (!$reminder->getReminderId()) {
                throw new NoSuchEntityException(__('Review item with specified ID "%1" not found.', $id));
            }
            $this->reviewReminder[$id]=$reminder;
        }
        return $this->reviewReminder[$id];
    }

    /**
     * @inheritDoc
     */
    public function delete(ReviewReminderInterface $reminder)
    {
        try {
            $this->resourceModel->delete($reminder);
            unset($this->reviewReminder[$reminder->getReminderId()]);
        } catch (\Exception $e) {
            if ($reminder->getReminderId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove Review Reminder item with ID %1. Error: %2',
                        [$reminder->getReminderId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove Review Reminder item. Error: %1', $e->getMessage()));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id)
    {
        $reminder=$this->getById($id);
        $this->delete($reminder);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection=$this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult=$this->reviewReminderResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function getByOrderId($order_id)
    {
        $reminder=$this->reviewReminderFactory->create();
        $this->resourceModel->load($reminder, $order_id, ReviewReminderInterface::ORDER_ID);
        if (!$reminder->getReminderId()) {
            throw new NoSuchEntityException(__('Order with specified ID "%1" not found.', $order_id));
        }
        return $reminder;
    }
}
