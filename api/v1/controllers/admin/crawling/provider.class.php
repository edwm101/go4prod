<?php
class Provider
{
    public $document, $provider;
    public $product = [];

    function __construct($document, $provider)
    {
        $this->document = $document;
        $this->provider = $provider;
    }

    function myTek()
    {
        $this->product["name"] = $this->document->find('h1[itemprop=name]')[0]->text();
        $this->product["price"] = $this->document->find('p[itemprop=offers] span::attr(content)')[0];
        $this->product["breadcrumb"] = array_map('trim', explode(">", strtolower($this->document->find('div[class=breadcrumb clearfix]')[0]
        ->text())));
        $this->product["reference"] = $this->document->find('span[itemprop=sku]')[0]->text();
        $this->product["short_description"] = $this->document->find('div[itemprop=description]')[0]->text();
        $this->product["image"] =  $this->document->find('span[id=view_full_size] img::attr(src)')[0];
        $this->product["description"] =  $this->document->find('div[id=more_info_sheets]')[0]->html();



        $images = [];
        foreach ($this->document->find('div[id=thumbs_list] > ul > li') as $image) {
            $images[] = $image->find("a img::attr(src)")[0];
        }
        $this->product["images"] = $images;
    }


    // function alwosta()
    // {
    //     $this->product["name"] = $this->document->find('title')[0]->text();
    //     $this->product["price"] = $this->document->find('p[itemprop=offers] span::attr(content)')[0];
    //     $this->product["reference"] = $this->document->find('p[id=product_reference] span::attr(content)')[0];
    //     $this->product["short_description"] = $this->document->find('div[itemprop=short_description_block]')[0]->text();
    //     $this->product["image"] =  $this->document->find('span[id=view_full_size] img::attr(src)')[0];
    // }


    // function technoPro()
    // {
    //     $this->product["name"] = $this->document->find('h1[itemprop=name]')[0]->text();
    //     $this->product["price"] = $this->document->find('p[itemprop=offers] span::attr(content)')[0];
    //     $this->product["reference"] = $this->document->find('p[id=product_reference] span::attr(content)')[0];
    //     $this->product["short_description"] = $this->document->find('div[itemprop=description]')[0]->text();
    //     $this->product["image"] =  $this->document->find('span[id=view_full_size] img::attr(src)')[0];
    // }


    function handle()
    {
        switch ($this->provider) {
            case 'myTek':
                $this->myTek();
                break;
            case 'technoPro':
                $this->technoPro();
                break;
            default:
                break;
        }

        return $this->product;
    }
}