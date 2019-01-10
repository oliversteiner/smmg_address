<?php

namespace Drupal\smmg_address\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\small_messages\Utility\Helper;
use Drupal\smmg_address\Controller\AddressController;

/**
 * Class NewAddressForm.
 */
class NewAddressForm extends FormBase
{
    private $options_country;
    private $options_group;
    private $options_gender;
    private $default_gender;
    private $default_group;
    private $default_country;


    /**
     *  constructor.
     */
    public function __construct()
    {
        $vid_gender = 'smmg_gender';
        $vid_group = 'smmg_address_group';
        $vid_country = 'smmg_country';

        $this->options_gender = Helper::getTermsByID($vid_gender);
        $this->options_group = Helper::getTermsByID($vid_group);
        $this->options_country = Helper::getTermsByID($vid_country);

        $this->default_group = Helper::getTermIDByName('Default', $vid_group);
        $this->default_country = Helper::getTermIDByName('Switzerland', $vid_country);


        $this->options_gender[0] = t('Please Chose');
        $this->default_gender = 0;


    }


    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'new_address_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        // $values = $form_state->getUserInput();

        // Spam and Bot Protection
        honeypot_add_form_protection($form, $form_state, [
            'honeypot',
            'time_restriction',
        ]);

        // Add JS and CSS
        // $form['#attached']['library'][] = 'smmg_address/smmg_address.form';

        // Disable browser HTML5 validation
        $form['#attributes']['novalidate'] = 'novalidate';

        // General
        // -------------------------------------------------
        //
        $form['general'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('General'),
            '#weight' => '0',
        ];
        $form['general']['group'] = [
            '#type' => 'select',
            '#title' => $this->t('Group'),
            '#options' => $this->options_group,
            '#default_value' => $this->default_group,
            '#weight' => '0',
        ];
        $form['general']['description'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Description'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
        ];

        // Address
        // -------------------------------------------------
        //
        $form['address'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Address'),
            '#weight' => '0',
        ];

        // Gender
        $form['address']['gender'] = [
            '#type' => 'select',
            '#title' => $this->t('Gender'),
            '#options' => $this->options_gender,
            '#default_value' => $this->default_gender,
            '#weight' => '0',
            '#required' => TRUE,

        ];

        // First Name
        $form['address']['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
        ];

        // Last Name
        $form['address']['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
            '#required' => TRUE,

        ];

        // Street and Number
        $form['address']['street_and_number'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Street and Number'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
            '#required' => TRUE,
        ];

        // ZIP Code
        $form['address']['zip_code'] = [
            '#type' => 'number',
            '#title' => $this->t('ZIP Code'),
            '#weight' => '0',
            '#required' => TRUE,

        ];

        // City
        $form['address']['city'] = [
            '#type' => 'textfield',
            '#title' => $this->t('City'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
            '#required' => TRUE,

        ];

        // Country
        $form['address']['country'] = [
            '#type' => 'select',
            '#title' => $this->t('Country'),
            '#options' => $this->options_country,
            '#default_value' => $this->default_country,
            '#weight' => '0',
        ];

        // Connections
        // -------------------------------------------------
        //
        $form['connections'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Connections'),
            '#weight' => '0',
        ];

        // Phone
        $form['connections']['phone'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Phone'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
        ];

        // Mobile
        $form['connections']['mobile'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Mobile'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
        ];

        // Email
        $form['connections']['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#weight' => '0',
        ];

        // Actions
        // -------------------------------------------------
        //
        // Submit
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // parent::validateForm($form, $form_state);

        $values = $form_state->getValues();

        $gender = $values['gender'];
        $last_name = $values['last_name'];
        $street_and_number = $values['street_and_number'];
        $zip_code = $values['zip_code'];
        $city = $values['city'];
        $email = $values['email'];


        // Gender
        $t_gender = $this->t('Gender');
        if (!$gender || empty($gender)) {
            $form_state->setErrorByName('gender',
                $this->t('Please fill in the field "@field"', ['@field' => $t_gender])
            );
        }
        // Last Name
        $t_last_name = $this->t('Last Name');
        if (!$last_name || empty($last_name)) {
            $form_state->setErrorByName('last_name',
                $this->t('Please fill in the field "@field"', ['@field' => $t_last_name])
            );
        }

        // Street and Number
        $t_street_and_number = $this->t('Street and Number');
        if (!$street_and_number || empty($street_and_number)) {
            $form_state->setErrorByName('street_and_number',
                $this->t('Please fill in the field "@field"', ['@field' => $t_street_and_number])
            );
        }

        // ZIP Code
        $t_zip_code = $this->t('ZIP');
        if (!$zip_code || empty($zip_code)) {
            $form_state->setErrorByName('ZIP',
                $this->t('Please fill in the field "@field"', ['@field' => $t_zip_code])
            );
        }

        // City
        $t_city = $this->t('City');
        if (!$city || empty($city)) {
            $form_state->setErrorByName('city',
                $this->t('Please fill in the field "@field"', ['@field' => $t_city])
            );
        }


        //  Email
        if ($email != '') {

            $validated_email = \Drupal::service('email.validator')
                ->isValid($email);

            if (FALSE === $validated_email) {
                $form_state->setErrorByName('email',
                    $this->t('There is something wrong with this email address.'));
            }

        }
    }


    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $values = $form_state->getValues();


        // Create Node
        $result = AddressController::newAddress($values);

        // Result
        if ($result) {
            if ($result['status']) {

                // Evening is OK
                if ($result['message']) {
                    $this->messenger()->addMessage($result['message']);
                }
            } else {

                // Error on create  node
                if ($result['message']) {
                    $this->messenger()->addMessage($result['message'], 'error');
                }
            }
        }

    }

}
