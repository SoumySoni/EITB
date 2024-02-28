<?php

namespace Drupal\page_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "second",
 *   label = @Translation("Second api Resource"),
 *   uri_paths = {
 *     "canonical" = "/second/node"
 *   }
 * )
 */
class second_node_resource extends ResourceBase {

   /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $response = [];
    $nid = \Drupal::entityQuery('node')
    ->condition('type', 'content_second')
    ->accessCheck(FALSE)
    ->execute();
    $node_id = reset($nid);

    if ($node_id) {
      $node = \Drupal\node\Entity\Node::load($node_id);
      
     $title = $node->get('title')->value;
     $paragraph_field_items = $node->get('field_second_para')->getValue();
      foreach ($paragraph_field_items as $paragraph_item) {
        $paragraph = \Drupal\paragraphs\Entity\Paragraph::load($paragraph_item['target_id']);
        $paragraph_des_value = $paragraph->get('field_description')->value;
        $paragraph_field_image  = \Drupal::service('file_url_generator')->generateAbsoluteString($paragraph->field_image->entity->getFileUri());
        $paragraph_filed_readmore = $paragraph->get('field_read_more')->title;
        $response[] = [
          'title' => $title,
          'description' => $paragraph_des_value,
          'image' => $paragraph_field_image,
          'readmore' => $paragraph_filed_readmore,
        ];
      }
    }
    return new ResourceResponse($response);
  }
  }





