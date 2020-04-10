<?php

namespace App\Form;

use App\Entity\Hostel;
use App\Entity\HostelTypes;
use App\Repository\HostelTypesRepository;
use App\Repository\RegionsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This class i have design for you Susanne.
 * In this class we build the hostel search form dynamic from
 * database given data so it is flexible to extend.
 *
 * Class SearchHostelType
 * @package App\Form
 */
class SearchHostelType extends AbstractType
{

    /**
     * Region database load to
     * @var RegionsRepository
     */
    private $regions;

    /**
     * Hostel type database load to
     * @var HostelTypesRepository
     */
    private $hostel_types;

    public function __construct(RegionsRepository $regions, HostelTypesRepository $hostel_types)
    {
        $this->regions = $regions;
        $this->hostel_types = $hostel_types;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $group = new HostelTypes();
        $builder
            ->add(
                'regions',
                ChoiceType::class,
                [
                    'choices' => [
                        $this->regions->getRegionsForForm(),
                    ],
                    'label'   => 'Region',
                ]
            )
            ->add(
                'hostel_types',
                ChoiceType::class,
                [
                    'choices' => [
                        $this->hostel_types->getHostelTypesForForm(),
                    ],
                    'label'   => 'Haustyp',
                ]
            )
            ->add('see_distance', RangeType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 10
                ],
                'label'   => 'Entfernung See',
            ])
            ->add('customer_id')
            ->add('hostel_name')
            ->add('submit', SubmitType::class, ['label' => 'Jetzt suchen']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Hostel::class,
            ]
        );
    }
}
