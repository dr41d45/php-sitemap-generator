<?php

namespace Icamys\SitemapGenerator;

use phpmock\phpunit\PHPMock;
use phpmock\spy\Spy;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase
{
    use PHPMock;

    /**
     * @var FileSystem
     */
    private $fs;

    /**
     * @var Spy for file_put_contents function
     */
    private $filePutContentsSpy;

    /**
     * @var Spy for fopen function
     */
    private $fopenSpy;

    /**
     * @var Spy for fclose function
     */
    private $fcloseSpy;

    /**
     * @var Spy for fputcsv function
     */
    private $fputcsvSpy;

    /**
     * @var Spy for fgetcsv function
     */
    private $fgetcsvSpy;

    /**
     * @var Spy for rewind function
     */
    private $rewindSpy;

    /**
     * @var Spy for file_get_contents function
     */
    private $fileGetContentsSpy;

    /**
     * @var Spy for file_exists function
     */
    private $fileExistsSpy;

    /**
     * @var Spy for gzopen function
     */
    private $gzopenSpy;

    /**
     * @var Spy for gzwrite function
     */
    private $gzwriteSpy;

    /**
     * @var Spy for gzclose function
     */
    private $gzcloseSpy;

    public function testFopenCall() {
        $this->fs->fopen('path', 'r');
        $this->assertCount(1, $this->fopenSpy->getInvocations());
        $this->assertEquals('path', $this->fopenSpy->getInvocations()[0]->getArguments()[0]);
        $this->assertEquals('r', $this->fopenSpy->getInvocations()[0]->getArguments()[1]);
    }

    public function testFcloseCall() {
        $this->fs->fclose('path');
        $this->assertCount(1, $this->fcloseSpy->getInvocations());
        $this->assertEquals('path', $this->fcloseSpy->getInvocations()[0]->getArguments()[0]);
    }

    public function testFputsCall() {
        $this->fs->fputcsv('path', []);
        $this->assertCount(1, $this->fputcsvSpy->getInvocations());
        $this->assertEquals('path', $this->fputcsvSpy->getInvocations()[0]->getArguments()[0]);
        $this->assertEquals([], $this->fputcsvSpy->getInvocations()[0]->getArguments()[1]);
    }

    public function testFgetsCall() {
        $this->fs->fgetcsv('path');
        $this->assertCount(1, $this->fgetcsvSpy->getInvocations());
        $this->assertEquals('path', $this->fgetcsvSpy->getInvocations()[0]->getArguments()[0]);
    }

    public function testRewindCall() {
        $this->fs->rewind('path');
        $this->assertCount(1, $this->rewindSpy->getInvocations());
        $this->assertEquals('path', $this->rewindSpy->getInvocations()[0]->getArguments()[0]);
    }

    public function testFilePutContentsCall() {
        $this->fs->file_put_contents('path', 'contents');
        $this->assertCount(1, $this->filePutContentsSpy->getInvocations());
        $this->assertEquals('path', $this->filePutContentsSpy->getInvocations()[0]->getArguments()[0]);
        $this->assertEquals('contents', $this->filePutContentsSpy->getInvocations()[0]->getArguments()[1]);
    }

    public function testFileGetContentsCall() {
        $this->fs->file_get_contents('path');
        $this->assertCount(1, $this->fileGetContentsSpy->getInvocations());
        $this->assertEquals('path', $this->fileGetContentsSpy->getInvocations()[0]->getArguments()[0]);
    }

    public function testFileExistsCall() {
        $this->fs->file_exists('path');
        $this->assertCount(1, $this->fileExistsSpy->getInvocations());
        $this->assertEquals('path', $this->fileExistsSpy->getInvocations()[0]->getArguments()[0]);
    }

    public function testGzipFileOpenCall() {
        $this->fs->gzopen('path', 'w');
        $this->assertCount(1, $this->gzopenSpy->getInvocations());
        $this->assertEquals('path', $this->gzopenSpy->getInvocations()[0]->getArguments()[0]);
        $this->assertEquals('w', $this->gzopenSpy->getInvocations()[0]->getArguments()[1]);
    }

    public function testGzipFileWriteCall() {
        $this->fs->gzwrite(true, 'content');
        $this->assertCount(1, $this->gzwriteSpy->getInvocations());
        $this->assertEquals(true, $this->gzwriteSpy->getInvocations()[0]->getArguments()[0]);
        $this->assertEquals('content', $this->gzwriteSpy->getInvocations()[0]->getArguments()[1]);
    }

    public function testGzipFileCloseCall() {
        $this->fs->gzclose(true);
        $this->assertCount(1, $this->gzcloseSpy->getInvocations());
        $this->assertEquals(true, $this->gzcloseSpy->getInvocations()[0]->getArguments()[0]);
    }

    /**
     * @throws \phpmock\MockEnabledException
     */
    protected function setUp(): void
    {
        $this->fs = new FileSystem();
        $this->fopenSpy = new Spy(__NAMESPACE__, "fopen", function (){});
        $this->fopenSpy->enable();
        $this->fcloseSpy = new Spy(__NAMESPACE__, "fclose", function (){});
        $this->fcloseSpy->enable();
        $this->fputcsvSpy = new Spy(__NAMESPACE__, "fputcsv", function (){});
        $this->fputcsvSpy->enable();
        $this->fgetcsvSpy = new Spy(__NAMESPACE__, "fgetcsv", function (){});
        $this->fgetcsvSpy->enable();
        $this->rewindSpy = new Spy(__NAMESPACE__, "rewind", function (){});
        $this->rewindSpy->enable();
        $this->filePutContentsSpy = new Spy(__NAMESPACE__, "file_put_contents", function (){});
        $this->filePutContentsSpy->enable();
        $this->fileGetContentsSpy = new Spy(__NAMESPACE__, "file_get_contents", function (){});
        $this->fileGetContentsSpy->enable();
        $this->fileExistsSpy = new Spy(__NAMESPACE__, "file_exists", function (){});
        $this->fileExistsSpy->enable();
        $this->gzopenSpy = new Spy(__NAMESPACE__, "gzopen", function (){});
        $this->gzopenSpy->enable();
        $this->gzwriteSpy = new Spy(__NAMESPACE__, "gzwrite", function (){});
        $this->gzwriteSpy->enable();
        $this->gzcloseSpy = new Spy(__NAMESPACE__, "gzclose", function (){});
        $this->gzcloseSpy->enable();
    }

    protected function tearDown(): void
    {
        unset($this->g);
        $this->fopenSpy->disable();
        $this->fcloseSpy->disable();
        $this->fputcsvSpy->disable();
        $this->fgetcsvSpy->disable();
        $this->rewindSpy->disable();
        $this->filePutContentsSpy->disable();
        $this->fileGetContentsSpy->disable();
        $this->fileExistsSpy->disable();
        $this->gzopenSpy->disable();
        $this->gzwriteSpy->disable();
        $this->gzcloseSpy->disable();
    }
}