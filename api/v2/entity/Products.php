<?php
class Products
{
  private $db;
  private $tabel_name = 'products';
  private $id;
  private $category_id;
  private $provider_id;
  private $name;
  private $price;
  private $discount;
  private $discount_price;
  private $reference;
  private $short_description;
  private $image;
  private $description;
  private $images;
  private $curl;
  private $views;
  private $likes;
  function __construct($data, $db = null)
  {
    $this->db = $db;
    $this->id = (int) @$data->id;
    $this->category_id = (int) @$data->category_id;
    $this->provider_id = (int) @$data->provider_id;
    $this->name = (string) @$data->name;
    $this->price = (int) @$data->price;
    $this->discount = (int) @$data->discount;
    $this->discount_price = (float) @$data->discount_price;
    $this->reference = (string) @$data->reference;
    $this->short_description = (string) @$data->short_description;
    $this->image = (string) @$data->image;
    $this->description = (string) @$data->description;
    $this->images = (string) @$data->images;
    $this->curl = (string) @$data->curl;
    $this->views = (int) @$data->views;
    $this->likes = (int) @$data->likes;
  }
  public function get_id()
  {
    return (int) $this->id;
  }
  public function set_id($id)
  {
    $this->id = (int) $id;
  }
  public function get_category_id()
  {
    return (int) $this->category_id;
  }
  public function set_category_id($category_id)
  {
    $this->category_id = (int) $category_id;
  }
  public function get_provider_id()
  {
    return (int) $this->provider_id;
  }
  public function set_provider_id($provider_id)
  {
    $this->provider_id = (int) $provider_id;
  }
  public function get_name()
  {
    return (string) $this->name;
  }
  public function set_name($name)
  {
    $this->name = (string) $name;
  }
  public function get_price()
  {
    return (int) $this->price;
  }
  public function set_price($price)
  {
    $this->price = (int) $price;
  }
  public function get_discount()
  {
    return (int) $this->discount;
  }
  public function set_discount($discount)
  {
    $this->discount = (int) $discount;
  }
  public function get_discount_price()
  {
    return (float) $this->discount_price;
  }
  public function set_discount_price($discount_price)
  {
    $this->discount_price = (float) $discount_price;
  }
  public function get_reference()
  {
    return (string) $this->reference;
  }
  public function set_reference($reference)
  {
    $this->reference = (string) $reference;
  }
  public function get_short_description()
  {
    return (string) $this->short_description;
  }
  public function set_short_description($short_description)
  {
    $this->short_description = (string) $short_description;
  }
  public function get_image()
  {
    return (string) $this->image;
  }
  public function set_image($image)
  {
    $this->image = (string) $image;
  }
  public function get_description()
  {
    return (string) $this->description;
  }
  public function set_description($description)
  {
    $this->description = (string) $description;
  }
  public function get_images()
  {
    return (string) $this->images;
  }
  public function set_images($images)
  {
    $this->images = (string) $images;
  }
  public function get_curl()
  {
    return (string) $this->curl;
  }
  public function set_curl($curl)
  {
    $this->curl = (string) $curl;
  }
  public function get_views()
  {
    return (int) $this->views;
  }
  public function set_views($views)
  {
    $this->views = (int) $views;
  }
  public function get_likes()
  {
    return (int) $this->likes;
  }
  public function set_likes($likes)
  {
    $this->likes = (int) $likes;
  }
}
