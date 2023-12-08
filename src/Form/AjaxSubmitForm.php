<?php

namespace Drupal\ajax_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Provides  "Ajax form" form.
 */
class AjaxSubmitForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {

    return 'ajax_form';

  }

  /**
   *{@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['element'] = [
      '#type' => 'markup',
      '#markup' => "<div class='success'></div>",
    ];
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];
    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('Save'),
      '#ajax' => [
        'callback' => '::ajaxActionsCallback',
      ],
    ];
    return $form;
  }
  public function submitData(array &$form, FormStateInterface $formState){
    $ajax_response = new AjaxResponse();
    $ajax_response ->addCommand(new HtmlCommand('.success','Form submitted successfully'));
    return $ajax_response;

  }


  public function ajaxActionsCallback(array &$form, FormStateInterface $form_state)
  {
    \Drupal::messenger()->addMessage($this->t('Form submitted. Name: @name, Email: @email, Message: @message', [
      '@name' => $form_state->getValue('name'),
      '@email' => $form_state->getValue('email'),
      '@message' => $form_state->getValue('message')

    ])
    );
  }
}
