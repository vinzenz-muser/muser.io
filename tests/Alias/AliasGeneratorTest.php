<?php

declare(strict_types=1);

namespace App\Tests\Alias;

use App\Alias\AliasGenerator;
use Contao\CoreBundle\Slug\Slug;
use Contao\CoreBundle\Translation\Translator;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AliasGeneratorTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $connection = $this->createMock(Connection::class);
        $slugGenerator = $this->createMock(Slug::class);
        $translator = $this->createMock(Translator::class);

        $aliasGenerator = new AliasGenerator($connection, $slugGenerator, $translator);

        $this->assertInstanceOf('App\Alias\AliasGenerator', $aliasGenerator);
    }

    public function testThrowsAnExceptionIfAliasExists(): void
    {
        $table = 'foo_bar';
        $alias = 'foobar';
        $id = 1;

        $statement = $this->createMock(Statement::class);
        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([0])
        ;

        $connection = $this->createMock(Connection::class);
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $translator = $this->createMock(Translator::class);
        $translator
            ->expects($this->once())
            ->method('trans')
            ->willReturn('ERR.aliasExists')
        ;

        $slugGenerator = $this->createMock(Slug::class);
        $dc = $this->createMock(DataContainer::class);

        $dc->id = $id;
        $dc->table = $table;

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('ERR.aliasExists');

        $aliasGenerator = new AliasGenerator($connection, $slugGenerator, $translator);
        $aliasGenerator->generate($alias, $dc);
    }

    public function testReturnsAliasIfNotExists(): void
    {
        $table = 'foo_bar';
        $alias = 'foobar';
        $id = 1;

        $statement = $this->createMock(Statement::class);
        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([])
        ;

        $connection = $this->createMock(Connection::class);
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $translator = $this->createMock(Translator::class);
        $slugGenerator = $this->createMock(Slug::class);
        $dc = $this->createMock(DataContainer::class);

        $dc->id = $id;
        $dc->table = $table;

        $aliasGenerator = new AliasGenerator($connection, $slugGenerator, $translator);
        $this->assertEquals($alias, $aliasGenerator->generate($alias, $dc));
    }

    public function testGeneratesAlias(): void
    {
        $alias = 'foobar';

        $statement = $this->createMock(Statement::class);
        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([])
        ;

        $connection = $this->createMock(Connection::class);
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $translator = $this->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $slugGenerator = $this->createMock(Slug::class);
        $slugGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($alias)
        ;

        $activeRecord = new \stdClass();
        $activeRecord->pid = 1;
        $activeRecord->title = 'foobar';

        /** @var DataContainer $dc */
        $dc = $this->mockClassWithProperties(DataContainer::class, [
            'activeRecord' => $activeRecord,
        ]);

        $aliasGenerator = new AliasGenerator($connection, $slugGenerator, $translator);
        $this->assertEquals('foobar', $aliasGenerator->generate('', $dc));
    }

    protected function mockClassWithProperties(string $class, array $properties = []): MockObject
    {
        $mock = $this->createMock($class);
        $mock
            ->method('__get')
            ->willReturnCallback(
                static function (string $key) use (&$properties) {
                    return $properties[$key] ?? null;
                }
            )
        ;

        if (\in_array('__set', get_class_methods($class), true)) {
            $mock
                ->method('__set')
                ->willReturnCallback(
                    static function (string $key, $value) use (&$properties) {
                        $properties[$key] = $value;
                    }
                )
            ;
        }

        if (\in_array('__isset', get_class_methods($class), true)) {
            $mock
                ->method('__isset')
                ->willReturnCallback(
                    static function (string $key) use (&$properties) {
                        return isset($properties[$key]);
                    }
                )
            ;
        }

        return $mock;
    }
}
