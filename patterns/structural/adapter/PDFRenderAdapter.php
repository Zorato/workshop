<?php declare(strict_types = 1);


/**
 * Class PDFRenderAdapter
 */
class PDFRenderAdapter implements RenderHtmlInterface
{
    /**
     * @var PDFDocument
     */
    private $pfd;

    /**
     * PDFRenderAdapter constructor.
     *
     * @param PDFDocument $pfd
     */
    public function __construct(PDFDocument $pfd)
    {
        $this->pfd = $pfd;
    }

    public function renderHeader()
    {
        $this->pfd->renderTop();
    }

    public function renderBody()
    {
        $this->pfd->renderMiddle();
    }

    public function renderFooter()
    {
        $this->pfd->renderBottom();
    }
}