<?php
class YammerHelper extends AppHelper
{
  public $helpers = array('Html');

  public function parse_message($messages, $message)
  {
    $result = h($message->body->parsed);
    if (!is_null($message->body->urls)) {
      foreach ($message->body->urls as $url) {
        $replacement = $this->Html->link($url, $url);
        $result = preg_replace("|{$url}|", $replacement, $result);
      }
    }
    preg_match_all('|\[\[(.+):(.+)\]\]|', $message->body->parsed, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
      $pattern = $match[0];
      $pattern = str_replace('[', '\\[', $pattern);
      $pattern = str_replace(']', '\\]', $pattern);
      $type = $match[1];
      $type_id = $match[2];
      foreach ($message->body->references as $obj) {
        if (($obj->type == $type) && ($obj->id == $type_id)) {
          switch ($type) {
          case 'user':
            $replacement = $this->Html->link($obj->name, array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'from_user', $type_id));
            $result = preg_replace("|{$pattern}|", "@{$replacement}", $result);
            break;
          case 'group':
            $replacement = $this->Html->link($obj->name, array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'in_group', $type_id));
            $result = preg_replace("|{$pattern}|", $replacement, $result);
            break;
          case 'tag':
            $replacement = $this->Html->link($obj->name, array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'about_topic', $obj->topic_id));
            $result = preg_replace("|{$pattern}|", "#{$replacement}", $result);
            break;
          }
        }
      }
    }

    return $result;
  }

  public function img_url($type, $type_id, $original_url, $width = 24, $height = 24, $quality = 75)
  {
    umask(0);

    $img_dir = sprintf("%s%s/img/icons/%s", APP, WEBROOT_DIR, $type);
    if (!file_exists($img_dir)) {
      mkdir($img_dir, 0777, $recurcive = true);
    }

    $filename = sprintf("%s/%s.jpg", $img_dir, $type_id);
    if (!file_exists($filename) || (time() > (filemtime($filename) + 60 * 60 * 12))) {
      if (!$this->img_convert($original_url, $filename, $width, $height, $quality)) {
        return $original_url;
      }
    }

    return sprintf("/img/icons/%s/%s.jpg", $type, $type_id);
  }

  protected function img_convert($url, $filename, $dst_width, $dst_height, $quality)
  {
    if (file_exists($filename)) {
      unlink($filename);
    }

    App::import('Core', 'HttpSocket');

    $httpSocket = new HttpSocket();
    $data = $httpSocket->get($url);
    file_put_contents($filename, $data);

    $src_img = null;
    $info = getimagesize($filename);
    switch ($info[2]) {
    case IMAGETYPE_JPEG:
      $src_img = imagecreatefromjpeg($filename);
      break;
    case IMAGETYPE_GIF:
      $src_img = imagecreatefromgif($filename);
      break;
    case IMAGETYPE_PNG:
      $src_img = imagecreatefrompng($filename);
      break;
    }

    if (is_null($src_img)) {
      return false;
    }

    $src_width = imagesx($src_img);
    $src_height = imagesy($src_img);

    $dst_img = imagecreatetruecolor($dst_width, $dst_height);
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
    unlink($filename);
    $ret = imagejpeg($dst_img, $filename, $quality);
    if ($ret !== true) {
      return false;
    }

    return true;
  }
}
