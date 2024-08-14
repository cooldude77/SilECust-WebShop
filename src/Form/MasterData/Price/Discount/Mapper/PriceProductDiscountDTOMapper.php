<?php

namespace App\Form\MasterData\Price\Discount\Mapper;

use App\Entity\PriceProductDiscount;
use App\Form\MasterData\Price\Discount\DTO\PriceProductDiscountDTO;
use App\Repository\CurrencyRepository;
use App\Repository\PriceProductDiscountRepository;
use App\Repository\ProductRepository;

class PriceProductDiscountDTOMapper
{

    private ProductRepository $productRepository;
    private CurrencyRepository $currencyRepository;
    private PriceProductDiscountRepository $priceBaseProductRepository;

    public function __construct(ProductRepository $productRepository,
        CurrencyRepository $currencyRepository, PriceProductDiscountRepository $priceBaseProductRepository
    ) {
        $this->productRepository = $productRepository;
        $this->currencyRepository = $currencyRepository;
        $this->priceBaseProductRepository = $priceBaseProductRepository;
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

    public function mapDtoToEntityForEdit(PriceProductDiscountDTO $priceBaseProductDTO, ?PriceProductDiscount
    $priceBase):PriceProductDiscount
    {

        $priceBase->setValue($priceBaseProductDTO->value);

        return $priceBase;
    }


}