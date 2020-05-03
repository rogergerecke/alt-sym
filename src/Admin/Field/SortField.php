<?php

namespace App\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class SortField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('admin/field/map.html.twig')
            ->setFormType(TextareaType::class)
            ->addCssClass('field-map')
            ->addCssFiles('js/admin/field-map.css')// todo add css
            ->addJsFiles('js/admin/field-map.js');// todo add ajax
    }
}