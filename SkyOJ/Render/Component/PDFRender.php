<?php namespace SkyOJ\Render\Component;

class PDFRender
{
    const m_pdf_js = <<<'jinkela'
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.332/pdf.min.js"></script>
            <div id="content" style="padding-bottom: 2rem;">Loading...</div>
            <script type="text/javascript">
                let j_cont = document.getElementById('content');
                j_cont.innerHTML = "";
                var url = "$";
                PDFJS.getDocument(url).then(function(pdf) {
                for (var pageNum = 1; pageNum <= pdf.numPages; ++pageNum) {
                    pdf.getPage(pageNum).then(function(page) {
                        var canvas = document.createElement('canvas');
                        j_cont.appendChild(canvas);
                        
                        canvas.style.cssText = 'width:100%; margin-bottom:16px; display:block;';
                        var viewport = page.getViewport(1.5/(canvas.width/page.getViewport(1.0).width));
                        var context = canvas.getContext('2d');
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;

                        page.getTextContent().then(function(text) {
                            page.render({
                                'canvasContext':context,
                                'viewport':viewport
                            })
                        })
                    });
                }
            })
            </script>
jinkela;
    private $m_pdf_path;
    public function __construct(string $pdf_path)
    {
        $this->m_pdf_path = $pdf_path;
    }

    public function html():string
    {
        $res = str_replace("$",$this->m_pdf_path,self::m_pdf_js);
        return $res;
    }
}
