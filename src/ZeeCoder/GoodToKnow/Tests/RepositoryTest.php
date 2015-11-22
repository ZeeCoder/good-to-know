<?php

namespace ZeeCoder\GoodToKnow;

use ZeeCoder\GoodToKnow\Repository\FactObjectRepository;
use ZeeCoder\GoodToKnow\Repository\PhpFileRepository;
use ZeeCoder\GoodToKnow\Repository\RepositoryInterface;
use ZeeCoder\GoodToKnow\Repository\YamlFileRepository;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function runRepositoryTest(RepositoryInterface $repo)
    {
        // Test getting everything
        $collection = $repo->findAllByGroups();

        $this->assertCount(3, $collection);
        $collection->rewind();

        $this->assertEquals('text1', $collection->current()->getText());

        $collection->next();
        $this->assertEquals('text2', $collection->current()->getText());

        $collection->next();
        $this->assertEquals('text3', $collection->current()->getText());

        // Get group1
        $collection = $repo->findAllByGroups(['group1']);
        $collection->rewind();

        $this->assertCount(2, $collection);

        $this->assertEquals('text1', $collection->current()->getText());

        $collection->next();
        $this->assertEquals('text2', $collection->current()->getText());

        // Get group2
        $collection = $repo->findAllByGroups(['group2']);
        $collection->rewind();

        $this->assertCount(2, $collection);

        $this->assertEquals('text2', $collection->current()->getText());

        $collection->next();
        $this->assertEquals('text3', $collection->current()->getText());

        // Get both explicitly
        $collection = $repo->findAllByGroups(['group1', 'group2']);
        $collection->rewind();

        $this->assertCount(3, $collection);

        $this->assertEquals('text1', $collection->current()->getText());

        $collection->next();
        $this->assertEquals('text2', $collection->current()->getText());

        $collection->next();
        $this->assertEquals('text3', $collection->current()->getText());
    }

    public function testFactObjectRepository()
    {
        $this->runRepositoryTest(
            (new FactObjectRepository)
                ->attach(
                    (new Fact)
                        ->setText('text1')
                        ->addGroup('group1')
                )
                ->attach(
                    (new Fact)
                        ->setText('text2')
                        ->addGroup('group1')
                        ->addGroup('group2')
                )
                ->attach(
                    (new Fact)
                        ->setText('text3')
                        ->addGroup('group2')
                )
        );
    }

    public function testPhpFileRepository()
    {
        $this->runRepositoryTest(
            new PhpFileRepository(__DIR__ . '/assets/gtk.php')
        );
    }

    public function testYamlFileRepository()
    {
        $this->runRepositoryTest(
            new YamlFileRepository(__DIR__ . '/assets/gtk.yml')
        );
    }
}
