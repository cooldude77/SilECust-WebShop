<?php

namespace App\Form\MasterData\Price\Mapper;

use App\Entity\PriceProductBase;
use App\Form\MasterData\Price\DTO\PriceProductBaseDTO;
use App\Repository\CurrencyRepository;
use App\Repository\PriceProductBaseRepository;
use App\Repository\ProductRepository;

class PriceProductBaseDTOMapper
{

    private ProductRepository $productRepository;
    private CurrencyRepository $currencyRepository;
    private PriceProductBaseRepository $priceBaseProductRepository;

    public function __construct(ProductRepository $productRepository,
        CurrencyRepository $currencyRepository, PriceProductBaseRepository $priceBaseProductRepository
    ) {
        $this->productRepository = $productRepository;
        $this->currencyRepository = $currencyRepository;
        $this->priceBaseProductRepository = $priceBaseProductRepository;
    }

    public function mapDtoToEntity(PriceProductBaseDTO $priceBaseProductDTO): PriceProductBase
    {

        $product = $this->productRepository->findOneBy(['id' => $priceBaseProductDTO->productId]);
        $currency = $this->currencyRepository->findOneBy(['id' => $priceBaseProductDTO->currencyId]
        );
        $priceBase = $this->priceBaseProductRepository->create($product, $currency);
        $priceBase->setPrice($priceBaseProductDTO->price);
        return $priceBase;

    }

    public function mapDtoToEntityForEdit(PriceProductBaseDTO $priceBaseProductDTO, ?PriceProductBase
    $priceBase):PriceProductBase
    {

        $priceBase->setPrice($priceBaseProductDTO->price);

        return $priceBase;
    }


}