<?php /** @noinspection ALL */

namespace Silecust\WebShop\Form\Transaction\Order\Header;

use Silecust\WebShop\Entity\OrderStatusType;
use Silecust\WebShop\Form\Transaction\Order\Header\DTO\OrderHeaderDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderHeaderEditForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'orderStatusType', EntityType::class,
            [
                'mapped' => false,
                'class' => OrderStatusType::class,
                // Note:
                // This might show incorrect value on screen . The value selected is correct but firefox may cause issue
                // see https://stackoverflow.com/questions/1479233/why-doesnt-firefox-show-the-correct-default-select-option/8258154#8258154
                'data' => $options['statusType']

            ]
        );
        $builder->add('choose', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {

            $formEvent->getForm()->add('orderStatusTypeId', HiddenType::class);

            // dto jugglery
            $data = $formEvent->getData();

            $data['orderStatusTypeId'] = $data['orderStatusType'];

            $formEvent->setData($data);

        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderHeaderDTO::class]);
        $resolver->setRequired('statusType');
    }

    public function getBlockPrefix(): string
    {

        return 'order_header_edit_form';
    }

}