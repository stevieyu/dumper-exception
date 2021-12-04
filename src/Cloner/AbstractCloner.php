<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stevie\DumperException\Cloner;

use Stevie\DumperException\Caster\Caster;
use Stevie\DumperException\Exception\ThrowingCasterException;

/**
 * AbstractCloner implements a generic caster mechanism for objects and resources.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
abstract class AbstractCloner implements ClonerInterface
{
    public static $defaultCasters = [
        '__PHP_Incomplete_Class' => ['Stevie\DumperException\Caster\Caster', 'castPhpIncompleteClass'],

        'Stevie\DumperException\Caster\CutStub' => ['Stevie\DumperException\Caster\StubCaster', 'castStub'],
        'Stevie\DumperException\Caster\CutArrayStub' => ['Stevie\DumperException\Caster\StubCaster', 'castCutArray'],
        'Stevie\DumperException\Caster\ConstStub' => ['Stevie\DumperException\Caster\StubCaster', 'castStub'],
        'Stevie\DumperException\Caster\EnumStub' => ['Stevie\DumperException\Caster\StubCaster', 'castEnum'],

        'Fiber' => ['Stevie\DumperException\Caster\FiberCaster', 'castFiber'],

        'Closure' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castClosure'],
        'Generator' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castGenerator'],
        'ReflectionType' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castType'],
        'ReflectionAttribute' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castAttribute'],
        'ReflectionGenerator' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castReflectionGenerator'],
        'ReflectionClass' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castClass'],
        'ReflectionClassConstant' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castClassConstant'],
        'ReflectionFunctionAbstract' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castFunctionAbstract'],
        'ReflectionMethod' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castMethod'],
        'ReflectionParameter' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castParameter'],
        'ReflectionProperty' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castProperty'],
        'ReflectionReference' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castReference'],
        'ReflectionExtension' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castExtension'],
        'ReflectionZendExtension' => ['Stevie\DumperException\Caster\ReflectionCaster', 'castZendExtension'],

        'Doctrine\Common\Persistence\ObjectManager' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'Doctrine\Common\Proxy\Proxy' => ['Stevie\DumperException\Caster\DoctrineCaster', 'castCommonProxy'],
        'Doctrine\ORM\Proxy\Proxy' => ['Stevie\DumperException\Caster\DoctrineCaster', 'castOrmProxy'],
        'Doctrine\ORM\PersistentCollection' => ['Stevie\DumperException\Caster\DoctrineCaster', 'castPersistentCollection'],
        'Doctrine\Persistence\ObjectManager' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],

        'DOMException' => ['Stevie\DumperException\Caster\DOMCaster', 'castException'],
        'DOMStringList' => ['Stevie\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMNameList' => ['Stevie\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMImplementation' => ['Stevie\DumperException\Caster\DOMCaster', 'castImplementation'],
        'DOMImplementationList' => ['Stevie\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMNode' => ['Stevie\DumperException\Caster\DOMCaster', 'castNode'],
        'DOMNameSpaceNode' => ['Stevie\DumperException\Caster\DOMCaster', 'castNameSpaceNode'],
        'DOMDocument' => ['Stevie\DumperException\Caster\DOMCaster', 'castDocument'],
        'DOMNodeList' => ['Stevie\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMNamedNodeMap' => ['Stevie\DumperException\Caster\DOMCaster', 'castLength'],
        'DOMCharacterData' => ['Stevie\DumperException\Caster\DOMCaster', 'castCharacterData'],
        'DOMAttr' => ['Stevie\DumperException\Caster\DOMCaster', 'castAttr'],
        'DOMElement' => ['Stevie\DumperException\Caster\DOMCaster', 'castElement'],
        'DOMText' => ['Stevie\DumperException\Caster\DOMCaster', 'castText'],
        'DOMTypeinfo' => ['Stevie\DumperException\Caster\DOMCaster', 'castTypeinfo'],
        'DOMDomError' => ['Stevie\DumperException\Caster\DOMCaster', 'castDomError'],
        'DOMLocator' => ['Stevie\DumperException\Caster\DOMCaster', 'castLocator'],
        'DOMDocumentType' => ['Stevie\DumperException\Caster\DOMCaster', 'castDocumentType'],
        'DOMNotation' => ['Stevie\DumperException\Caster\DOMCaster', 'castNotation'],
        'DOMEntity' => ['Stevie\DumperException\Caster\DOMCaster', 'castEntity'],
        'DOMProcessingInstruction' => ['Stevie\DumperException\Caster\DOMCaster', 'castProcessingInstruction'],
        'DOMXPath' => ['Stevie\DumperException\Caster\DOMCaster', 'castXPath'],

        'XMLReader' => ['Stevie\DumperException\Caster\XmlReaderCaster', 'castXmlReader'],

        'ErrorException' => ['Stevie\DumperException\Caster\ExceptionCaster', 'castErrorException'],
        'Exception' => ['Stevie\DumperException\Caster\ExceptionCaster', 'castException'],
        'Error' => ['Stevie\DumperException\Caster\ExceptionCaster', 'castError'],
        'Symfony\Bridge\Monolog\Logger' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\DependencyInjection\ContainerInterface' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\EventDispatcher\EventDispatcherInterface' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\HttpClient\AmpHttpClient' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castHttpClient'],
        'Symfony\Component\HttpClient\CurlHttpClient' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castHttpClient'],
        'Symfony\Component\HttpClient\NativeHttpClient' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castHttpClient'],
        'Symfony\Component\HttpClient\Response\AmpResponse' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'Symfony\Component\HttpClient\Response\CurlResponse' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'Symfony\Component\HttpClient\Response\NativeResponse' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'Symfony\Component\HttpFoundation\Request' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castRequest'],
        'Symfony\Component\Uid\Ulid' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castUlid'],
        'Symfony\Component\Uid\Uuid' => ['Stevie\DumperException\Caster\SymfonyCaster', 'castUuid'],
        'Stevie\DumperException\Exception\ThrowingCasterException' => ['Stevie\DumperException\Caster\ExceptionCaster', 'castThrowingCasterException'],
        'Stevie\DumperException\Caster\TraceStub' => ['Stevie\DumperException\Caster\ExceptionCaster', 'castTraceStub'],
        'Stevie\DumperException\Caster\FrameStub' => ['Stevie\DumperException\Caster\ExceptionCaster', 'castFrameStub'],
        'Stevie\DumperException\Cloner\AbstractCloner' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\ErrorHandler\Exception\SilencedErrorContext' => ['Stevie\DumperException\Caster\ExceptionCaster', 'castSilencedErrorContext'],

        'Imagine\Image\ImageInterface' => ['Stevie\DumperException\Caster\ImagineCaster', 'castImage'],

        'Ramsey\Uuid\UuidInterface' => ['Stevie\DumperException\Caster\UuidCaster', 'castRamseyUuid'],

        'ProxyManager\Proxy\ProxyInterface' => ['Stevie\DumperException\Caster\ProxyManagerCaster', 'castProxy'],
        'PHPUnit_Framework_MockObject_MockObject' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'PHPUnit\Framework\MockObject\MockObject' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'PHPUnit\Framework\MockObject\Stub' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'Prophecy\Prophecy\ProphecySubjectInterface' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],
        'Mockery\MockInterface' => ['Stevie\DumperException\Caster\StubCaster', 'cutInternals'],

        'PDO' => ['Stevie\DumperException\Caster\PdoCaster', 'castPdo'],
        'PDOStatement' => ['Stevie\DumperException\Caster\PdoCaster', 'castPdoStatement'],

        'AMQPConnection' => ['Stevie\DumperException\Caster\AmqpCaster', 'castConnection'],
        'AMQPChannel' => ['Stevie\DumperException\Caster\AmqpCaster', 'castChannel'],
        'AMQPQueue' => ['Stevie\DumperException\Caster\AmqpCaster', 'castQueue'],
        'AMQPExchange' => ['Stevie\DumperException\Caster\AmqpCaster', 'castExchange'],
        'AMQPEnvelope' => ['Stevie\DumperException\Caster\AmqpCaster', 'castEnvelope'],

        'ArrayObject' => ['Stevie\DumperException\Caster\SplCaster', 'castArrayObject'],
        'ArrayIterator' => ['Stevie\DumperException\Caster\SplCaster', 'castArrayIterator'],
        'SplDoublyLinkedList' => ['Stevie\DumperException\Caster\SplCaster', 'castDoublyLinkedList'],
        'SplFileInfo' => ['Stevie\DumperException\Caster\SplCaster', 'castFileInfo'],
        'SplFileObject' => ['Stevie\DumperException\Caster\SplCaster', 'castFileObject'],
        'SplHeap' => ['Stevie\DumperException\Caster\SplCaster', 'castHeap'],
        'SplObjectStorage' => ['Stevie\DumperException\Caster\SplCaster', 'castObjectStorage'],
        'SplPriorityQueue' => ['Stevie\DumperException\Caster\SplCaster', 'castHeap'],
        'OuterIterator' => ['Stevie\DumperException\Caster\SplCaster', 'castOuterIterator'],
        'WeakReference' => ['Stevie\DumperException\Caster\SplCaster', 'castWeakReference'],

        'Redis' => ['Stevie\DumperException\Caster\RedisCaster', 'castRedis'],
        'RedisArray' => ['Stevie\DumperException\Caster\RedisCaster', 'castRedisArray'],
        'RedisCluster' => ['Stevie\DumperException\Caster\RedisCaster', 'castRedisCluster'],

        'DateTimeInterface' => ['Stevie\DumperException\Caster\DateCaster', 'castDateTime'],
        'DateInterval' => ['Stevie\DumperException\Caster\DateCaster', 'castInterval'],
        'DateTimeZone' => ['Stevie\DumperException\Caster\DateCaster', 'castTimeZone'],
        'DatePeriod' => ['Stevie\DumperException\Caster\DateCaster', 'castPeriod'],

        'GMP' => ['Stevie\DumperException\Caster\GmpCaster', 'castGmp'],

        'MessageFormatter' => ['Stevie\DumperException\Caster\IntlCaster', 'castMessageFormatter'],
        'NumberFormatter' => ['Stevie\DumperException\Caster\IntlCaster', 'castNumberFormatter'],
        'IntlTimeZone' => ['Stevie\DumperException\Caster\IntlCaster', 'castIntlTimeZone'],
        'IntlCalendar' => ['Stevie\DumperException\Caster\IntlCaster', 'castIntlCalendar'],
        'IntlDateFormatter' => ['Stevie\DumperException\Caster\IntlCaster', 'castIntlDateFormatter'],

        'Memcached' => ['Stevie\DumperException\Caster\MemcachedCaster', 'castMemcached'],

        'Ds\Collection' => ['Stevie\DumperException\Caster\DsCaster', 'castCollection'],
        'Ds\Map' => ['Stevie\DumperException\Caster\DsCaster', 'castMap'],
        'Ds\Pair' => ['Stevie\DumperException\Caster\DsCaster', 'castPair'],
        'Stevie\DumperException\Caster\DsPairStub' => ['Stevie\DumperException\Caster\DsCaster', 'castPairStub'],

        'CurlHandle' => ['Stevie\DumperException\Caster\ResourceCaster', 'castCurl'],

        ':dba' => ['Stevie\DumperException\Caster\ResourceCaster', 'castDba'],
        ':dba persistent' => ['Stevie\DumperException\Caster\ResourceCaster', 'castDba'],

        'GdImage' => ['Stevie\DumperException\Caster\ResourceCaster', 'castGd'],
        ':gd' => ['Stevie\DumperException\Caster\ResourceCaster', 'castGd'],

        ':mysql link' => ['Stevie\DumperException\Caster\ResourceCaster', 'castMysqlLink'],
        ':pgsql large object' => ['Stevie\DumperException\Caster\PgSqlCaster', 'castLargeObject'],
        ':pgsql link' => ['Stevie\DumperException\Caster\PgSqlCaster', 'castLink'],
        ':pgsql link persistent' => ['Stevie\DumperException\Caster\PgSqlCaster', 'castLink'],
        ':pgsql result' => ['Stevie\DumperException\Caster\PgSqlCaster', 'castResult'],
        ':process' => ['Stevie\DumperException\Caster\ResourceCaster', 'castProcess'],
        ':stream' => ['Stevie\DumperException\Caster\ResourceCaster', 'castStream'],

        'OpenSSLCertificate' => ['Stevie\DumperException\Caster\ResourceCaster', 'castOpensslX509'],
        ':OpenSSL X.509' => ['Stevie\DumperException\Caster\ResourceCaster', 'castOpensslX509'],

        ':persistent stream' => ['Stevie\DumperException\Caster\ResourceCaster', 'castStream'],
        ':stream-context' => ['Stevie\DumperException\Caster\ResourceCaster', 'castStreamContext'],

        'XmlParser' => ['Stevie\DumperException\Caster\XmlResourceCaster', 'castXml'],
        ':xml' => ['Stevie\DumperException\Caster\XmlResourceCaster', 'castXml'],

        'RdKafka' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castRdKafka'],
        'RdKafka\Conf' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castConf'],
        'RdKafka\KafkaConsumer' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castKafkaConsumer'],
        'RdKafka\Metadata\Broker' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castBrokerMetadata'],
        'RdKafka\Metadata\Collection' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castCollectionMetadata'],
        'RdKafka\Metadata\Partition' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castPartitionMetadata'],
        'RdKafka\Metadata\Topic' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castTopicMetadata'],
        'RdKafka\Message' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castMessage'],
        'RdKafka\Topic' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castTopic'],
        'RdKafka\TopicPartition' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castTopicPartition'],
        'RdKafka\TopicConf' => ['Stevie\DumperException\Caster\RdKafkaCaster', 'castTopicConf'],
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
