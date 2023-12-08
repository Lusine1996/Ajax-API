<?php
namespace Drupal\ajax_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Implements an example form with Ajax.
 */
class AjaxForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_form_example';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'event' => 'change', // Trigger the Ajax callback on the change event.
      ],
    ];

    $form['result'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result"></div>',
    ];

    return $form;
  }

  /**
   * Ajax callback for updating the result div.
   */
  public function ajaxCallback(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();

    // Get the value of the 'name' field.
    $name = $form_state->getValue('name');

    // Add an HtmlCommand to update the content of the result div.
    $ajax_response->addCommand(new HtmlCommand('.result', 'Name changed to: ' . $name));

    return $ajax_response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
