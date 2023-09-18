<?php

namespace PHPStudios\ReviewReminder\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Escaper;

/**
 * Class EmailData
 * @package PHPStudios\ReviewReminder\Block
 */
class EmailData extends Template
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var ImageFactory
     */
    protected $productImageFactory;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * EmailData constructor.
     * @param Template\Context $context
     * @param OrderRepositoryInterface $orderRepository
     * @param ImageFactory $productImageFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Escaper $escaper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        OrderRepositoryInterface $orderRepository,
        ImageFactory $productImageFactory,
        ProductRepositoryInterface $productRepository,
        Escaper $escaper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->orderRepository = $orderRepository;
        $this->productImageFactory = $productImageFactory;
        $this->productRepository = $productRepository;
        $this->escaper = $escaper;
    }

    /**
     * @param $reminder
     * @return array
     */
    public function getProductsData($reminder)
    {
        $orderId = $reminder->getOrderId();
        try {
            $order = $this->orderRepository->get($orderId);
            $orderItems = [];
            /** @var \Magento\Sales\Model\Order\Item $item */
            foreach ($order->getAllVisibleItems() as $item) {
                try {
                    $product = $item->getProduct();
                    if (isset($product)) {
                        $orderItem = [
                            'name' => $item->getName(),
                            'sku' => $item->getSku(),
                            'url' => $item->getProduct()->getProductUrl(),
                            'product' => $item->getProduct()
                        ];
                        $orderItems[] = $orderItem;
                    } else {
                        $orderItems[] = [];
                    }
                } catch (\Exception $exception) {
                    $orderItems[] = [];
                }
            }
            return $orderItems;
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @param $product
     * @param $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductImage($product, $imageId, $attributes = [])
    {
        $product = $this->productRepository->getById($product->getId());
        return $this->productImageFactory->create($product, $imageId, $attributes);
    }

    /**
     * @return Escaper
     */
    public function getEscaper()
    {
        return $this->escaper;
    }
}
