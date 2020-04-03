Small library for a basic but very flexible tree data structure. You have the possibility to sort tree nodes and control the payload type within the tree nodes.

## Overview

The interface [`TreeNodes\TreeNode`](src/TreeNode.php) is the most basic abstract concept of a tree node. A tree is seen as a set of `TreeNode` objects which may or may not have a set of children (which are objects implementing the interface [`TreeNodes\TreeNode`](src/TreeNode.php)) and a payload (can be a simple integer or boolean or a complex object).
We offer different kind of tree nodes. Choose one which fits your use-case the best.

**Tree Node types**

- [TreeNode](src/TreeNode.php)
- [TypedPayloadTreeNode](src/TypedPayloadTreeNode.php)
- [SortableTreeNode](src/SortableTreeNode.php)
- [TypedPayloadSortableTreeNode](src/TypedPayloadSortableTreeNode.php)

For each type a generic implementation class exists, e.g. [`TreeNodes\GenericTreeNode`](src/GenericTreeNode.php) is a basic implementation of the interface [`TreeNodes\TreeNode`](src/TreeNode.php).

## Install

Best way to get started is to use [Composer](https://getcomposer.org/).

`composer require dynamic-commerce/tree-nodes`

## Basic usage

In the following examples we use the following example tree structure:

```
        Root
        /  \
       A    B
     /   \
    C     D
  / | \
 E  F  G
```

**Id Generator**

Each tree node has a id property. To generate this id, you can specify your own IdGenerator using the interface [`TreeNodes\IdGenerator`](src/IdGenerator.php). If you want to use the default, just pass null as value to the constructor. Default case uses the [`TreeNodes\GenericIdGenerator`](src/GenericIdGenerator.php) to generate a v4 UUID.

**Create a new TreeNode**

```php
use TreeNodes\GenericTreeNode;

$treeNode = new GenericTreeNode('Root');
```

The interface [`TreeNodes\TypedPayloadTreeNode`](src/TypedPayloadTreeNode.php) offers a set of payload types as public constants you can use to restrict the type of the payload.

```php
use TreeNodes\GenericTypedPayloadTreeNode;
use TreeNodes\TypedPayloadTreeNode;

$treeNode = new GenericTypedPayloadTreeNode('Root', TypedPayloadTreeNode::PAYLOAD_TYPE_STRING, null);
```

If you want to have a object as payload to just be a specific class you can simply provide the FQDN.

```php
use TreeNodes\GenericTypedPayloadTreeNode;
use TreeNodes\TypedPayloadTreeNode;

$myObject = new MyObject();
$treeNode = new GenericTypedPayloadTreeNode($myObject, TypedPayloadTreeNode::PAYLOAD_TYPE_OBJECT_WITH_FQDN,MyObject::class);
```

**Adding children**

```php
use TreeNodes\GenericTreeNode;

$rootTreeNode = new GenericTreeNode('Root');
$treeNodeA = new GenericTreeNode('A');

$rootTreeNode->addChild($treeNodeA);
```

**TreeNodes with sorting**

When you add a new sortable child the sorting will be calculated and set to the next highest sorting of the current children within the current parent node.

```php
use TreeNodes\GenericSortableTreeNode;

$rootTreeNode = new GenericSortableTreeNode('Root');

$treeNodeA = new GenericSortableTreeNode('A');
$rootTreeNode->addChildWithSorting($treeNodeA);

echo $treeNodeA->getPerLevelSorting(); //Prints 1 (first child for current parent node)

$treeNodeB = new GenericSortableTreeNode('B');
$rootTreeNode->addChildWithSorting($treeNodeB);

echo $treeNodeB->getPerLevelSorting(); //Prints 2 (second child for current parent node)
```

When you want to change the sorting of a tree node you can simply request it. Use the static method `processNewSortingRequest` providing the node you want to move and the new sorting.

```php
//assume we want to move `$treeNodeB` to sorting 1
GenericSortableTreeNode::processNewSortingRequest($treeNodeB, 1);

echo $treeNodeA->getPerLevelSorting(); //Prints 2 now (automatically changed within the sort request)
echo $treeNodeB->getPerLevelSorting(); //Prints 1 now
```

Not only can you change the sorting within a tree node children you can even move a node with all it's children to a new position. To do that you can use the static method `processMoveRequest` providing the node you want to move, the new parent node e.g. Root and the new sorting it should have.

```php
use TreeNodes\GenericSortableTreeNode;

$rootTreeNode = new GenericSortableTreeNode('Root');
$treeNodeA = new GenericSortableTreeNode('A');
$treeNodeB = new GenericSortableTreeNode('B');
$treeNodeC = new GenericSortableTreeNode('C');
$treeNodeD = new GenericSortableTreeNode('D');
$treeNodeE = new GenericSortableTreeNode('E');
$treeNodeF = new GenericSortableTreeNode('F');
$treeNodeG = new GenericSortableTreeNode('G');

$rootTreeNode->addChildWithSorting($treeNodeA);
$rootTreeNode->addChildWithSorting($treeNodeB);

$treeNodeA->addChildWithSorting($treeNodeC);
$treeNodeA->addChildWithSorting($treeNodeD);

$treeNodeC->addChildWithSorting($treeNodeE);
$treeNodeC->addChildWithSorting($treeNodeF);
$treeNodeC->addChildWithSorting($treeNodeG);

//assume we want to move C (with all children E,F,G) to new parent node Root and sorting 2.
GenericSortableTreeNode::processMoveRequest($treeNodeC,$rootTreeNode,2);

echo $treeNodeC->getParent()->getPayload(); //Prints Root
echo $treeNodeC->getLevel(); //Prints 1
echo $treeNodeC->getPerLevelSorting(); //Prints 2
```

You can implement your own classes for handling sort and move requests. Simple use the interface [`TreeNodes\TreeNodeSortRequestProcessor`](src/TreeNodeSortRequestProcessor.php) and the trait [`TreeNodes\canProcessTreeNodeSortRequests`](src/canProcessTreeNodeSortRequests.php).

**Remove a child**

```php
use TreeNodes\GenericTreeNode;

$rootTreeNode = new GenericTreeNode('Root');
$treeNodeA = new GenericTreeNode('A');

$rootTreeNode->addChild($treeNodeA);

echo $rootTreeNode->getNoOfChildren(); //Prints 1
$rootTreeNode->removeChild($treeNodeA);
echo $rootTreeNode->getNoOfChildren(); //Prints 0
```

Same as with adding children you can remove the children with different types by using separate functions

```php
use TreeNodes\GenericSortableTreeNode;

$rootTreeNode = new GenericSortableTreeNode('Root');
$treeNodeA = new GenericSortableTreeNode('A');

$rootTreeNode->addChildWithSorting($treeNodeA);

echo $rootTreeNode->getNoOfChildrenWithSorting(); //Prints 1
$rootTreeNode->removeChildWithSorting($treeNodeA);
echo $rootTreeNode->getNoOfChildrenWithSorting(); //Prints 0
```

## Testing

We use [PHPUnit](https://phpunit.de/) to automate testing and to detect issues. All unit tests are located in the [`/tests`](tests) folder. If you want to run them use the pre configured [`phpunit.xml`](phpunit.xml) and run `./vendor/bin/phpunit -c phpunit.xml` in your preferred CMD.
