<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Image\Mapper;

use Silecust\WebShop\Entity\CustomerImage;
use Silecust\WebShop\Form\Common\File\Mapper\FileDTOMapper;
use Silecust\WebShop\Form\MasterData\Customer\Image\DTO\CustomerImageDTO;
use Silecust\WebShop\Repository\CustomerImageRepository;
use Silecust\WebShop\Repository\CustomerRepository;

readonly class CustomerImageDTOMapper
{

    public function __construct(private FileDTOMapper $fileDTOMapper,
        private CustomerRepository                    $customerRepository,
        private CustomerImageRepository               $customerImageRepository
    ) {
    }

    public function mapDtoToEntityForCreate(CustomerImageDTO $customerImageDTO): CustomerImage
    {

        $customer = $this->customerRepository->find($customerImageDTO->customerId);

        $file = $this->fileDTOMapper->mapToFileEntityForCreate($customerImageDTO->fileDTO);

        return $this->customerImageRepository->create($customer, $file);

    }

    public function mapDtoToEntityForEdit(CustomerImageDTO $fileImageDTO,
        CustomerImage $customerImage
    ): CustomerImage {


        $file = $this->fileDTOMapper->mapToFileEntityForEdit(
            $fileImageDTO->fileDTO,
            $customerImage->getFile()
        );

        $customerImage->setFile($file);

        return $customerImage;


    }

    public function mapEntityToDto(CustomerImage $customerImage): CustomerImageDTO
    {
        $dto = new CustomerImageDTO();

        $dto->fileDTO = $this->fileDTOMapper->mapEntityToFileDto($customerImage->getFile());


        return $dto;

    }

}