<?php

namespace Drupal\smmg_address\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\small_messages\Utility\Helper;

/**
 * Class AdressController.
 */
class AdressController extends ControllerBase
{



    /**
     * @param $data
     * @return array
     */
    public static function newSubscriber($data)
    {
        // Token

        $output = [
            'status' => FALSE,
            'mode' => 'save',
            'nid' => FALSE,
            'message' => '',
        ];


        // General
        $group = $data['group'];
        $user = $data['user'];
        $member = $data['member'];
        $description = $data['description'];

        // Address
        $gender = $data['gender'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];

        $street_and_number = $data['street_and_number'];
        $zip_code = $data['zip_code'];
        $city = $data['city'];
        $country = $data['country'];

        // Connections
        $phone = $data['phone'];
        $mobile = $data['mobile'];
        $email = $data['email'];

        // Group Term
        $group_title = Helper::getTermNameByID($group);

        // Title
        $title = "$group_title: $first_name $last_name - $description";


        try {
            $storage = \Drupal::entityTypeManager()->getStorage('node');
            $new_node = $storage->create(
                [
                    'type' => 'smmg_address',
                    'title' => $title,

                    // General
                    'field_smmg_group' => $group,
                    'field_smmg_user' => $user,
                    'field_smmg_member' => $member,
                    'field_smmg_description' => $description,

                    // Address
                    'field_smmg_gender' => $gender,
                    'field_smmg_first_name' => $first_name,
                    'field_smmg_last_name' => $last_name,

                    'field_smmg_street_and_number' => $street_and_number,
                    'field_smmg_zip_code' => $zip_code,
                    'field_smmg_city' => $city,
                    'field_smmg_country' => $country,

                    // Connections
                    'field_smmg_phone' => $phone,
                    'field_smmg_mobile' => $mobile,
                    'field_smmg_email' => $email,

                ]);


            // Save
            try {
                $new_node->save();
            } catch (EntityStorageException $e) {
            }

            $new_node_id = $new_node->id();

            if ($new_node_id) {

                $message = t('Address successfully saved.');

                $output['message'] = $message;
                $output['status'] = TRUE;
                $output['nid'] = $new_node_id;

            }
        } catch (InvalidPluginDefinitionException $e) {
        } catch (PluginNotFoundException $e) {
        }

        return $output;

    }

}
