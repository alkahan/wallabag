<?php

namespace Wallabag\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    private $themes = array();
    private $languages = array();

    /**
     * @param array $themes    Themes come from the LiipThemeBundle (liip_theme.themes)
     * @param array $languages Languages come from configuration
     */
    public function __construct($themes, $languages)
    {
        $this->themes = array_combine(
            $themes,
            array_map(function ($s) { return ucwords(strtolower(str_replace('-', ' ', $s))); }, $themes)
        );

        $this->languages = $languages;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('theme', 'choice', array(
                'choices' => array_flip($this->themes),
                'choices_as_values' => true,
            ))
            ->add('items_per_page')
            ->add('language', 'choice', array(
                'choices' => $this->languages,
            ))
            ->add('save', 'submit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wallabag\CoreBundle\Entity\Config',
        ));
    }

    public function getName()
    {
        return 'config';
    }
}
