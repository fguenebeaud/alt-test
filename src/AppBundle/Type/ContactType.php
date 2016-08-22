<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 21/08/2016
 * Time: 19:24
 */

namespace AppBundle\Type;

use AppBundle\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContactType
 * @package AppBundle\Type
 */
class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname',
                'text',
                array(
                    'label' => 'Prénom *',
                    'required' => true,
                )
            )
            ->add(
                'lastname',
                'text',
                array(
                    'label' => 'Nom *',
                    'required' => true,
                )
            )
            ->add(
                'email',
                'text',
                array(
                    'label' => 'eMail *',
                    'required' => true,
                )
            )
            ->add(
                'content',
                'textarea',
                array(
                    'label' => 'Contenu *',
                    'required' => true,
                )
            )
        ->add('object', ChoiceType::class, [
            'choices'  => Contact::getObjects(),
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Contact',
            )
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'contact';
    }
}
