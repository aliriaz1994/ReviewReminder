<?php

namespace PHPStudios\ReviewReminder\Observer\Order;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPStudios\ReviewReminder\Api\Data\ReviewReminderInterfaceFactory;
use PHPStudios\ReviewReminder\Api\ReviewReminderRepositoryInterface;
use PHPStudios\ReviewReminder\Helper\Data;

class AddReminder implements ObserverInterface
{
    /**
     * @var ReviewReminderRepositoryInterface
     */
    protected $reviewReminderRepository;

    /**
     * @var ReviewReminderInterfaceFactory
     */
    protected $reviewReminderInterfaceFactory;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * SaveOrder constructor.
     * @param ReviewReminderRepositoryInterface $reviewReminderRepository
     * @param ReviewReminderInterfaceFactory $reviewReminderInterfaceFactory
     * @param Data $helperData
     */
    public function __construct(
        ReviewReminderRepositoryInterface $reviewReminderRepository,
        ReviewReminderInterfaceFactory $reviewReminderInterfaceFactory,
        Data $helperData
    ) {
        $this->reviewReminderRepository = $reviewReminderRepository;
        $this->reviewReminderInterfaceFactory = $reviewReminderInterfaceFactory;
        $this->helperData = $helperData;
    }

    /**
     * @param Observer $observer
     * @return bool
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();

        /**
         * Get the Current Order Status and Compare it with configuration
         * Order Status, if the order status is equal to configuration order
         * status then check if current order is not already in our custom table
         * if not then add it to our table other wise ignore it.
         */
        $canProcessOrder = false;
        if ($order->getStatus() == $this->helperData->getOrderStatus()) {
            try {
                $this->reviewReminderRepository->getByOrderId($order->getId());
            } catch (NoSuchEntityException $exception) {
                $canProcessOrder = true;
            }
            if ($canProcessOrder) {
                try {
                    $reminder = $this->reviewReminderInterfaceFactory->create();
                    $reminder->setStatus(\PHPStudios\ReviewReminder\Model\Config\Source\Status::NOT_SEND);
                    $reminder->setStoreID($order->getStoreId());
                    $reminder->setCustomerEmail($order->getCustomerEmail());
                    $reminder->setCustomerName(
                        $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname()
                    );
                    $reminder->setOrderId($order->getId());
                    $this->reviewReminderRepository->save($reminder);
                } catch (CouldNotSaveException $exception) {
                    return false;
                }
            }
        }

        return true;
    }
}
