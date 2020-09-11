<?php

use Icamys\SitemapGenerator\FileSystem;
use Icamys\SitemapGenerator\FileSystemUrlStorage;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class FileSystemUrlStorageTest extends TestCase
{
    use PHPMock;

    public function testSuccessfulObjectConstruction()
    {
        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(true);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);
        $this->assertInstanceOf(FileSystemUrlStorage::class, $storage);
    }

    public function testFailedFopenOnObjectConstruction()
    {
        $this->expectException(RuntimeException::class);

        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(false);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);
    }

    public function testCount()
    {
        $lastmodStr = '2020-05-15T18:47:02+00:00';

        $addArgs = [
            'http://example.com/url/path/',
            DateTime::createFromFormat(DATE_ATOM, $lastmodStr),
            'always',
            0.5,
            null,
        ];
        $fputcsv = [
            $addArgs[0],
            $lastmodStr,
            $addArgs[2],
            $addArgs[3],
            $addArgs[4],
        ];

        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(true);
        $fsMock
            ->method('fputcsv')
            ->with(true, $fputcsv)
            ->willReturn(true);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);

        $storage->add($addArgs[0], $addArgs[1], $addArgs[2], $addArgs[3], $addArgs[4]);
        $this->assertEquals(1, $storage->count());
    }

    public function testCurrent()
    {
        $lastmodStr = '2020-05-15T18:47:02+00:00';

        $addArgs = [
            'http://example.com/url/path/',
            DateTime::createFromFormat(DATE_ATOM, $lastmodStr),
            'always',
            0.5,
            null,
        ];
        $fputcsvArgs = [
            $addArgs[0],
            $lastmodStr,
            $addArgs[2],
            $addArgs[3],
            $addArgs[4],
        ];

        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(true);
        $fsMock
            ->method('fputcsv')
            ->with(true, $fputcsvArgs)
            ->willReturn(true);
        $fsMock
            ->method('fgetcsv')
            ->withConsecutive([true], [true])
            ->willReturnOnConsecutiveCalls($fputcsvArgs, false);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);
        $storage->add($addArgs[0], $addArgs[1], $addArgs[2], $addArgs[3], $addArgs[4]);
        $current = $storage->current();
        $this->assertEquals($current[0], $fputcsvArgs[0]);
    }

    public function testReadLineToUrlObjWithAlternatesANdEmptyLastmodAndPriority()
    {
        $lastmodStr = '';
        $alternatesArr = [
            ['hreflang' => 'de', 'href' => "http://www.example.com/de"],
            ['hreflang' => 'fr', 'href' => "http://www.example.com/fr"],
        ];
        $alternatesArrSerialized = 'a:2:{i:0;a:2:{s:8:"hreflang";s:2:"de";s:4:"href";s:25:"http://www.example.com/de";}i:1;a:2:{s:8:"hreflang";s:2:"fr";s:4:"href";s:25:"http://www.example.com/fr";}}';

        $addArgs = [
            'http://example.com/url/path/',
            null,
            '',
            null,
            $alternatesArr,
        ];
        $fputcsvArgs = [
            $addArgs[0],
            $lastmodStr,
            $addArgs[2],
            $addArgs[3],
            $alternatesArrSerialized,
        ];

        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(true);
        $fsMock
            ->method('fputcsv')
            ->with(true, $fputcsvArgs)
            ->willReturn(true);
        $fsMock
            ->method('fgetcsv')
            ->withConsecutive([true], [true])
            ->willReturnOnConsecutiveCalls($fputcsvArgs, false);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);
        $storage->add($addArgs[0], $addArgs[1], $addArgs[2], $addArgs[3], $addArgs[4]);
        $current = $storage->current();
        $this->assertEquals($current[0], $fputcsvArgs[0]);
    }

    public function testOutOfBoundExceptionWithNoUrlsInStorage()
    {
        $this->expectException(OutOfBoundsException::class);

        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(true);
        $fsMock
            ->method('fgetcsv')
            ->with(true)
            ->willReturn(false);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);
        $storage->current();
    }

    public function testOutOfBoundExceptionWithPresentUrlsInStorage()
    {
        $this->expectException(OutOfBoundsException::class);

        $lastmodStr = '2020-05-15T18:47:02+00:00';

        $addArgs = [
            'http://example.com/url/path/',
            DateTime::createFromFormat(DATE_ATOM, $lastmodStr),
            'always',
            0.5,
            null,
        ];
        $fputcsvArgs = [
            $addArgs[0],
            $lastmodStr,
            $addArgs[2],
            $addArgs[3],
            $addArgs[4],
        ];

        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(true);
        $fsMock
            ->method('fputcsv')
            ->with(true, $fputcsvArgs)
            ->willReturn(true);
        $fsMock
            ->method('fgetcsv')
            ->withConsecutive([true], [true])
            ->willReturnOnConsecutiveCalls($fputcsvArgs, false);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);
        $storage->add($addArgs[0], $addArgs[1], $addArgs[2], $addArgs[3], $addArgs[4]);
        $storage->current();
        $storage->next();
        $storage->current();
    }

    public function testRewind()
    {
        $lastmodStr = '2020-05-15T18:47:02+00:00';

        $addArgs = [
            'http://example.com/url/path/',
            DateTime::createFromFormat(DATE_ATOM, $lastmodStr),
            'always',
            0.5,
            null,
        ];
        $fputcsvArgs = [
            $addArgs[0],
            $lastmodStr,
            $addArgs[2],
            $addArgs[3],
            $addArgs[4],
        ];

        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(true);
        $fsMock
            ->method('fputcsv')
            ->with(true, $fputcsvArgs)
            ->willReturn(true);
        $fsMock
            ->method('rewind')
            ->with(true)
            ->willReturn(true);
        $fsMock
            ->method('fgetcsv')
            ->withConsecutive([true], [true])
            ->willReturnOnConsecutiveCalls($fputcsvArgs, $fputcsvArgs);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);
        $storage->add($addArgs[0], $addArgs[1], $addArgs[2], $addArgs[3], $addArgs[4]);
        $current = $storage->current();
        $this->assertEquals($current[0], $fputcsvArgs[0]);

        $storage->next();
        $storage->rewind();
        $current = $storage->current();
        $this->assertEquals($current[0], $fputcsvArgs[0]);
    }

    public function testInvalidHandleForFgetcsv()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to read line from url storage file: basepath/filename');

        $lastmodStr = '2020-05-15T18:47:02+00:00';

        $addArgs = [
            'http://example.com/url/path/',
            DateTime::createFromFormat(DATE_ATOM, $lastmodStr),
            'always',
            0.5,
            null,
        ];
        $fputcsvArgs = [
            $addArgs[0],
            $lastmodStr,
            $addArgs[2],
            $addArgs[3],
            $addArgs[4],
        ];

        $fsMock = $this->createMock(FileSystem::class);
        $fsMock
            ->method('fopen')
            ->withConsecutive(
                [$this->equalTo('basepath/filename'), $this->equalTo('w')],
                [$this->equalTo('basepath/filename'), $this->equalTo('r')]
            )
            ->willReturn(true);
        $fsMock
            ->method('fputcsv')
            ->with(true, $fputcsvArgs)
            ->willReturn(true);
        $fsMock
            ->method('fgetcsv')
            ->with(true)
            ->willReturn(null);

        $storage = new FileSystemUrlStorage('basepath', 'filename', $fsMock);
        $storage->add($addArgs[0], $addArgs[1], $addArgs[2], $addArgs[3], $addArgs[4]);
        $storage->current();
    }
}