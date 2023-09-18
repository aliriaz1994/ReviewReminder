<?php

namespace PHPStudios\ReviewReminder\Model\Reminder;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Url;
use Magento\Store\Model\StoreManagerInterface;
use PHPStudios\ReviewReminder\Helper\Data;

/**
 * Class EmailSender
 * @package PHPStudios\ReviewReminder\Model\Reminder
 */
class EmailSender
{
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var EncryptorInterface
     */
    private $encryptor;
    /**
     * @var Url
     */
    private $url;
    protected $helperData;
    /**
     * EmailSender constructor.
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param EncryptorInterface $encryptor
     * @param Url $url
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        EncryptorInterface $encryptor,
        Url $url,
        Data $helperData
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->encryptor = $encryptor;
        $this->url = $url;
        $this->helperData = $helperData;
    }
    /**
     * @param $reminderItem
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function sendApprovalEmail($reminderItem)
    {
        if (isset($reminderItem)) {
            $this->sendEmail(
                $this->helperData->getEmailTemplate(),
                $this->storeManager->getStore()->getId(),
                $reminderItem,
                $reminderItem->getCustomerEmail()
            );
        }
    }
    /**
     * @param $templateIdentifier
     * @param $storeId
     * @param $templateVars
     * @param $sendTo
     * @param string $area
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    private function sendEmail($templateIdentifier, $storeId, $templateVars, $sendTo, $area = Area::AREA_FRONTEND)
    {
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($templateIdentifier)
            ->setTemplateOptions(
                ['area' => $area, 'store' => $storeId]
            )
            ->setTemplateVars(['data' => $templateVars])
            ->setFromByScope(
                $this->helperData->getSenderEmail(),
                $storeId
            )
            ->addTo([$sendTo])
            ->getTransport();
        $transport->sendMessage();
    }
}
