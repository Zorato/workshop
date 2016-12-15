<?php

/**
 * Class RenderHtmlImplementation
 */
class RenderHtmlImplementation implements RenderHtmlInterface
{

    public function renderHeader()
    {
        return "<html><body>";
    }

    public function renderBody()
    {
        return "Hello World";
    }

    public function renderFooter()
    {
        return "</body></html>";
    }

}