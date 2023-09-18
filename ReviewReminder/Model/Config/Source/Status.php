<?php

namespace PHPStudios\ReviewReminder\Model\Config\Source;

/**
 * Class Status
 * @package PHPStudios\ReviewReminder\Model\Config\Source
 */
class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    const NOT_SEND = 0;
    const SENT = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('Not Send'), 'value' => self::NOT_SEND],
            ['label' => __('Sent'), 'value' => self::SENT]
        ];
    }
}
