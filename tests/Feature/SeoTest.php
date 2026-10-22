<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_menghasilkan_xml_dengan_charset_utf8_dan_memiliki_root_urlset(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());

        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);
        $this->assertSame('urlset', $xml->getName());
    }
}
