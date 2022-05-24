<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Image' => Media::TYPE_PICTURE,
                    'Video' => Media::TYPE_VIDEO_UPLOADED,
                ],
                'data' => Media::TYPE_PICTURE,
                'required' => true,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('mediaFile', VichFileType::class, [
                'required' => false,
                'label' => 'Image ou vidéo téléchargée',
                'constraints' => [
                    new Image([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'video/mp4',
                            'video/webm',
                            'video/ogg',
                            'video/x-flv',
                            'video/avi',
                        ],
                        'mimeTypesMessage' => 'Merci de choisir un fichier de type jpeg, png, gif, mp4, webm, ogg, flv, avi',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez uploader une image ou une vidéo',
                    ]),
                ],
            ])
            ->add('streamedPath', TextType::class, [
                'required' => false,
                'label' => 'Lien partageable d\'une vidéo externe (Youtube, ...)',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            "allow_extra_fields" => true,
        ]);
    }
}