<?php


namespace pingdecopong\PagerBundle\Pager\PagerColumn\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PagerColumnFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sortName', 'hidden', array(
            ))
            ->add('sortType', 'hidden', array(
            ))
            ->addEventListener(FormEvents::PRE_BIND, function(FormEvent $event) {

                $data = $event->getData();

                if(!empty($data['sortName']) && empty($data['sortType']))
                {
                    $data['sortType'] = 'asc';
                }

                $event->setData($data);
            })
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'pingdecopong\PagerBundle\Pager\PagerColumn\Form\PagerColumnFormModel'
        ));
    }

    public function getName()
    {
        return 'c';
    }

}