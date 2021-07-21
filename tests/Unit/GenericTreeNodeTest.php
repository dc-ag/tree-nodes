<?php

declare(strict_types=1);

namespace TreeNodes\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use TreeNodes\TreeNode;
use TreeNodes\GenericTreeNode;
use InvalidArgumentException;
use Closure;

/**
 * @covers TreeNodes\GenericTreeNode
 * @uses TreeNodes\GenericIdGenerator
 */
final class GenericTreeNodeTest extends TestCase
{
    private Generator $faker;
    private TreeNode $demoTree;
    private Closure $orderWritingFindPredicate;
    private string $orderString = "";
    private string $idToFind = "";

    protected function setUp(): void
    {
        $this->faker = Factory::create();

        $treeNode = new GenericTreeNode("root", "root");

        $treeNodeFirstChild = new GenericTreeNode("1", "1");
        $treeNode->addChild($treeNodeFirstChild);

        $treeNodeSecondChild = new GenericTreeNode("2", "2");
        $treeNode->addChild($treeNodeSecondChild);

        $treeNodeSecondChildFirstChild = new GenericTreeNode("2.1", "2.1");
        $treeNodeSecondChild->addChild($treeNodeSecondChildFirstChild);

        $treeNodeSecondChildFirstChildFirstChild = new GenericTreeNode("2.1.1", "2.1.1");
        $treeNodeSecondChildFirstChild->addChild($treeNodeSecondChildFirstChildFirstChild);

        $treeNodeSecondChildFirstChildSecondChild = new GenericTreeNode("2.1.2", "2.1.2");
        $treeNodeSecondChildFirstChild->addChild($treeNodeSecondChildFirstChildSecondChild);

        $treeNodeSecondChildSecondChild = new GenericTreeNode("2.2", "2.2");
        $treeNodeSecondChild->addChild($treeNodeSecondChildSecondChild);

        $treeNodeThirdChild = new GenericTreeNode("3", "3");
        $treeNode->addChild($treeNodeThirdChild);

        $treeNodeThirdChildFirstChild = new GenericTreeNode("3.1", "3.1");
        $treeNodeThirdChild->addChild($treeNodeThirdChildFirstChild);

        $treeNodeFourthChild = new GenericTreeNode("4", "4");
        $treeNode->addChild($treeNodeFourthChild);

        $payload = new \stdClass();
        $payload->property = 'value';
        $treeNodeFifthChild = new GenericTreeNode("5", $payload);
        $treeNode->addChild($treeNodeFifthChild);

        $this->demoTree = $treeNode;
        $this->idToFind = "3.1";

        $visitFn = function(TreeNode $treeNode) {
            $separator = '' === $this->orderString ? '' : ', ';
            $this->orderString .= $separator . $treeNode->getId();
            return $treeNode->getId() === $this->idToFind;
        };

        $this->orderWritingFindPredicate = Closure::fromCallable($visitFn);
    }

    protected function tearDown(): void
    {
        unset($this->faker);
        unset($this->demoTree);
        unset($this->orderString);
        unset($this->orderWritingVisitor);
        unset($this->idToFind);
    }

    public function testGetterId(): void
    {
        $payload = $this->faker->domainName;
        $id = $this->faker->uuid;
        $treeNode = new GenericTreeNode($id, $payload);

        $this->assertEquals($id, $treeNode->getId());
    }

    public function testGetterPayload(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($payload === $treeNode->getPayload());
    }

