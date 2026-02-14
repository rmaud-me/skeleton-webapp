<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormThemePresentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('input', TextType::class, [
                'label'    => 'Basic input',
                'required' => false,
            ])
            ->add('inputRequired', TextType::class, [
                'label' => 'Input required',
            ])
            ->add('inputWithHelper', TextType::class, [
                'label' => 'Input With helper',
                'help' => "I am a helper !"
            ])
            ->add('emailInput', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('date', DateType::class, [
                'label'              => 'Date',
                'widget'             => 'single_text',
            ])
            ->add('datepicker', DateType::class, [
                'label'              => 'Date with external datepicker',
                'widget'             => 'single_text',
                'input_format'       => 'Y-m-d',
                'html5'              => false,
            ])
            ->add('datetimepicker', DateTimeType::class, [
                'widget'             => 'single_text',
                'input_format'       => 'Y-m-d H:i',
                'html5'              => false,
                'label'              => 'Datetime with external datetimepicker',
            ])
            ->add('timepicker', TimeType::class, [
                'widget'             => 'single_text',
                'input_format'       => 'H:i',
                'html5'              => false,
                'label'              => 'Time with external datetimepicker',
            ])
            ->add('numericInput', IntegerType::class, [
                'label' => 'Numeric Input',
            ])
            ->add('textarea', TextareaType::class, [
                'label' => 'Textarea',
            ])
            ->add('toggle', CheckboxType::class, [
                'label'    => 'Toggle',
                'required' => false,
            ])
            ->add('select', ChoiceType::class, [
                'choices'  => [
                    'Choice 1' => 'ChoiceOne',
                    'Choice 2' => 'ChoiceTwo',
                ],
                'label' => 'Select input',
            ])
//            ->add('multipleSelect', ChoiceType::class, [
//                'label'        => 'Multiple select',
//                'multiple'     => true,
//                'expanded'     => false,
//                'choices'      => [
//                    'Choice 1' => 'ChoiceOne',
//                    'Choice 2' => 'ChoiceTwo',
//                ],
//                'autocomplete' => true,
//            ])
            ->add('radio', ChoiceType::class, [
                'choices'  => [
                    'Choice 1' => 'ChoiceOne',
                    'Choice 2' => 'ChoiceTwo',
                ],
                'expanded' => true,
                'multiple' => false,
                'label'    => 'Radio',
            ])
            ->add('checkbox', ChoiceType::class, [
                'choices'  => [
                    'Choice 1' => 'ChoiceOne',
                    'Choice 2' => 'ChoiceTwo',
                ],
                'expanded' => true,
                'multiple' => true,
                'label'    => 'Checkbox',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
