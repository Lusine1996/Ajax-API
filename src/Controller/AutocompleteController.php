<?php

namespace Drupal\ajax_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;

/**
 * Class AutoCompleteController
 * @package Drupal\ajax_api\Controller
 */
class AutoCompleteController extends ControllerBase
{

  /**
   * @return JsonResponse
   */
  public function handleAutocomplete(Request $request)
  {
    $results = [];
    $input = $request->query->get('q');
    if (!$input) {
      return new JsonResponse($results);
    }
    $input = Xss::filter($input);
    $query = \Drupal::entityQuery('node')
      ->accessCheck(TRUE)
      ->condition('type', 'user_data')
      ->condition('title', $input, 'CONTAINS')
      ->groupBy('nid')
      ->sort('created', 'DESC')
      ->range(0, 10);
    $ids = $query->execute();
    $nodes = $ids ? \Drupal\node\Entity\Node::loadMultiple($ids) : [];
    foreach ($nodes as $node) {
      $results[] = [
        'value' => EntityAutocomplete::getEntityLabels([$node]),
        'label' => $node->getTitle().' ('.$node->id().')',
      ];
    }
    return new JsonResponse($results);
  }

}