    public function testGetterLevel(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->getLevel() === 0);
    }

    public function testGetterSetterParent(): void
    {
        $payloadParent = $this->faker->domainName;
        $treeNodeParent = new GenericTreeNode(null, $payloadParent);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->setParent($treeNodeParent);

        $this->assertTrue($treeNode->getParent() === $treeNodeParent);
    }

    public function testGetterChildren(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertIsArray($treeNode->getChildren());
    }

    public function testGetterNoOfChildren(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getNoOfChildren() === 3);
    }

    public function testAddChild(): void
    {
        $payloadChild = $this->faker->domainName;
        $treeNodeChild = new GenericTreeNode(null, $payloadChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);

        $treeNode->addChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 1);
    }

    public function testRemoveChild(): void
    {
        $payloadChild = $this->faker->domainName;
        $treeNodeChild = new GenericTreeNode(null, $payloadChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);

        $treeNode->addChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 1);

        $treeNode->removeChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);
    }

    public function testGetterNoOfDescendants(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode(null, $payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getNoOfDescendants() === 4);
    }

    public function testGetterIsLeaf(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->isLeaf());
    }

    public function testGetterIsRoot(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->isRoot());
    }

    public function testGetterRootForTreeNode(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode(null, $payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNodeChildThirdChild->getRootForTreeNode() === $treeNode);
    }

    public function testGetterHeight(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode(null, $payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getHeight() === 2);
    }

    public function testGetterRootIdAddingSubTreeToRoot(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payloadChildThirdFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChild = new GenericTreeNode(null, $payloadChildThirdFirstChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdFirstChild);

        $payloadChildThirdFirstChildFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildFirstChild = new GenericTreeNode(null, $payloadChildThirdFirstChildFirstChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildFirstChild);

        $payloadChildThirdFirstChildSecondChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildSecondChild = new GenericTreeNode(null, $payloadChildThirdFirstChildSecondChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildSecondChild);

        $payloadChildThirdSecondChild = $this->faker->domainName;
        $treeNodeChildThirdSecondChild = new GenericTreeNode(null, $payloadChildThirdSecondChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdSecondChild);

        $payload = $this->faker->domainName;
        $rootTreeNode = new GenericTreeNode(null, $payload);
        $rootTreeNode->addChild($treeNodeChildFirst);
        $rootTreeNode->addChild($treeNodeChildSecond);
        $rootTreeNode->addChild($treeNodeChildThird);

        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildFirst->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildSecond->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThird->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChildFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChildSecondChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdSecondChild->getRootId());
    }

    public function testGetterRootIdAddingChildsFromTopToBottom(): void
    {
        $payload = $this->faker->domainName;
        $rootTreeNode = new GenericTreeNode(null, $payload);

        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);
        $rootTreeNode->addChild($treeNodeChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);
        $rootTreeNode->addChild($treeNodeChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);
        $rootTreeNode->addChild($treeNodeChildThird);

        $payloadChildThirdFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChild = new GenericTreeNode(null, $payloadChildThirdFirstChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdFirstChild);

        $payloadChildThirdFirstChildFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildFirstChild = new GenericTreeNode(null, $payloadChildThirdFirstChildFirstChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildFirstChild);

        $payloadChildThirdFirstChildSecondChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildSecondChild = new GenericTreeNode(null, $payloadChildThirdFirstChildSecondChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildSecondChild);

        $payloadChildThirdSecondChild = $this->faker->domainName;
        $treeNodeChildThirdSecondChild = new GenericTreeNode(null, $payloadChildThirdSecondChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdSecondChild);

        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildFirst->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildSecond->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThird->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChildFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChildSecondChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdSecondChild->getRootId());
    }

    public function testFindPreOrder(): void
    {
        $expectedOrderStringBeforeFind = 'root, 1, 2, 2.1, 2.1.1, 2.1.2, 2.2, 3, 3.1';
        $this->orderString = '';
        $root = $this->demoTree;
        $foundNode = $root->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_PRE_ORDER);
        $errMsgOrder = "findNode with preorder does not traverse in expected order [$expectedOrderStringBeforeFind] - actual order [{$this->orderString}].";
        $errMsgNotFound = "findNode-test did not find a node present in the tree.";
        $this->assertTrue($foundNode instanceof TreeNode && $foundNode->getId() === '3.1', $errMsgNotFound);
        $this->assertEquals($expectedOrderStringBeforeFind, $this->orderString, $errMsgOrder);
    }

    public function testFindPreOrderNegative(): void
    {
        $expectedOrderStringBeforeFind = 'root, 1, 2, 2.1, 2.1.1, 2.1.2, 2.2, 3, 3.1, 4, 5';
        $this->orderString = '';
        $root = $this->demoTree;
        $this->idToFind = 'X';
        $foundNode = $root->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_PRE_ORDER);
        $this->idToFind = '3.1';
        $errMsgFound = "findNode-test did find something when tasked to search a non-present id.";
        $this->assertNull($foundNode, $errMsgFound);
    }

    public function testFindPostOrder(): void
    {
        $expectedOrderStringBeforeFind = '1, 2.1.1, 2.1.2, 2.1, 2.2, 2, 3.1';
        $this->orderString = '';
        $root = $this->demoTree;
        $foundNode = $root->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_POST_ORDER);
        $errMsgOrder = "findNode with postorder does not traverse in expected order [$expectedOrderStringBeforeFind] - actual order [{$this->orderString}].";
        $errMsgNotFound = "findNode-test did not find a node present in the tree.";
        $this->assertTrue($foundNode instanceof TreeNode && $foundNode->getId() === '3.1', $errMsgNotFound);
        $this->assertEquals($expectedOrderStringBeforeFind, $this->orderString, $errMsgOrder);
    }

    public function testFindPostOrderNegative(): void
    {
        $expectedOrderStringBeforeFind = '1, 2.1.1, 2.1.2, 2.1, 2.2, 2, 3.1, 3, 4, 5, root';
        $this->orderString = '';
        $root = $this->demoTree;
        $this->idToFind = 'X';
        $foundNode = $root->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_POST_ORDER);
        $this->idToFind = '3.1';
        $errMsgFound = "findNode-test did find something when tasked to search a non-present id.";
        $this->assertNull($foundNode, $errMsgFound);
    }

    public function testFindLevelOrder(): void
    {
        $expectedOrderStringBeforeFind = 'root, 1, 2, 3, 4, 5, 2.1, 2.2, 3.1, 2.1.1';
        $this->orderString = '';
        $root = $this->demoTree;
        $this->idToFind = '2.1.1';
        $foundNode = $root->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_LEVEL_ORDER);
        $errMsgOrder = "findNode with level-order does not traverse in expected order [$expectedOrderStringBeforeFind] - actual order [{$this->orderString}].";
        $errMsgNotFound = "findNode-test did not find a node present in the tree.";
        $this->assertTrue($foundNode instanceof TreeNode && $foundNode->getId() === '2.1.1', $errMsgNotFound);
        $this->assertEquals($expectedOrderStringBeforeFind, $this->orderString, $errMsgOrder);
    }

    
    public function testFindLeveltOrderNegative(): void
    {
        $expectedOrderStringBeforeFind = 'root, 1, 2, 3, 4, 5, 2.1, 2.2, 3.1, 2.1.1, 2.1.2';
        $this->orderString = '';
        $root = $this->demoTree;
        $this->idToFind = 'X';
        $foundNode = $root->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_LEVEL_ORDER);
        $this->idToFind = '3.1';
        $errMsgFound = "findNode-test did find something when tasked to search a non-present id.";
        $this->assertNull($foundNode, $errMsgFound);
    }

    public function testFindThrowsErrorOnInvalidSearchOption(): void
    {
        $root = $this->demoTree;
        $this->expectException(InvalidArgumentException::class);
        $root->findNode($this->orderWritingFindPredicate, 8);
    }

    public function testGetDeepCopy(): void
    {
        $demoTreeCopy = $this->demoTree->getDeepCopy();
        $demoTreeCopy->addChild(new GenericTreeNode('6','6'));

        $this->orderString = '';
        $this->idToFind = "6";
        $shouldBeAddedNode = $demoTreeCopy->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_PRE_ORDER);
        $isAddedNode = $shouldBeAddedNode instanceof TreeNode && $shouldBeAddedNode->getId() === '6';
        $orderStringForDeepCopyFind = $this->orderString;

        $this->orderString = '';
        $shouldBeNull = $this->demoTree->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_PRE_ORDER);
        $this->orderString = '';

        
        $errMsgOldInstanceChanged = 'Modifying the result of getDeepCopy modifies the original tree.';
        $errMsgNewInstanceNotChanged = 'Could not modify deep-copied instance of tree. Pre-order structure is [' . $this->orderString . '].';
        
        $this->assertNull($shouldBeNull, $errMsgOldInstanceChanged);
        $this->assertTrue($isAddedNode, $errMsgNewInstanceNotChanged);
    }

    public function testRemoveChildById(): void
    {
        $root = $this->demoTree->getDeepCopy();
        $root->removeChildById("4");
        $this->orderString = '';
        $expectedOrderStringPreOrder = 'root, 1, 2, 2.1, 2.1.1, 2.1.2, 2.2, 3, 3.1, 5';
        $this->idToFind = "5";
        $root->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_PRE_ORDER);
        $errMsg = "Expected preorder-structure after removal [$expectedOrderStringPreOrder] - actual structure [{$this->orderString}].";
        $this->assertEquals($expectedOrderStringPreOrder, $this->orderString, $errMsg);
        
    }

    public function testReplaceDescendant(): void
    {
        $newSubtreeRoot = new GenericTreeNode("A","A");
        $newSubtreeFirstChild = new GenericTreeNode("B", 'B');
        $newSubtreeSecondChild = new GenericTreeNode("C", 'C');
        $newSubtreeThirdChild = new GenericTreeNode("D", 'D');
        $newSubtreeLevel2Node1 = new GenericTreeNode("C.1", 'C.1');
        $newSubtreeLevel2Node2 = new GenericTreeNode("C.2", 'C.2');

        $newSubtreeSecondChild->addChild($newSubtreeLevel2Node1);
        $newSubtreeSecondChild->addChild($newSubtreeLevel2Node2);

        $newSubtreeRoot->addChild($newSubtreeFirstChild);
        $newSubtreeRoot->addChild($newSubtreeSecondChild);
        $newSubtreeRoot->addChild($newSubtreeThirdChild);

        $expectedOrderStringPreOrder =  'root, 1, 2, 2.1, 2.1.1, 2.1.2, A, B, C, C.1, C.2, D, 3, 3.1, 4, 5';
        $this->orderString = '';
        $tree = $this->demoTree->getDeepCopy();
        $toReplaceNode = $tree->findNode(static fn(?TreeNode $n) => null !== $n && $n->getId() === '2.2', TreeNode::SEARCH_PRE_ORDER);
        $tree->replaceDescendant($toReplaceNode, $newSubtreeRoot);
        $this->idToFind = '5';
        $tree->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_PRE_ORDER);
        $errMsg = 'Expected traversal order with replaced node should be [' . $expectedOrderStringPreOrder . '] - is [' . $this->orderString . '].';
        $this->assertEquals($expectedOrderStringPreOrder, $this->orderString, $errMsg);
    }

    public function testReplaceDescendantById(): void
    {
        $newSubtreeRoot = new GenericTreeNode("A","A");
        $newSubtreeFirstChild = new GenericTreeNode("B", 'B');
        $newSubtreeSecondChild = new GenericTreeNode("C", 'C');
        $newSubtreeThirdChild = new GenericTreeNode("D", 'D');
        $newSubtreeLevel2Node1 = new GenericTreeNode("C.1", 'C.1');
        $newSubtreeLevel2Node2 = new GenericTreeNode("C.2", 'C.2');

        $newSubtreeSecondChild->addChild($newSubtreeLevel2Node1);
        $newSubtreeSecondChild->addChild($newSubtreeLevel2Node2);

        $newSubtreeRoot->addChild($newSubtreeFirstChild);
        $newSubtreeRoot->addChild($newSubtreeSecondChild);
        $newSubtreeRoot->addChild($newSubtreeThirdChild);

        $expectedOrderStringPreOrder =  'root, 1, 2, 2.1, 2.1.1, 2.1.2, A, B, C, C.1, C.2, D, 3, 3.1, 4, 5';
        $this->orderString = '';
        $tree = $this->demoTree->getDeepCopy();
        $tree->replaceDescendantById('2.2', $newSubtreeRoot);
        $this->idToFind = '5';
        $tree->findNode($this->orderWritingFindPredicate, TreeNode::SEARCH_PRE_ORDER);
        $errMsg = 'Expected traversal order with replaced node should be [' . $expectedOrderStringPreOrder . '] - is [' . $this->orderString . '].';
        $this->assertEquals($expectedOrderStringPreOrder, $this->orderString, $errMsg);
    }

}
