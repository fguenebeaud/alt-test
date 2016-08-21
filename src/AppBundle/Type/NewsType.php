<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 21/08/2016
 * Time: 19:24
 */

namespace AppBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NewsType
 * @package AppBundle\Type
 */
class NewsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                'text',
                array(
                    'label' => 'Titre *',
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
            ->add('picture', FileType::class, array('label' => 'Image de la news', 'required' => false));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\News',
            )
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'news';
    }
}
