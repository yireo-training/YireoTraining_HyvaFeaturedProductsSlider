<?php declare(strict_types=1);

namespace YireoTraining\HyvaFeaturedProductsSlider\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\ImageFactory as ProductImageFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class FeaturedProducts implements ArgumentInterface
{
    private ProductRepositoryInterface $productRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private ProductImageFactory $productImageFactory;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductImageFactory $productImageFactory
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productImageFactory = $productImageFactory;
    }

    /**
     * @return ProductInterface[]
     */
    public function getProducts(): array
    {
        $this->searchCriteriaBuilder->setPageSize(4);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->productRepository->getList($searchCriteria);
        return $searchResults->getItems();
    }

    /**
     * @return array[]
     */
    public function getProductImages(): array
    {
        $productImages = [];
        foreach ($this->getProducts() as $product) {
            /** @var Product $product */
            $productImage = $this->productImageFactory->create($product, 'product_page_main_image', []);
            $productImages[] = [
                'src' => $productImage->getImageUrl(),
                'alt' => $product->getName(),
                'caption' => $product->getName(),
            ];
        }

        return $productImages;
    }
}
