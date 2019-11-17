<?php
class Provider
{
    public $document, $provider;
    public $product = [];

    function __construct($document, $curl)
    {
        $this->document = $document;
        $this->product["curl"] = $curl;
        $curl = parse_url($curl);
        $this->provider = preg_replace("/^([a-zA-Z0-9].*\.)?([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z.]{2,})$/", '$2', $curl['host']);
    }

    function myTek()
    {
        $this->product["name"] = $this->document->find('h1[itemprop=name]')[0]->text();
        $this->product["price"] = $this->document->find('p[itemprop=offers] span::attr(content)')[0];
        $this->product["breadcrumb"] =   array_map('trim', explode(">", utf8_encode(strtolower($this->document->find('div[class=breadcrumb clearfix]')[0]
            ->text()))));
        $this->product["reference"] = $this->document->find('span[itemprop=sku]')[0]->text();
        $this->product["short_description"] = $this->document->find('div[itemprop=description]')[0]->html();
        $this->product["image"] =  $this->document->find('span[id=view_full_size] img::attr(src)')[0];
        $this->product["description"] =  $this->document->find('div[id=more_info_sheets]')[0]->html();

        $images = [];
        foreach ($this->document->find('div[id=thumbs_list] > ul > li') as $image) {
            $images[] = $image->find("a img::attr(src)")[0];
        }
        $this->product["images"] = $images;
    }


    function alwosta()
    {
        $this->product["name"] = $this->document->find('title')[0]->text();
        $this->product["price"] = $this->document->find('p[itemprop=offers] span::attr(content)')[0];
        $this->product["reference"] = "";
        $this->product["short_description"] = $this->document->find('div[class=product_desc]')[0]->text();
        $this->product["description"] =  "";
        $this->product["image"] =  $this->document->find('span[id=view_full_size] img::attr(src)')[0];
        $images = [];
        foreach ($this->document->find('div[id=thumbs_list] > ul > li') as $image) {
            $images[] = $image->find("a img::attr(src)")[0];
        }
        $this->product["images"] = $images;
    }


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
            case 'mytek.tn':
                $this->myTek();
                break;
            case 'alwosta.tn':
                $this->alwosta();
                break;
            case 'techno-Pro.com':
                $this->technoPro();
                break;
            default:
                break;
        }

        return $this->product;
    }
}
