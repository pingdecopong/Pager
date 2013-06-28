<?php


namespace pingdecopong\PagerBundle\Pager\PagerColumn\Form;


class PagerColumnFormType {

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
            'data_class' => 'System\CompasBundle\Lib\Pager\BasicColumn\BasicColumnFormModel'
        ));
    }

    public function getName()
    {
        return 'basiccolumn';
    }

}