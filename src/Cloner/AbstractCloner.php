<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stvy\DumperException\Cloner;

use Stvy\DumperException\Caster\Caster;
use Stvy\DumperException\Exception\ThrowingCasterException;

/**
 * AbstractCloner implements a generic caster mechanism for objects and resources.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
abstract class AbstractCloner implements ClonerInterface
{
    public static $defaultCasters = [
        '__PHP_Incomplete_Class' => ['Stvy\DumperException\Caster\Caster', 'castPhpIncompleteClass'],

        'Stvy\DumperException\Caster\CutStub' => ['Stvy\DumperException\Caster\StubCaster', 'castStub'],
        'Stvy\DumperException\Caster\CutArrayStub' => ['Stvy\DumperException\Caster\StubCaster', 'castCutArray'],
        'Stvy\DumperException\Caster\ConstStub' => ['Stvy\DumperException\Caster\StubCaster', 'castStub'],
        'Stvy\DumperException\Caster\EnumStub' => ['Stvy\DumperException\Caster\StubCaster', 'castEnum'],

        'Fiber' => ['Stvy\DumperException\Caster\FiberCaster', 'castFiber'],

        'Closure' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castClosure'],
        'Generator' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castGenerator'],
        'ReflectionType' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castType'],
        'ReflectionAttribute' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castAttribute'],
        'ReflectionGenerator' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castReflectionGenerator'],
        'ReflectionClass' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castClass'],
        'ReflectionClassConstant' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castClassConstant'],
        'ReflectionFunctionAbstract' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castFunctionAbstract'],
        'ReflectionMethod' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castMethod'],
        'ReflectionParameter' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castParameter'],
        'ReflectionProperty' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castProperty'],
        'ReflectionReference' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castReference'],
        'ReflectionExtension' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castExtension'],
        'ReflectionZendExtension' => ['Stvy\DumperException\Caster\ReflectionCaster', 'castZendExtension'],

        'Doctrine\Common\Persistence\ObjectManager' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'Doctrine\Common\Proxy\Proxy' => ['Stvy\DumperException\Caster\DoctrineCaster', 'castCommonProxy'],
        'Doctrine\ORM\Proxy\Proxy' => ['Stvy\DumperException\Caster\DoctrineCaster', 'castOrmProxy'],
        'Doctrine\ORM\PersistentCollection' => ['Stvy\DumperException\Caster\DoctrineCaster', 'castPersistentCollection'],
        'Doctrine\Persistence\ObjectManager' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],

        'DOMException' => ['Stvy\DumperException\Caster\DOMCaster', 'castException'],
        'DOMStringList' => ['Stvy\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMNameList' => ['Stvy\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMImplementation' => ['Stvy\DumperException\Caster\DOMCaster', 'castImplementation'],
        'DOMImplementationList' => ['Stvy\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMNode' => ['Stvy\DumperException\Caster\DOMCaster', 'castNode'],
        'DOMNameSpaceNode' => ['Stvy\DumperException\Caster\DOMCaster', 'castNameSpaceNode'],
        'DOMDocument' => ['Stvy\DumperException\Caster\DOMCaster', 'castDocument'],
        'DOMNodeList' => ['Stvy\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMNamedNodeMap' => ['Stvy\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMCharacterData' => ['Stvy\DumperException\Caster\DOMCaster', 'castCharacterData'],
        'DOMAttr' => ['Stvy\DumperException\Caster\DOMCaster', 'castAttr'],
        'DOMElement' => ['Stvy\DumperException\Caster\DOMCaster', 'castElement'],
        'DOMText' => ['Stvy\DumperException\Caster\DOMCaster', 'castText'],
        'DOMTypeinfo' => ['Stvy\DumperException\Caster\DOMCaster', 'castTypeinfo'],
        'DOMDomError' => ['Stvy\DumperException\Caster\DOMCaster', 'castDomError'],
        'DOMLocator' => ['Stvy\DumperException\Caster\DOMCaster', 'castLocator'],
        'DOMDocumentType' => ['Stvy\DumperException\Caster\DOMCaster', 'castDocumentType'],
        'DOMNotation' => ['Stvy\DumperException\Caster\DOMCaster', 'castNotation'],
        'DOMEntity' => ['Stvy\DumperException\Caster\DOMCaster', 'castEntity'],
        'DOMProcessingInstruction' => ['Stvy\DumperException\Caster\DOMCaster', 'castProcessingInstruction'],
        'DOMXPath' => ['Stvy\DumperException\Caster\DOMCaster', 'castXPath'],

        'XMLReader' => ['Stvy\DumperException\Caster\XmlReaderCaster', 'castXmlReader'],

        'ErrorException' => ['Stvy\DumperException\Caster\ExceptionCaster', 'castErrorException'],
        'Exception' => ['Stvy\DumperException\Caster\ExceptionCaster', 'castException'],
        'Error' => ['Stvy\DumperException\Caster\ExceptionCaster', 'castError'],
        'Symfony\Bridge\Monolog\Logger' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\DependencyInjection\ContainerInterface' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\EventDispatcher\EventDispatcherInterface' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\HttpClient\AmpHttpClient' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castHttpClient'],
        'Symfony\Component\HttpClient\CurlHttpClient' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castHttpClient'],
        'Symfony\Component\HttpClient\NativeHttpClient' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castHttpClient'],
        'Symfony\Component\HttpClient\Response\AmpResponse' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'Symfony\Component\HttpClient\Response\CurlResponse' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'Symfony\Component\HttpClient\Response\NativeResponse' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'Symfony\Component\HttpFoundation\Request' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castRequest'],
        'Symfony\Component\Uid\Ulid' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castUlid'],
        'Symfony\Component\Uid\Uuid' => ['Stvy\DumperException\Caster\SymfonyCaster', 'castUuid'],
        'Stvy\DumperException\Exception\ThrowingCasterException' => ['Stvy\DumperException\Caster\ExceptionCaster', 'castThrowingCasterException'],
        'Stvy\DumperException\Caster\TraceStub' => ['Stvy\DumperException\Caster\ExceptionCaster', 'castTraceStub'],
        'Stvy\DumperException\Caster\FrameStub' => ['Stvy\DumperException\Caster\ExceptionCaster', 'castFrameStub'],
        'Stvy\DumperException\Cloner\AbstractCloner' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\ErrorHandler\Exception\SilencedErrorContext' => ['Stvy\DumperException\Caster\ExceptionCaster', 'castSilencedErrorContext'],

        'Imagine\Image\ImageInterface' => ['Stvy\DumperException\Caster\ImagineCaster', 'castImage'],

        'Ramsey\Uuid\UuidInterface' => ['Stvy\DumperException\Caster\UuidCaster', 'castRamseyUuid'],

        'ProxyManager\Proxy\ProxyInterface' => ['Stvy\DumperException\Caster\ProxyManagerCaster', 'castProxy'],
        'PHPUnit_Framework_MockObject_MockObject' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'PHPUnit\Framework\MockObject\MockObject' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'PHPUnit\Framework\MockObject\Stub' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'Prophecy\Prophecy\ProphecySubjectInterface' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],
        'Mockery\MockInterface' => ['Stvy\DumperException\Caster\StubCaster', 'cutInternals'],

        'PDO' => ['Stvy\DumperException\Caster\PdoCaster', 'castPdo'],
        'PDOStatement' => ['Stvy\DumperException\Caster\PdoCaster', 'castPdoStatement'],

        'AMQPConnection' => ['Stvy\DumperException\Caster\AmqpCaster', 'castConnection'],
        'AMQPChannel' => ['Stvy\DumperException\Caster\AmqpCaster', 'castChannel'],
        'AMQPQueue' => ['Stvy\DumperException\Caster\AmqpCaster', 'castQueue'],
        'AMQPExchange' => ['Stvy\DumperException\Caster\AmqpCaster', 'castExchange'],
        'AMQPEnvelope' => ['Stvy\DumperException\Caster\AmqpCaster', 'castEnvelope'],

        'ArrayObject' => ['Stvy\DumperException\Caster\SplCaster', 'castArrayObject'],
        'ArrayIterator' => ['Stvy\DumperException\Caster\SplCaster', 'castArrayIterator'],
        'SplDoublyLinkedList' => ['Stvy\DumperException\Caster\SplCaster', 'castDoublyLinkedList'],
        'SplFileInfo' => ['Stvy\DumperException\Caster\SplCaster', 'castFileInfo'],
        'SplFileObject' => ['Stvy\DumperException\Caster\SplCaster', 'castFileObject'],
        'SplHeap' => ['Stvy\DumperException\Caster\SplCaster', 'castHeap'],
        'SplObjectStorage' => ['Stvy\DumperException\Caster\SplCaster', 'castObjectStorage'],
        'SplPriorityQueue' => ['Stvy\DumperException\Caster\SplCaster', 'castHeap'],
        'OuterIterator' => ['Stvy\DumperException\Caster\SplCaster', 'castOuterIterator'],
        'WeakReference' => ['Stvy\DumperException\Caster\SplCaster', 'castWeakReference'],

        'Redis' => ['Stvy\DumperException\Caster\RedisCaster', 'castRedis'],
        'RedisArray' => ['Stvy\DumperException\Caster\RedisCaster', 'castRedisArray'],
        'RedisCluster' => ['Stvy\DumperException\Caster\RedisCaster', 'castRedisCluster'],

        'DateTimeInterface' => ['Stvy\DumperException\Caster\DateCaster', 'castDateTime'],
        'DateInterval' => ['Stvy\DumperException\Caster\DateCaster', 'castInterval'],
        'DateTimeZone' => ['Stvy\DumperException\Caster\DateCaster', 'castTimeZone'],
        'DatePeriod' => ['Stvy\DumperException\Caster\DateCaster', 'castPeriod'],

        'GMP' => ['Stvy\DumperException\Caster\GmpCaster', 'castGmp'],

        'MessageFormatter' => ['Stvy\DumperException\Caster\IntlCaster', 'castMessageFormatter'],
        'NumberFormatter' => ['Stvy\DumperException\Caster\IntlCaster', 'castNumberFormatter'],
        'IntlTimeZone' => ['Stvy\DumperException\Caster\IntlCaster', 'castIntlTimeZone'],
        'IntlCalendar' => ['Stvy\DumperException\Caster\IntlCaster', 'castIntlCalendar'],
        'IntlDateFormatter' => ['Stvy\DumperException\Caster\IntlCaster', 'castIntlDateFormatter'],

        'Memcached' => ['Stvy\DumperException\Caster\MemcachedCaster', 'castMemcached'],

        'Ds\Collection' => ['Stvy\DumperException\Caster\DsCaster', 'castCollection'],
        'Ds\Map' => ['Stvy\DumperException\Caster\DsCaster', 'castMap'],
        'Ds\Pair' => ['Stvy\DumperException\Caster\DsCaster', 'castPair'],
        'Stvy\DumperException\Caster\DsPairStub' => ['Stvy\DumperException\Caster\DsCaster', 'castPairStub'],

        'CurlHandle' => ['Stvy\DumperException\Caster\ResourceCaster', 'castCurl'],

        ':dba' => ['Stvy\DumperException\Caster\ResourceCaster', 'castDba'],
        ':dba persistent' => ['Stvy\DumperException\Caster\ResourceCaster', 'castDba'],

        'GdImage' => ['Stvy\DumperException\Caster\ResourceCaster', 'castGd'],
        ':gd' => ['Stvy\DumperException\Caster\ResourceCaster', 'castGd'],

        ':mysql link' => ['Stvy\DumperException\Caster\ResourceCaster', 'castMysqlLink'],
        ':pgsql large object' => ['Stvy\DumperException\Caster\PgSqlCaster', 'castLargeObject'],
        ':pgsql link' => ['Stvy\DumperException\Caster\PgSqlCaster', 'castLink'],
        ':pgsql link persistent' => ['Stvy\DumperException\Caster\PgSqlCaster', 'castLink'],
        ':pgsql result' => ['Stvy\DumperException\Caster\PgSqlCaster', 'castResult'],
        ':process' => ['Stvy\DumperException\Caster\ResourceCaster', 'castProcess'],
        ':stream' => ['Stvy\DumperException\Caster\ResourceCaster', 'castStream'],

        'OpenSSLCertificate' => ['Stvy\DumperException\Caster\ResourceCaster', 'castOpensslX509'],
        ':OpenSSL X.509' => ['Stvy\DumperException\Caster\ResourceCaster', 'castOpensslX509'],

        ':persistent stream' => ['Stvy\DumperException\Caster\ResourceCaster', 'castStream'],
        ':stream-context' => ['Stvy\DumperException\Caster\ResourceCaster', 'castStreamContext'],

        'XmlParser' => ['Stvy\DumperException\Caster\XmlResourceCaster', 'castXml'],
        ':xml' => ['Stvy\DumperException\Caster\XmlResourceCaster', 'castXml'],

        'RdKafka' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castRdKafka'],
        'RdKafka\Conf' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castConf'],
        'RdKafka\KafkaConsumer' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castKafkaConsumer'],
        'RdKafka\Metadata\Broker' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castBrokerMetadata'],
        'RdKafka\Metadata\Collection' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castCollectionMetadata'],
        'RdKafka\Metadata\Partition' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castPartitionMetadata'],
        'RdKafka\Metadata\Topic' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castTopicMetadata'],
        'RdKafka\Message' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castMessage'],
        'RdKafka\Topic' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castTopic'],
        'RdKafka\TopicPartition' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castTopicPartition'],
        'RdKafka\TopicConf' => ['Stvy\DumperException\Caster\RdKafkaCaster', 'castTopicConf'],
    ];

    protected $maxItems = 2500;
    protected $maxString = -1;
    protected $minDepth = 1;

    /**
     * @var array<string, list<callable>>
     */
    private array $casters = [];

    /**
     * @var callable|null
     */
    private $prevErrorHandler;

    private array $classInfo = [];
    private int $filter = 0;

    /**
     * @param callable[]|null $casters A map of casters
     *
     * @see addCasters
     */
    public function __construct(array $casters = null)
    {
        if (null === $casters) {
            $casters = static::$defaultCasters;
        }
        $this->addCasters($casters);
    }

    /**
     * Adds casters for resources and objects.
     *
     * Maps resources or objects types to a callback.
     * Types are in the key, with a callable caster for value.
     * Resource types are to be prefixed with a `:`,
     * see e.g. static::$defaultCasters.
     *
     * @param callable[] $casters A map of casters
     */
    public function addCasters(array $casters)
    {
        foreach ($casters as $type => $callback) {
            $this->casters[$type][] = $callback;
        }
    }

    /**
     * Sets the maximum number of items to clone past the minimum depth in nested structures.
     */
    public function setMaxItems(int $maxItems)
    {
        $this->maxItems = $maxItems;
    }

    /**
     * Sets the maximum cloned length for strings.
     */
    public function setMaxString(int $maxString)
    {
        $this->maxString = $maxString;
    }

    /**
     * Sets the minimum tree depth where we are guaranteed to clone all the items.  After this
     * depth is reached, only setMaxItems items will be cloned.
     */
    public function setMinDepth(int $minDepth)
    {
        $this->minDepth = $minDepth;
    }

    /**
     * Clones a PHP variable.
     *
     * @param int $filter A bit field of Caster::EXCLUDE_* constants
     */
    public function cloneVar(mixed $var, int $filter = 0): Data
    {
        $this->prevErrorHandler = set_error_handler(function ($type, $msg, $file, $line, $context = []) {
            if (\E_RECOVERABLE_ERROR === $type || \E_USER_ERROR === $type) {
                // Cloner never dies
                throw new \ErrorException($msg, 0, $type, $file, $line);
            }

            if ($this->prevErrorHandler) {
                return ($this->prevErrorHandler)($type, $msg, $file, $line, $context);
            }

            return false;
        });
        $this->filter = $filter;

        if ($gc = gc_enabled()) {
            gc_disable();
        }
        try {
            return new Data($this->doClone($var));
        } finally {
            if ($gc) {
                gc_enable();
            }
            restore_error_handler();
            $this->prevErrorHandler = null;
        }
    }

    /**
     * Effectively clones the PHP variable.
     */
    abstract protected function doClone(mixed $var): array;

    /**
     * Casts an object to an array representation.
     *
     * @param bool $isNested True if the object is nested in the dumped structure
     */
    protected function castObject(Stub $stub, bool $isNested): array
    {
        $obj = $stub->value;
        $class = $stub->class;

        if (str_contains($class, "@anonymous\0")) {
            $stub->class = get_debug_type($obj);
        }
        if (isset($this->classInfo[$class])) {
            [$i, $parents, $hasDebugInfo, $fileInfo] = $this->classInfo[$class];
        } else {
            $i = 2;
            $parents = [$class];
            $hasDebugInfo = method_exists($class, '__debugInfo');

            foreach (class_parents($class) as $p) {
                $parents[] = $p;
                ++$i;
            }
            foreach (class_implements($class) as $p) {
                $parents[] = $p;
                ++$i;
            }
            $parents[] = '*';

            $r = new \ReflectionClass($class);
            $fileInfo = $r->isInternal() || $r->isSubclassOf(Stub::class) ? [] : [
                'file' => $r->getFileName(),
                'line' => $r->getStartLine(),
            ];

            $this->classInfo[$class] = [$i, $parents, $hasDebugInfo, $fileInfo];
        }

        $stub->attr += $fileInfo;
        $a = Caster::castObject($obj, $class, $hasDebugInfo, $stub->class);

        try {
            while ($i--) {
                if (!empty($this->casters[$p = $parents[$i]])) {
                    foreach ($this->casters[$p] as $callback) {
                        $a = $callback($obj, $a, $stub, $isNested, $this->filter);
                    }
                }
            }
        } catch (\Exception $e) {
            $a = [(Stub::TYPE_OBJECT === $stub->type ? Caster::PREFIX_VIRTUAL : '').'⚠' => new ThrowingCasterException($e)] + $a;
        }

        return $a;
    }

    /**
     * Casts a resource to an array representation.
     *
     * @param bool $isNested True if the object is nested in the dumped structure
     */
    protected function castResource(Stub $stub, bool $isNested): array
    {
        $a = [];
        $res = $stub->value;
        $type = $stub->class;

        try {
            if (!empty($this->casters[':'.$type])) {
                foreach ($this->casters[':'.$type] as $callback) {
                    $a = $callback($res, $a, $stub, $isNested, $this->filter);
                }
            }
        } catch (\Exception $e) {
            $a = [(Stub::TYPE_OBJECT === $stub->type ? Caster::PREFIX_VIRTUAL : '').'⚠' => new ThrowingCasterException($e)] + $a;
        }

        return $a;
    }
}
