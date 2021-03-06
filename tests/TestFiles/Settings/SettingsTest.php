<?php

namespace Tests\Settings;

use PHPUnit\Framework\TestCase;
use Searcher\Search;

class SettingsTest extends TestCase {
    private $file;

    protected function setUp():void {
        $text = "текст рыба в котором нет смысла";

        $path_file = "./tests/TestFiles/file.txt";
        $f = fopen($path_file, "w");
        fwrite($f,$text);
        $this->file = $path_file;
        fclose($f);

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    function testSuccessTestSettings (){
        $searcher = new Search();
        $result = $searcher->search($this->file, 'рыба', './tests/TestFiles/Settings/files/goodSettings.yaml');
        $this->assertEquals($result[0]['string'], 1);
        $this->assertEquals($result[0]['positions'], 7);
    }

    function testNotFileSettings(){
        $searcher = new Search();
        $result = $searcher->search($this->file, 'ali', 'alipub');

        $this->assertEquals($result, 'FILE_SETTINGS[not_exist]');
    }

    function testMaxSizeErr(){
        $settings = "file:\n";
        $settings .= "   max_size_kb: 1";


        $path_file = "./tests/TestFiles/Settings/files/testSetting.yaml";
        $f = fopen($path_file, "w");
        fwrite($f,rtrim($settings));
        $this->file = $path_file;
        fclose($f);

        $searcher = new Search();
        $result = $searcher->search('./tests/TestFiles/Settings/files/flood.txt', 'ali', $path_file);

        $this->assertEquals($result, 'FILE[size_error]');
    }

    function testMimeType(){
        $settings = "file:\n";
        $settings .= "  mime_type:\n";
        $settings .= "      word: true";

        $path_file = "./tests/TestFiles/Settings/files/testSetting.yaml";
        $f = fopen($path_file, "w");
        fwrite($f,rtrim($settings));
        $this->file = $path_file;
        fclose($f);

        $searcher = new Search();
        $result = $searcher->search($this->file, 'ali', $path_file);

        $this->assertEquals($result, 'FILE[type_error]');
    }
}