<?php


namespace App\Form;


use App\Entity\Organizer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OrganizerType
 *
 * @package App\Form
 */
class OrganizerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('consumerkey')
            ->add('consumersecret')
            ->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
            /** @var Organizer $organizer */
            $organizer = $event->getData();
            $form = $event->getForm();

            if ($organizer && $organizer['consumerkey'] && $organizer['consumersecret']) {
                $form->add('responsekey');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Organizer::class,
        ));
    }
}
