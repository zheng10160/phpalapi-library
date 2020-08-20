<?php

class Domain_WaterMake {

    public function getWaterMake() {
        $PHPWord = new PHPWord_Lite();

// New portrait section
        $section = $PHPWord->createSection();

// Create header
        $header = $section->createHeader();

// Add a watermark to the header
        $header->addWatermark('_earth.jpg', array('marginTop'=>200, 'marginLeft'=>55));

        $section->addText('The header reference to the current section includes a watermark image.');

// Save File
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save('Watermark.docx');
        return 'WaterMake is created!';
    }
}
