<?php

namespace Silecust\WebShop\Form\MasterData\Price\Mapper;

use Silecust\WebShop\Entity\PriceProductBase;
use Silecust\WebShop\Form\MasterData\Price\DTO\PriceProductBaseDTO;
use Silecust\WebShop\Repository\CurrencyRepository;
use Silecust\WebShop\Repository\PriceProductBaseRepository;
use Silecust\WebShop\Repository\ProductRepository;

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

    public function maptoDtoFromEntityForEdit(PriceProductBase $priceBase): PriceProductBaseDTO
    {

        $priceBaseProductDTO = new PriceProductBaseDTO();
        $priceBaseProductDTO->id = $priceBase->getId();
        $priceBaseProductDTO->productId = $priceBase->getProduct()->getId();
        $priceBaseProductDTO->currencyId = $priceBase->getCurrency()->getId();
        $priceBaseProductDTO->price = $priceBase->getPrice();

        return $priceBaseProductDTO;
    }


}