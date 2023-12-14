<?php

namespace Drupal\ajax_api\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Element\EntityAutocomplete;

/**
 * Form to handle article autocomplete.
 */
class AutocompleteForm extends FormBase
{

  public function getFormId()
  {
    return 'autocomplete_search';
  }

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorage
   */
  protected $nodeStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager)
  {
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['search'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search user data'),
      '#autocomplete_route_name' => 'ajax_api.user_autocomplete',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // Extracts the entity ID from the autocompletion result.
    $article_id = EntityAutocomplete::extractEntityIdFromAutocompleteInput($form_state->getValue('search'));
  }
}


