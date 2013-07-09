<?php
namespace pingdecopong\SampleBundle\Form\Search;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => '名前',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'pingdecopong\SampleBundle\Form\Search\SearchFormModel'
        ));
    }

    public function getName()
    {
        return 'pagerselector';
    }

}