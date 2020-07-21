<?php

namespace App\Form;

use App\Entity\AmenitiesTypes;
use App\Entity\Hostel;
use App\Entity\Regions;
use App\Repository\AmenitiesTypesRepository;
use App\Repository\HostelRepository;
use App\Repository\RegionsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

// we need for range fields with ion-rangeslider
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
     * @var AmenitiesTypesRepository
     */
    private $amenitiesTypesRepository;
    /**
     * @var HostelRepository
     */
    private $hostelRepository;

    public function __construct(RegionsRepository $regions, AmenitiesTypesRepository $amenitiesTypesRepository,HostelRepository $hostelRepository)
    {
        $this->regions = $regions;
        $this->amenitiesTypesRepository = $amenitiesTypesRepository;
        $this->hostelRepository = $hostelRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'regions',
                ChoiceType::class,
                [
                    'choices'    => [
                        'alle'=>'',
                        $this->regions->getRegionsForForm(),
                    ],
                    'label'      => false,
                    'group_by'   => 'id',
                    'data_class' => Regions::class,

                ]
            )
            ->add(
                'hostel_types',
                ChoiceType::class,
                [
                    'choices'    => [
                        'alle'=>'',
                        $this->amenitiesTypesRepository->getAmenitiesTypesForForm(),
                    ],
                    'label'      => false,
                    'group_by'   => 'id',
                    'data_class' => Hostel::class,
                ]
            )
            ->add(
                'see_distance',
                TextType::class,
                [
                    'attr'  => [
                        'class'        => 'js-range-slider',
                        'data-type'    => "double",
                        'data-min'     => "1",
                        'data-max'     => $this->hostelRepository->getMaxDistanceToSee(),
                        'data-from'    => "1",
                        'data-to'      => $this->hostelRepository->getMaxDistanceToSee(),
                        'data-postfix' => ' KM',
                    ],
                    'label' => false,
                ]
            )
            ->add(
                'handicap',
                CheckboxType::class,
                [
                    'label'        => false,
                    'value'        => '1',
                    'false_values' => [null],
                ]
            )
            ->add(
                'bread_service',
                CheckboxType::class,
                [
                    'label'        => false,
                    'value'        => '1',
                    'false_values' => [null],
                ]
            )
            ->add(
                'half_board',
                CheckboxType::class,
                [
                    'label'        => false,
                    'value'        => '1',
                    'false_values' => [null],
                ]
            )
            ->add(
                'breakfast',
                CheckboxType::class,
                [
                    'label'        => false,
                    'value'        => '1',
                    'false_values' => [null],
                ]
            )
            ->add(
                'price_range',
                TextType::class,
                [
                    'attr'  => [
                        'value'        => 'false',
                        'class'        => 'js-range-slider',
                        'data-type'    => 'double',
                        'data-step'    => 10,
                        'data-min'     => '0',
                        'data-max'     => $this->hostelRepository->getMaxPrice(),
                        'data-from'    => '0',
                        'data-to'      => $this->hostelRepository->getMaxPrice(),
                        'data-postfix' => ' €',
                    ],
                    'label' => false,
                ]
            )
            ->add(
                'quantity_person',
                TextType::class,
                [
                    'attr'  => [
                        'class'     => 'js-range-slider',
                        'data-type' => 'single',
                        'data-step' => 1,
                        'data-min'  => '1',
                        'data-max'  => '45',
                        'data-from' => '1',
                        'data-to'   => '2',
                    ],
                    'label' => false,
                ]
            )
            ->add('submit', SubmitType::class, ['label' => 'Jetzt suchen']);
    }

    /*  public function configureOptions(OptionsResolver $resolver)
      {
          $resolver->setDefaults(
              [
                  'data_class' => Hostel::class,
              ]
          );
      }*/
}
