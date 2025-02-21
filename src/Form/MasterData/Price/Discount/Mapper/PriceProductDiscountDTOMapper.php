<?php

namespace Silecust\WebShop\Form\MasterData\Price\Discount\Mapper;

use Silecust\WebShop\Entity\PriceProductDiscount;
use Silecust\WebShop\Form\MasterData\Price\Discount\DTO\PriceProductDiscountDTO;
use Silecust\WebShop\Repository\CurrencyRepository;
use Silecust\WebShop\Repository\PriceProductDiscountRepository;
use Silecust\WebShop\Repository\ProductRepository;

readonly class PriceProductDiscountDTOMapper
{


    public function __construct(private ProductRepository              $productRepository,
                                private CurrencyRepository             $currencyRepository,
                                private PriceProductDiscountRepository $priceBaseProductRepository
    )
    {
    }

    public function mapDtoToEntity(PriceProductDiscountDTO $priceBaseProductDTO): PriceProductDiscount
    {

        $product = $this->productRepository->findOneBy(['id' => $priceBaseProductDTO->productId]);
        $currency = $this->currencyRepository->findOneBy(['id' => $priceBaseProductDTO->currencyId]
        );
        $priceBase = $this->priceBaseProductRepository->create($product, $currency);
        $priceBase->setValue($priceBaseProductDTO->value);
        return $priceBase;

    }

    public function mapDtoToEntityForEdit(PriceProductDiscountDTO $priceBaseProductDTO, ?PriceProductDiscount $priceBase): PriceProductDiscount
    {

        $priceBase->setValue($priceBaseProductDTO->value);

        return $priceBase;
    }

    public function mapToDtoFromEntityForEdit(PriceProductDiscount $priceBase): PriceProductDiscountDTO
    {

        $priceProductDiscountDTO = new PriceProductDiscountDTO();
        $priceProductDiscountDTO->id = $priceBase->getId();
        $priceProductDiscountDTO->productId = $priceBase->getProduct()->getId();
        $priceProductDiscountDTO->currencyId = $priceBase->getCurrency()->getId();
        $priceProductDiscountDTO->value = $priceBase->getValue();

        return $priceProductDiscountDTO;
    }
}