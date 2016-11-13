<?php

namespace AppBundle\Helper;

use Symfony\Component\Form\Form;

class FormHelper
{
    /**
     * @param Form $form
     * @param array $customFields
     * @return array
     */
    public static function getArray($form, $customFields = [])
    {
        return array_merge([
            'form_layout' => mb_strtolower($form->getName()),
            'form' => $form->createView(),
        ], $customFields);
    }
}
