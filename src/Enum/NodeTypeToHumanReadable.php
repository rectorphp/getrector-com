<?php

declare(strict_types=1);

namespace Rector\Website\Enum;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Minus;
use PhpParser\Node\Expr\AssignOp\Plus;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Div;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BinaryOp\Mul;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Cast\Double;
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
use PhpParser\Node\FunctionLike;
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
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
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
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\While_;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;

final class NodeTypeToHumanReadable
{
    /**
     * @var array<class-string<Node>, string>
     */
    public const MAP = [
        Class_::class => 'class',
        ClassMethod::class => 'class method',
        MethodCall::class => 'method call',
        FuncCall::class => 'function call',
        StaticCall::class => 'static call',
        Function_::class => 'function',
        Closure::class => 'closure',
        StmtsAwareInterface::class => 'statements',
        Property::class => 'property',
        If_::class => 'if',
        Ternary::class => 'ternary',
        Expression::class => 'expression',
        Identical::class => '===',
        BooleanNot::class => 'boolean not',
        New_::class => 'new instance',
        Assign::class => 'assignment',
        Foreach_::class => 'foreach statement',
        String_::class => 'string',
        ArrowFunction::class => 'arrow function',
        ClassConst::class => 'class constant',
        NotIdentical::class => '!==',
        FileWithoutNamespace::class => 'files without namespace',
        Switch_::class => 'switch',
        Namespace_::class => 'namespace',
        Interface_::class => 'interface',
        Param::class => 'parameter',
        ClassLike::class => 'class-like statements',
        NotEqual::class => '!=',
        While_::class => 'while statement',
        BooleanAnd::class => '&&',
        For_::class => 'for statement',
        Array_::class => 'array',
        Equal::class => 'equal binary operation',
        ConstFetch::class => 'constant fetch',
        Do_::class => 'do statement',
        Empty_::class => 'empty expression',
        BooleanOr::class => 'boolean or operation',
        ElseIf_::class => 'elseif statement',
        Encapsed::class => 'encapsed string',
        Variable::class => 'variable',
        Break_::class => 'break statement',
        PropertyFetch::class => 'property fetch',
        FunctionLike::class => 'function-like',
        ClassConstFetch::class => 'class constant fetch',
        NullsafeMethodCall::class => 'nullsafe method call',
        Concat::class => 'concatenation binary operation',
        Plus::class => 'assignment plus operation',
        Minus::class => 'assignment minus operation',
        TryCatch::class => 'try-catch statement',
        ArrayDimFetch::class => 'array dimension fetch',
        BinaryOp::class => 'binary operation',
        StaticPropertyFetch::class => 'static property fetch',
        List_::class => 'list',
        Unset_::class => 'unset cast',
        FullyQualified::class => 'fully qualified name',
        \PhpParser\Node\Expr\BinaryOp\Plus::class => '+',
        \PhpParser\Node\Expr\BinaryOp\Minus::class => '-',
        Mul::class => '*',
        Div::class => '/',
        \PhpParser\Node\Expr\AssignOp\Mul::class => '=*',
        \PhpParser\Node\Expr\AssignOp\Div::class => '=/',
        Cast::class => 'cast',
        Trait_::class => 'trait',
        LNumber::class => 'integer literal',
        DNumber::class => 'float literal',
        Double::class => 'double cast',
        Catch_::class => 'catch statement',
        Else_::class => 'else statement',
        Isset_::class => 'isset expression',
        Include_::class => 'include expression',
        LogicalOr::class => 'logical or operation',
        LogicalAnd::class => 'logical and operation',
        ArrayItem::class => 'array item',
        Arg::class => 'argument',
        Continue_::class => 'continue statement',
        \PhpParser\Node\Scalar\MagicConst\Class_::class => 'class magic constant',
        Greater::class => 'greater than binary operation',
        Smaller::class => 'less than binary operation',
    ];
}
