<?php

/**
 * DCA tl_content
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['mvo_nested_forms_subForm'] =
    '{type_legend},type,name;{mvo_nested_forms_legend},mvo_nested_forms_srcForm,mvo_nested_forms_mandatory';

$GLOBALS['TL_DCA']['tl_form_field']['fields']['mvo_nested_forms_srcForm'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_form_field']['mvo_nested_forms_srcForm'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => ['mvo_contao_nested_forms.listener.datacontainer.form_field', 'onGetForms'],
    'eval'             => [
        'mandatory'      => true,
        'chosen'         => true,
        'submitOnChange' => true,
        'tl_class'       => 'w50'
    ],
    'sql'              => "int(10) unsigned NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['mvo_nested_forms_mandatory'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_form_field']['mvo_nested_forms_mandatory'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [-1 => 'keep', 0 => 'forceNotMandatory', 1 => 'forceMandatory'],
    'reference' => &$GLOBALS['TL_LANG']['tl_form_field']['mvo_nested_forms_mandatory'],
    'eval'      => [
        'mandatory' => true,
        'tl_class'  => 'clr w50'
    ],
    'sql'       => "int(1) NOT NULL default '-1'"
];