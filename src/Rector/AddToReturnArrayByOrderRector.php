<?php
declare(strict_types=1);

namespace Crmplease\Coder\Rector;

use Crmplease\Coder\Code;
use Crmplease\Coder\Constant;
use Crmplease\Coder\Helper\AddToArrayByOrderHelper;
use Crmplease\Coder\Helper\CheckMethodHelper;
use Crmplease\Coder\Helper\NodeArrayHelper;
use PhpParser\Node;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;

/**
 * @author Mougrim <rinat@mougrim.ru>
 */
class AddToReturnArrayByOrderRector extends AbstractRector
{
    private $checkMethodHelper;
    private $nodeArrayHelper;
    private $addToArrayByOrderHelper;
    private $method = '';
    private $path = [];
    private $value;

    public function __construct(
        CheckMethodHelper $checkMethodHelper,
        NodeArrayHelper $nodeArrayHelper,
        AddToArrayByOrderHelper $addToArrayByOrderHelper
    )
    {
        $this->checkMethodHelper = $checkMethodHelper;
        $this->nodeArrayHelper = $nodeArrayHelper;
        $this->addToArrayByOrderHelper = $addToArrayByOrderHelper;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string[]|int[]|Constant[] $path
     *
     * @return $this
     */
    public function setPath($path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string|float|int|array|Constant|Code $value
     *
     * @return $this
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Add to method "getArray" to return array value "newValue" with check duplicates', [
            new CodeSample(
                <<<'PHP'
class SomeClass
{
    public function getArray(): array
    {
        return [
            'existsValue',
        ];
    }
}
PHP
                ,
                <<<'PHP'
class SomeClass
{
    public function getArray(): array
    {
        return [
            'existsValue',
            'newValue',
        ];
    }
}
PHP
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Node $node
     *
     * @return Node|null
     * @throws RectorException
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Return_) {
            return null;
        }
        if (!$this->checkMethodHelper->checkMethod($this->method, $node)) {
            return null;
        }

        $arrayNode = $this->nodeArrayHelper->getFromReturnStatement($node);
        $arrayNode = $this->nodeArrayHelper->findOrAddArrayByPath($this->path, $arrayNode);
        $this->addToArrayByOrderHelper->addToArrayByOrder($this->value, $arrayNode);
        return $node;
    }
}
