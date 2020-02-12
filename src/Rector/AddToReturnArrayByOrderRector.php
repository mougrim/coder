<?php
declare(strict_types=1);

namespace CrmPlease\Coder\Rector;

use CrmPlease\Coder\Helper\AddToArrayByOrderHelper;
use CrmPlease\Coder\Helper\CheckMethodHelper;
use CrmPlease\Coder\Helper\GetNodeArrayHelper;
use PhpParser\Node;
use PhpParser\Node\Stmt\Return_;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @author Mougrim <rinat@mougrim.ru>
 */
class AddToReturnArrayByOrderRector extends AbstractRector
{
    private $checkMethodHelper;
    private $getNodeArrayHelper;
    private $addToArrayByOrderHelper;
    private $method = '';
    private $value;
    private $constant = '';

    public function __construct(
        CheckMethodHelper $checkMethodHelper,
        GetNodeArrayHelper $getNodeArrayHelper,
        AddToArrayByOrderHelper $addToArrayByOrderHelper
    )
    {
        $this->checkMethodHelper = $checkMethodHelper;
        $this->getNodeArrayHelper = $getNodeArrayHelper;
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
     * @param string|float|int $value
     *
     * @return $this
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $constant
     *
     * @return $this
     */
    public function setConstant(string $constant): self
    {
        $this->constant = $constant;
        return $this;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Add to method "getArray" to return array value "newValue" with check duplicates', [
            new CodeSample(
                <<<'PHP'
class SomeClass
{
    public function getArray()
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
    public function getArray()
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

        $nodeArray = $this->getNodeArrayHelper->getFromReturnStatement($node);
        $result = $this->addToArrayByOrderHelper->addToArrayByOrder($this->value, $this->constant, $nodeArray);
        return $result ? $node : null;
    }
}