<?php

declare(strict_types=1);

namespace Rector\Website\Enum;

use PhpParser\Node;
use PhpParser\Node\Arg;
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
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Enum_;
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

final class NodeTypeToHumanReadable
{
    /**
     * @api used in blade
     * @var array<string, array<class-string<Node>, string>>
     */
    public const SELECT_ITEMS_BY_GROUP = [
        'Modern' => [
            AttributeGroup::class => 'Attributes',
        ],
        'Class-likes' => [
            Class_::class => 'Class',
            Interface_::class => 'Interface',
            Trait_::class => 'Trait',
            Enum_::class => 'Enum',
        ],
        'Class elements' => [
            ClassConst::class => 'Constant',
            Property::class => 'Property',
            ClassMethod::class => 'Method',
            TraitUse::class => 'Trait use',
        ],
        'Function-likes' => [
            Function_::class => 'Functions',
            ArrowFunction::class => 'Arrow functions',
            Closure::class => 'Closures',
            Param::class => 'Parameter',
        ],
        'Fetches' => [
            PropertyFetch::class => 'Property fetch',
            StaticPropertyFetch::class => 'Static property fetch',
            ClassConstFetch::class => 'Class constant fetch',
            ConstFetch::class => 'Constant fetch',
        ],
        'Calls' => [
            MethodCall::class => 'Method calls',
            StaticCall::class => 'Static calls',
            FuncCall::class => 'Function calls',
            Arg::class => 'argument',
            NullsafeMethodCall::class => 'Nullsafe method call',
            New_::class => 'New instance',
        ],
        'Operations' => [
            BinaryOp::class => 'Binary operations (+, -, /...)',
            AssignOp::class => 'Assign operations(+=, -=...)',
        ],
        'Scalars' => [
            String_::class => 'String',
            LNumber::class => 'Decimal number',
            DNumber::class => 'Float number',
            Cast::class => 'Casts',
            Encapsed::class => 'Encapsed string',
        ],
        'Conditions and loops' => [
            If_::class => 'If',
            ElseIf_::class => 'Elseif',
            Else_::class => 'Else',
            Ternary::class => 'Ternary',
            While_::class => 'While',
            Do_::class => 'Do',
            Foreach_::class => 'Foreach',
            For_::class => 'For',
        ],
        'Arrays' => [
            Array_::class => 'Array',
            ArrayItem::class => 'Array item',
            ArrayDimFetch::class => 'Array dimension fetch',
            List_::class => 'List',
            Unset_::class => 'Unset',
            Isset_::class => 'Isset',
            Empty_::class => 'Empty',
        ],
        'Try' => [
            Switch_::class => 'Switch',
            Break_::class => 'Break statement',
            TryCatch::class => 'Try-catch statement',
            Catch_::class => 'Catch statement',
            Continue_::class => 'continue statement',
        ],
        'Namespace' => [
            FileWithoutNamespace::class => 'Namespace-less',
            Namespace_::class => 'Namespace',
            Include_::class => 'Include',
        ],
        'Rest' => [
            Assign::class => 'Assignment',
            Variable::class => 'Variable',
            FullyQualified::class => 'Fully qualified name',
            Expression::class => 'Expression',
            StmtsAwareInterface::class => 'Statement array',
        ],
    ];
}
