<?php
declare(strict_types=1);

namespace Tarknaiev\BestBefore\Block\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Show
 * @package Tarknaiev\BestBefore\Block\Product
 */
class Show extends Template implements IdentityInterface
{
    const CACHE_TAG = 'best_before_block';

    /**
     * @var ProductRepositoryInterface/null
     */
    protected $currentProduct;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Show constructor.
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct (
        Context $context,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return ProductInterface/null
     */
    public function getProduct() :? ProductInterface
    {
        if (!$this->currentProduct) {
            $productId = $this->getRequest()->getParam('id');
            if (!$productId) {
                return null;
            }
            try {
                $this->currentProduct = $this->productRepository->getById($productId);
            } catch (NoSuchEntityException $e) {
                return null;
            }
        }

        return $this->currentProduct;
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities () : array
    {
        return [self::CACHE_TAG . '_' . $this->getBlockId()];
    }
}
