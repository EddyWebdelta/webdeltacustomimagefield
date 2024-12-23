<?php
namespace WebdeltaCustomImageField\Form\Extension;

use PrestaShopBundle\Form\Admin\Sell\Page\CmsPageType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CmsPageFormExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [CmsPageType::class];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('custom_image', FileType::class, [
            'label' => 'Extra Image',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'accept' => 'image/*'
            ]
        ]);
    }
}
