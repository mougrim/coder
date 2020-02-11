<?php
declare(strict_types=1);

namespace CrmPlease\Coder\Rector;

use CrmPlease\Coder\Helper\AddToArrayByOrderHelper;
use CrmPlease\Coder\Helper\GetNodeArrayHelper;
use PhpParser\Node;
use PhpParser\Node\Stmt\PropertyProperty;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @author Mougrim <rinat@mougrim.ru>
 */
class AddToPropertyArrayByOrderRector extends AbstractRector
{
    private $getNodeArrayHelper;
    private $addToArrayByOrderHelper;
    private $property = '';
    private $value;
    private $constant = '';

    public function __construct(GetNodeArrayHelper $getNodeArrayHelper, AddToArrayByOrderHelper $addToArrayByOrderHelper)
    {
        $this->getNodeArrayHelper = $getNodeArrayHelper;
        $this->addToArrayByOrderHelper = $addToArrayByOrderHelper;
    }

    /**
     * @param string $property
     *
     * @return $this
     */
    public function setProperty(string $property): self
    {
        $this->property = $property;
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
        return new RectorDefinition('Add to property "array" value "newValue" with check duplicates', [
            new CodeSample(
                <<<'PHP'
class SomeClass
{
    protected $array = [
        'existsValue',
    ];
}
PHP
                ,
                <<<'PHP'
class SomeClass
{
    protected $array = [
        'existsValue',
        'newValue',
    ];
}
PHP
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [PropertyProperty::class];
    }

    /**
     * @param Node $node
     *
     * @return Node|null
     * @throws RectorException
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof PropertyProperty) {
            return null;
        }

        if ($node->name->name !== $this->property) {
            return null;
        }

        $arrayNode = $this->getNodeArrayHelper->getFromPropertyPropertyStatement($node);
        $result = $this->addToArrayByOrderHelper->addToArrayByOrder($this->value, $this->constant, $arrayNode);
        return $result ? $node : null;
    }
}
