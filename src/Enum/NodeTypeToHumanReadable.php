<?php

declare(strict_types=1);

namespace App\Enum;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Cast\Unset_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\While_;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;

/**
 * @api used in blade
 */
final class NodeTypeToHumanReadable
{
    /**
     * @var array<string, array<string, array<class-string<Node>>>>
     */
    public const SELECT_ITEMS_BY_GROUP = [
        'Modern' => [
            'Attributes' => [AttributeGroup::class, Attribute::class],
        ],
        'Class-likes' => [
            'Class' => [Class_::class],
            'Interface' => [Interface_::class],
            'Trait' => [Trait_::class, TraitUse::class],
            'Enum' => [Enum_::class, EnumCase::class],
        ],
        'Class elements' => [
            'Constant' => [ClassConst::class, ClassConstFetch::class],
            'Property' => [Property::class, PropertyFetch::class, StaticPropertyFetch::class],
            'Method' => [ClassMethod::class],
        ],
        'Function-likes' => [
            'Functions' => [Function_::class],
            'Closures & Arrow functions' => [Closure::class, ArrowFunction::class],
            'Parameter' => [Param::class],
        ],
        'Globals' => [
            'Include & Require' => [Include_::class],
            'Constants' => [Const_::class, ConstFetch::class],
        ],
        'Calls' => [
            'Method calls' => [MethodCall::class],
            'Static calls' => [StaticCall::class],
            'Function calls' => [FuncCall::class],
            'Argument' => [Arg::class],
            'Nullsafe method call' => [NullsafeMethodCall::class],
            'New instance' => [New_::class],
        ],
        'Operations' => [
            'Binary (+, -, /...)' => [BinaryOp::class],
            'Assign (+=, -=...)' => [AssignOp::class],
        ],
        'Scalars' => [
            'String' => [String_::class],
            'Decimal number' => [LNumber::class],
            'Float number' => [DNumber::class],
            'Casts' => [Cast::class],
            'Encapsed string' => [Encapsed::class],
        ],
        'Conditions' => [
            'If' => [If_::class, Else_::class, ElseIf_::class],
            'Ternary' => [Ternary::class],
        ],
        'Loops' => [
            'While & do' => [While_::class, Do_::class],
            'Foreach' => [Foreach_::class],
            'For' => [For_::class],
        ],
        'Arrays' => [
            'Array' => [Array_::class, ArrayItem::class],
            'Array dim fetch' => [ArrayDimFetch::class],
            'List' => [List_::class],
            'Unset/isset' => [Unset_::class, Isset_::class, Empty_::class],
        ],
        'Try' => [
            'Switch' => [Switch_::class],
            'Break statement' => [Break_::class],
            'Try-catch statement' => [TryCatch::class, Catch_::class],
            'Continue statement' => [Continue_::class],
        ],
        'Namespace' => [
            'Namespace-less' => [FileWithoutNamespace::class],
            'Namespace' => [Namespace_::class],
        ],
        'Rest' => [
            'Assignment' => [Assign::class],
            'Variable' => [Variable::class],
            'Fully qualified name' => [FullyQualified::class],
            'Expression' => [Expression::class],
            'Statement array' => [StmtsAwareInterface::class],
        ],
    ];
}
