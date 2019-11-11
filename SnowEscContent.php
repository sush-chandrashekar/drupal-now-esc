<?php
/* PHP code to pull ESC Content from servicenow */
namespace Drupal\snow_content\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;

/**
 * Block of Cat Facts... you can't make this stuff up.
 *
 * @Block(
 *   id = "snow_content",
 *   admin_label = @Translation("Snow Content")
 * )
 */
class SnowContentTest extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var \GuzzleHttp\Client $client */
    $client = \Drupal::service('http_client_factory')->fromOptions([
      'headers' => [
     'Authorization' => 'Basic **authcode***',
     'Content-Type' => 'application/hal+json'
  ]
    ]);

  /* Calling Scripted REST API to get ESC content and any Image associated with it as Base64 encoded  */

    $response = $client->get('https://instance.service-now.com/api/sn_cd/drupal_get_content');

    $restResponse = Json::decode($response->getBody());
    $kbs = [];
    $content = '';

    foreach ($restResponse as $eachResp) {
      foreach($eachResp as $eachkb){



        $content .= '<div class="snow-content" style="width:100%;padding:10px;background-color:#f5f5f5;"><h2 style="font-weight:bold; text-decoration:underline;">'.$eachkb['heading_text'].'</h2><p>'.$eachkb['title'].'</p><a href="'.$eachkb['link'].'"><img style="width:100%;" src="data:image/png;base64,'.$eachkb['image'].'" /></a></div>';

      }

    }

    return [

      '#type' => 'markup',
      '#markup' => $content,
    ];
  }

}