<?php
/**
 * @file
 * Contains \Drupal\custom_block\Plugin\Block\CustomBlock.
 */
namespace Drupal\custom_block\Plugin\Block;
use Drupal;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "custom_block",
 *   admin_label = @Translation("Custom block"),
 *   category = @Translation("Custom block example")
 * )
 */
class CustomBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $id = Drupal::currentUser()->id();
    $query = \Drupal::database()->select('users_field_data', 'ufd');
    $query->addField('ufd', 'name');
    $query->condition('ufd.uid', $id);
    $name = $query->execute()->fetchField();

    $query = \Drupal::database()->select('commerce_store_field_data', 'ufd');
    $query->addField('ufd', 'name');
    $query->condition('ufd.uid', $id);
    $company = $query->execute()->fetchField();

    if(empty($company))
    {
      $company_error = 'Компаний нет';
    }
    else {
      $query = \Drupal::database()->select('commerce_store_field_data', 'ufd2');
      $query->addField('ufd2', 'store_id');
      $company_id = $query->execute()->fetchField();


      $query = \Drupal::database()->select('commerce_product_field_data', 'product');
      //$query->addField('ufd', 'default_langcode');
      $query->condition('product.default_langcode', $company_id);
      $count_product = $query->countQuery()->execute()->fetchField();



    }
    return array(
      '#type' => 'markup',
      '#markup' => 'Your name: '.$name . '<a href="http://drupal2/user/'.$id.'/edit"> edit </a>'.'<br />'.$company_error.'Orginization: '. $company.'<a href="http://drupal2/store/'.$company_id.'/edit?destination=/admin/commerce/config/stores"> edit</a>'.'<br /> Count product: '.$count_product .'<a href = "#"> see</a>'
    ,

    );

  }
}
