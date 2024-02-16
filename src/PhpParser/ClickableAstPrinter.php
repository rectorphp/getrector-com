<?php

namespace Rector\Website\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\BitwiseAnd;
use PhpParser\Node\Expr\AssignOp\BitwiseOr;
use PhpParser\Node\Expr\AssignOp\BitwiseXor;
use PhpParser\Node\Expr\AssignOp\Coalesce;
use PhpParser\Node\Expr\AssignOp\Concat;
use PhpParser\Node\Expr\AssignOp\Div;
use PhpParser\Node\Expr\AssignOp\Minus;
use PhpParser\Node\Expr\AssignOp\Mod;
use PhpParser\Node\Expr\AssignOp\Mul;
use PhpParser\Node\Expr\AssignOp\Plus;
use PhpParser\Node\Expr\AssignOp\Pow;
use PhpParser\Node\Expr\AssignOp\ShiftLeft;
use PhpParser\Node\Expr\AssignOp\ShiftRight;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BinaryOp\LogicalXor;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\BinaryOp\Spaceship;
use PhpParser\Node\Expr\BitwiseNot;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Expr\Cast\Bool_;
use PhpParser\Node\Expr\Cast\Double;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\Cast\Object_;
use PhpParser\Node\Expr\Cast\Unset_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\Error;
use PhpParser\Node\Expr\ErrorSuppress;
use PhpParser\Node\Expr\Eval_;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\ShellExec;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\MatchArm;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\MagicConst\Class_;
use PhpParser\Node\Scalar\MagicConst\Dir;
use PhpParser\Node\Scalar\MagicConst\File;
use PhpParser\Node\Scalar\MagicConst\Function_;
use PhpParser\Node\Scalar\MagicConst\Line;
use PhpParser\Node\Scalar\MagicConst\Method;
use PhpParser\Node\Scalar\MagicConst\Namespace_;
use PhpParser\Node\Scalar\MagicConst\Trait_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Global_;
use PhpParser\Node\Stmt\Goto_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\HaltCompiler;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Label;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Static_;
use PhpParser\Node\Stmt\StaticVar;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\TraitUseAdaptation\Alias;
use PhpParser\Node\Stmt\TraitUseAdaptation\Precedence;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\UnionType;
use PhpParser\Node\VariadicPlaceholder;
use PhpParser\Node\VarLikeIdentifier;
use PhpParser\PrettyPrinter\Standard;
use Rector\Website\Enum\AttributeKey;

final class ClickableAstPrinter extends Standard
{
    public function __construct(
        private string $uuid
    ) {
        parent::__construct();
    }

    protected function pParam(Param $param): string
    {
        $nodeId = $param->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pParam($param));
    }

    protected function pArg(Arg $arg): string
    {
        $nodeId = $arg->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pArg($arg));
    }

    protected function pVariadicPlaceholder(VariadicPlaceholder $variadicPlaceholder): string
    {
        $nodeId = $variadicPlaceholder->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pVariadicPlaceholder($variadicPlaceholder)
        );
    }

    protected function pConst(Const_ $const): string
    {
        $nodeId = $const->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pConst($const));
    }

    protected function pNullableType(NullableType $nullableType): string
    {
        $nodeId = $nullableType->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pNullableType($nullableType));
    }

    protected function pUnionType(UnionType $unionType): string
    {
        $nodeId = $unionType->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pUnionType($unionType));
    }

    protected function pIntersectionType(IntersectionType $intersectionType): string
    {
        $nodeId = $intersectionType->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pIntersectionType($intersectionType)
        );
    }

    protected function pIdentifier(Identifier $identifier): string
    {
        $nodeId = $identifier->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pIdentifier($identifier));
    }

    protected function pVarLikeIdentifier(VarLikeIdentifier $varLikeIdentifier): string
    {
        $nodeId = $varLikeIdentifier->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pVarLikeIdentifier($varLikeIdentifier)
        );
    }

    protected function pAttribute(Attribute $attribute): string
    {
        $nodeId = $attribute->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pAttribute($attribute));
    }

    protected function pAttributeGroup(AttributeGroup $attributeGroup): string
    {
        $nodeId = $attributeGroup->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pAttributeGroup($attributeGroup));
    }

    protected function pName(Name $name): string
    {
        $nodeId = $name->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pName($name));
    }

    protected function pName_FullyQualified(FullyQualified $fullyQualified): string
    {
        $nodeId = $fullyQualified->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pName_FullyQualified($fullyQualified)
        );
    }

    protected function pName_Relative(Relative $relative): string
    {
        $nodeId = $relative->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pName_Relative($relative));
    }

    protected function pScalar_MagicConst_Class(Class_ $class): string
    {
        $nodeId = $class->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_MagicConst_Class($class));
    }

    protected function pScalar_MagicConst_Dir(Dir $dir): string
    {
        $nodeId = $dir->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_MagicConst_Dir($dir));
    }

    protected function pScalar_MagicConst_File(File $file): string
    {
        $nodeId = $file->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_MagicConst_File($file));
    }

    protected function pScalar_MagicConst_Function(Function_ $function): string
    {
        $nodeId = $function->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pScalar_MagicConst_Function($function)
        );
    }

    protected function pScalar_MagicConst_Line(Line $line): string
    {
        $nodeId = $line->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_MagicConst_Line($line));
    }

    protected function pScalar_MagicConst_Method(Method $method): string
    {
        $nodeId = $method->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_MagicConst_Method($method));
    }

    protected function pScalar_MagicConst_Namespace(Namespace_ $namespace): string
    {
        $nodeId = $namespace->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pScalar_MagicConst_Namespace($namespace)
        );
    }

    protected function pScalar_MagicConst_Trait(Trait_ $trait): string
    {
        $nodeId = $trait->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_MagicConst_Trait($trait));
    }

    protected function pScalar_String(String_ $string): string
    {
        $nodeId = $string->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_String($string));
    }

    protected function pScalar_Encapsed(Encapsed $encapsed): string
    {
        $nodeId = $encapsed->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_Encapsed($encapsed));
    }

    protected function pScalar_LNumber(LNumber $lNumber): string
    {
        $nodeId = $lNumber->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_LNumber($lNumber));
    }

    protected function pScalar_DNumber(DNumber $dNumber): string
    {
        $nodeId = $dNumber->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pScalar_DNumber($dNumber));
    }

    protected function pScalar_EncapsedStringPart(EncapsedStringPart $encapsedStringPart): string
    {
        $nodeId = $encapsedStringPart->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pScalar_EncapsedStringPart($encapsedStringPart)
        );
    }

    protected function pExpr_Assign(Assign $assign): string
    {
        $nodeId = $assign->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Assign($assign));
    }

    protected function pExpr_AssignRef(AssignRef $assignRef): string
    {
        $nodeId = $assignRef->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignRef($assignRef));
    }

    protected function pExpr_AssignOp_Plus(Plus $plus): string
    {
        $nodeId = $plus->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignOp_Plus($plus));
    }

    protected function pExpr_AssignOp_Minus(Minus $minus): string
    {
        $nodeId = $minus->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignOp_Minus($minus));
    }

    protected function pExpr_AssignOp_Mul(Mul $mul): string
    {
        $nodeId = $mul->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignOp_Mul($mul));
    }

    protected function pExpr_AssignOp_Div(Div $div): string
    {
        $nodeId = $div->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignOp_Div($div));
    }

    protected function pExpr_AssignOp_Concat(Concat $concat): string
    {
        $nodeId = $concat->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignOp_Concat($concat));
    }

    protected function pExpr_AssignOp_Mod(Mod $mod): string
    {
        $nodeId = $mod->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignOp_Mod($mod));
    }

    protected function pExpr_AssignOp_BitwiseAnd(BitwiseAnd $bitwiseAnd): string
    {
        $nodeId = $bitwiseAnd->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_AssignOp_BitwiseAnd($bitwiseAnd)
        );
    }

    protected function pExpr_AssignOp_BitwiseOr(BitwiseOr $bitwiseOr): string
    {
        $nodeId = $bitwiseOr->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_AssignOp_BitwiseOr($bitwiseOr)
        );
    }

    protected function pExpr_AssignOp_BitwiseXor(BitwiseXor $bitwiseXor): string
    {
        $nodeId = $bitwiseXor->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_AssignOp_BitwiseXor($bitwiseXor)
        );
    }

    protected function pExpr_AssignOp_ShiftLeft(ShiftLeft $shiftLeft): string
    {
        $nodeId = $shiftLeft->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_AssignOp_ShiftLeft($shiftLeft)
        );
    }

    protected function pExpr_AssignOp_ShiftRight(ShiftRight $shiftRight): string
    {
        $nodeId = $shiftRight->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_AssignOp_ShiftRight($shiftRight)
        );
    }

    protected function pExpr_AssignOp_Pow(Pow $pow): string
    {
        $nodeId = $pow->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignOp_Pow($pow));
    }

    protected function pExpr_AssignOp_Coalesce(Coalesce $coalesce): string
    {
        $nodeId = $coalesce->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_AssignOp_Coalesce($coalesce));
    }

    protected function pExpr_BinaryOp_Plus(\PhpParser\Node\Expr\BinaryOp\Plus $plus): string
    {
        $nodeId = $plus->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Plus($plus));
    }

    protected function pExpr_BinaryOp_Minus(\PhpParser\Node\Expr\BinaryOp\Minus $minus): string
    {
        $nodeId = $minus->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Minus($minus));
    }

    protected function pExpr_BinaryOp_Mul(\PhpParser\Node\Expr\BinaryOp\Mul $mul): string
    {
        $nodeId = $mul->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Mul($mul));
    }

    protected function pExpr_BinaryOp_Div(\PhpParser\Node\Expr\BinaryOp\Div $div): string
    {
        $nodeId = $div->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Div($div));
    }

    protected function pExpr_BinaryOp_Concat(\PhpParser\Node\Expr\BinaryOp\Concat $concat): string
    {
        $nodeId = $concat->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Concat($concat));
    }

    protected function pExpr_BinaryOp_Mod(\PhpParser\Node\Expr\BinaryOp\Mod $mod): string
    {
        $nodeId = $mod->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Mod($mod));
    }

    protected function pExpr_BinaryOp_BooleanAnd(BooleanAnd $booleanAnd): string
    {
        $nodeId = $booleanAnd->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_BooleanAnd($booleanAnd)
        );
    }

    protected function pExpr_BinaryOp_BooleanOr(BooleanOr $booleanOr): string
    {
        $nodeId = $booleanOr->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_BooleanOr($booleanOr)
        );
    }

    protected function pExpr_BinaryOp_BitwiseAnd(\PhpParser\Node\Expr\BinaryOp\BitwiseAnd $bitwiseAnd): string
    {
        $nodeId = $bitwiseAnd->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_BitwiseAnd($bitwiseAnd)
        );
    }

    protected function pExpr_BinaryOp_BitwiseOr(\PhpParser\Node\Expr\BinaryOp\BitwiseOr $bitwiseOr): string
    {
        $nodeId = $bitwiseOr->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_BitwiseOr($bitwiseOr)
        );
    }

    protected function pExpr_BinaryOp_BitwiseXor(\PhpParser\Node\Expr\BinaryOp\BitwiseXor $bitwiseXor): string
    {
        $nodeId = $bitwiseXor->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_BitwiseXor($bitwiseXor)
        );
    }

    protected function pExpr_BinaryOp_ShiftLeft(\PhpParser\Node\Expr\BinaryOp\ShiftLeft $shiftLeft): string
    {
        $nodeId = $shiftLeft->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_ShiftLeft($shiftLeft)
        );
    }

    protected function pExpr_BinaryOp_ShiftRight(\PhpParser\Node\Expr\BinaryOp\ShiftRight $shiftRight): string
    {
        $nodeId = $shiftRight->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_ShiftRight($shiftRight)
        );
    }

    protected function pExpr_BinaryOp_Pow(\PhpParser\Node\Expr\BinaryOp\Pow $pow): string
    {
        $nodeId = $pow->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Pow($pow));
    }

    protected function pExpr_BinaryOp_LogicalAnd(LogicalAnd $logicalAnd): string
    {
        $nodeId = $logicalAnd->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_LogicalAnd($logicalAnd)
        );
    }

    protected function pExpr_BinaryOp_LogicalOr(LogicalOr $logicalOr): string
    {
        $nodeId = $logicalOr->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_LogicalOr($logicalOr)
        );
    }

    protected function pExpr_BinaryOp_LogicalXor(LogicalXor $logicalXor): string
    {
        $nodeId = $logicalXor->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_LogicalXor($logicalXor)
        );
    }

    protected function pExpr_BinaryOp_Equal(Equal $equal): string
    {
        $nodeId = $equal->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Equal($equal));
    }

    protected function pExpr_BinaryOp_NotEqual(NotEqual $notEqual): string
    {
        $nodeId = $notEqual->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_NotEqual($notEqual));
    }

    protected function pExpr_BinaryOp_Identical(Identical $identical): string
    {
        $nodeId = $identical->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_Identical($identical)
        );
    }

    protected function pExpr_BinaryOp_NotIdentical(NotIdentical $notIdentical): string
    {
        $nodeId = $notIdentical->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_NotIdentical($notIdentical)
        );
    }

    protected function pExpr_BinaryOp_Spaceship(Spaceship $spaceship): string
    {
        $nodeId = $spaceship->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_Spaceship($spaceship)
        );
    }

    protected function pExpr_BinaryOp_Greater(Greater $greater): string
    {
        $nodeId = $greater->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Greater($greater));
    }

    protected function pExpr_BinaryOp_GreaterOrEqual(GreaterOrEqual $greaterOrEqual): string
    {
        $nodeId = $greaterOrEqual->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_GreaterOrEqual($greaterOrEqual)
        );
    }

    protected function pExpr_BinaryOp_Smaller(Smaller $smaller): string
    {
        $nodeId = $smaller->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Smaller($smaller));
    }

    protected function pExpr_BinaryOp_SmallerOrEqual(SmallerOrEqual $smallerOrEqual): string
    {
        $nodeId = $smallerOrEqual->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_BinaryOp_SmallerOrEqual($smallerOrEqual)
        );
    }

    protected function pExpr_BinaryOp_Coalesce(\PhpParser\Node\Expr\BinaryOp\Coalesce $coalesce): string
    {
        $nodeId = $coalesce->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BinaryOp_Coalesce($coalesce));
    }

    protected function pExpr_Instanceof(Instanceof_ $instanceof): string
    {
        $nodeId = $instanceof->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Instanceof($instanceof));
    }

    protected function pExpr_BooleanNot(BooleanNot $booleanNot): string
    {
        $nodeId = $booleanNot->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BooleanNot($booleanNot));
    }

    protected function pExpr_BitwiseNot(BitwiseNot $bitwiseNot): string
    {
        $nodeId = $bitwiseNot->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_BitwiseNot($bitwiseNot));
    }

    protected function pExpr_UnaryMinus(UnaryMinus $unaryMinus): string
    {
        $nodeId = $unaryMinus->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_UnaryMinus($unaryMinus));
    }

    protected function pExpr_UnaryPlus(UnaryPlus $unaryPlus): string
    {
        $nodeId = $unaryPlus->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_UnaryPlus($unaryPlus));
    }

    protected function pExpr_PreInc(PreInc $preInc): string
    {
        $nodeId = $preInc->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_PreInc($preInc));
    }

    protected function pExpr_PreDec(PreDec $preDec): string
    {
        $nodeId = $preDec->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_PreDec($preDec));
    }

    protected function pExpr_PostInc(PostInc $postInc): string
    {
        $nodeId = $postInc->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_PostInc($postInc));
    }

    protected function pExpr_PostDec(PostDec $postDec): string
    {
        $nodeId = $postDec->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_PostDec($postDec));
    }

    protected function pExpr_ErrorSuppress(ErrorSuppress $errorSuppress): string
    {
        $nodeId = $errorSuppress->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_ErrorSuppress($errorSuppress)
        );
    }

    protected function pExpr_YieldFrom(YieldFrom $yieldFrom): string
    {
        $nodeId = $yieldFrom->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_YieldFrom($yieldFrom));
    }

    protected function pExpr_Print(Print_ $print): string
    {
        $nodeId = $print->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Print($print));
    }

    protected function pExpr_Cast_Int(Int_ $int): string
    {
        $nodeId = $int->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Cast_Int($int));
    }

    protected function pExpr_Cast_Double(Double $double): string
    {
        $nodeId = $double->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Cast_Double($double));
    }

    protected function pExpr_Cast_String(\PhpParser\Node\Expr\Cast\String_ $string): string
    {
        $nodeId = $string->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Cast_String($string));
    }

    protected function pExpr_Cast_Array(Array_ $array): string
    {
        $nodeId = $array->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Cast_Array($array));
    }

    protected function pExpr_Cast_Object(Object_ $object): string
    {
        $nodeId = $object->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Cast_Object($object));
    }

    protected function pExpr_Cast_Bool(Bool_ $bool): string
    {
        $nodeId = $bool->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Cast_Bool($bool));
    }

    protected function pExpr_Cast_Unset(Unset_ $unset): string
    {
        $nodeId = $unset->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Cast_Unset($unset));
    }

    protected function pExpr_FuncCall(FuncCall $funcCall): string
    {
        $nodeId = $funcCall->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_FuncCall($funcCall));
    }

    protected function pExpr_MethodCall(MethodCall $methodCall): string
    {
        $nodeId = $methodCall->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_MethodCall($methodCall));
    }

    protected function pExpr_NullsafeMethodCall(NullsafeMethodCall $nullsafeMethodCall): string
    {
        $nodeId = $nullsafeMethodCall->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_NullsafeMethodCall($nullsafeMethodCall)
        );
    }

    protected function pExpr_StaticCall(StaticCall $staticCall): string
    {
        $nodeId = $staticCall->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_StaticCall($staticCall));
    }

    protected function pExpr_Empty(Empty_ $empty): string
    {
        $nodeId = $empty->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Empty($empty));
    }

    protected function pExpr_Isset(Isset_ $isset): string
    {
        $nodeId = $isset->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Isset($isset));
    }

    protected function pExpr_Eval(Eval_ $eval): string
    {
        $nodeId = $eval->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Eval($eval));
    }

    protected function pExpr_Include(Include_ $include): string
    {
        $nodeId = $include->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Include($include));
    }

    protected function pExpr_List(List_ $list): string
    {
        $nodeId = $list->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_List($list));
    }

    protected function pExpr_Error(Error $error): string
    {
        $nodeId = $error->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Error($error));
    }

    protected function pExpr_Variable(Variable $variable): string
    {
        $nodeId = $variable->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Variable($variable));
    }

    protected function pExpr_Array(\PhpParser\Node\Expr\Array_ $array): string
    {
        $nodeId = $array->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Array($array));
    }

    protected function pExpr_ArrayItem(ArrayItem $arrayItem): string
    {
        $nodeId = $arrayItem->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_ArrayItem($arrayItem));
    }

    protected function pExpr_ArrayDimFetch(ArrayDimFetch $arrayDimFetch): string
    {
        $nodeId = $arrayDimFetch->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_ArrayDimFetch($arrayDimFetch)
        );
    }

    protected function pExpr_ConstFetch(ConstFetch $constFetch): string
    {
        $nodeId = $constFetch->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_ConstFetch($constFetch));
    }

    protected function pExpr_ClassConstFetch(ClassConstFetch $classConstFetch): string
    {
        $nodeId = $classConstFetch->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_ClassConstFetch($classConstFetch)
        );
    }

    protected function pExpr_PropertyFetch(PropertyFetch $propertyFetch): string
    {
        $nodeId = $propertyFetch->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_PropertyFetch($propertyFetch)
        );
    }

    protected function pExpr_NullsafePropertyFetch(NullsafePropertyFetch $nullsafePropertyFetch): string
    {
        $nodeId = $nullsafePropertyFetch->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_NullsafePropertyFetch($nullsafePropertyFetch)
        );
    }

    protected function pExpr_StaticPropertyFetch(StaticPropertyFetch $staticPropertyFetch): string
    {
        $nodeId = $staticPropertyFetch->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_StaticPropertyFetch($staticPropertyFetch)
        );
    }

    protected function pExpr_ShellExec(ShellExec $shellExec): string
    {
        $nodeId = $shellExec->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_ShellExec($shellExec));
    }

    protected function pExpr_Closure(Closure $node): string
    {
        $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Closure($node));
    }

    protected function pExpr_Match(Match_ $match): string
    {
        $nodeId = $match->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Match($match));
    }

    protected function pMatchArm(MatchArm $matchArm): string
    {
        $nodeId = $matchArm->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pMatchArm($matchArm));
    }

    protected function pExpr_ArrowFunction(ArrowFunction $arrowFunction): string
    {
        $nodeId = $arrowFunction->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pExpr_ArrowFunction($arrowFunction)
        );
    }

    protected function pExpr_ClosureUse(ClosureUse $node): string
    {
        $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_ClosureUse($node));
    }

    protected function pExpr_New(New_ $new): string
    {
        $nodeId = $new->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_New($new));
    }

    protected function pExpr_Clone(Clone_ $clone): string
    {
        $nodeId = $clone->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Clone($clone));
    }

    protected function pExpr_Ternary(Ternary $ternary): string
    {
        $nodeId = $ternary->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Ternary($ternary));
    }

    protected function pExpr_Exit(Exit_ $exit): string
    {
        $nodeId = $exit->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Exit($exit));
    }

    protected function pExpr_Throw(Throw_ $throw): string
    {
        $nodeId = $throw->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Throw($throw));
    }

    protected function pExpr_Yield(Yield_ $yield): string
    {
        $nodeId = $yield->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pExpr_Yield($yield));
    }

    protected function pStmt_Namespace(\PhpParser\Node\Stmt\Namespace_ $namespace): string
    {
        $nodeId = $namespace->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Namespace($namespace));
    }

    protected function pStmt_Use(Use_ $use): string
    {
        $nodeId = $use->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Use($use));
    }

    protected function pStmt_GroupUse(GroupUse $groupUse): string
    {
        $nodeId = $groupUse->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_GroupUse($groupUse));
    }

    protected function pStmt_UseUse(UseUse $useUse): string
    {
        $nodeId = $useUse->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_UseUse($useUse));
    }

    protected function pStmt_Interface(Interface_ $interface): string
    {
        $nodeId = $interface->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Interface($interface));
    }

    protected function pStmt_Enum(Enum_ $enum): string
    {
        $nodeId = $enum->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Enum($enum));
    }

    protected function pStmt_Class(\PhpParser\Node\Stmt\Class_ $class): string
    {
        $nodeId = $class->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Class($class));
    }

    protected function pStmt_Trait(\PhpParser\Node\Stmt\Trait_ $trait): string
    {
        $nodeId = $trait->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Trait($trait));
    }

    protected function pStmt_EnumCase(EnumCase $enumCase): string
    {
        $nodeId = $enumCase->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_EnumCase($enumCase));
    }

    protected function pStmt_TraitUse(TraitUse $traitUse): string
    {
        $nodeId = $traitUse->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_TraitUse($traitUse));
    }

    protected function pStmt_TraitUseAdaptation_Precedence(Precedence $precedence): string
    {
        $nodeId = $precedence->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pStmt_TraitUseAdaptation_Precedence($precedence)
        );
    }

    protected function pStmt_TraitUseAdaptation_Alias(Alias $alias): string
    {
        $nodeId = $alias->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pStmt_TraitUseAdaptation_Alias($alias)
        );
    }

    protected function pStmt_Property(Property $property): string
    {
        $nodeId = $property->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Property($property));
    }

    protected function pStmt_PropertyProperty(PropertyProperty $propertyProperty): string
    {
        $nodeId = $propertyProperty->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pStmt_PropertyProperty($propertyProperty)
        );
    }

    protected function pStmt_ClassMethod(ClassMethod $classMethod): string
    {
        $nodeId = $classMethod->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_ClassMethod($classMethod));
    }

    protected function pStmt_ClassConst(ClassConst $classConst): string
    {
        $nodeId = $classConst->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_ClassConst($classConst));
    }

    protected function pStmt_Function(\PhpParser\Node\Stmt\Function_ $function): string
    {
        $nodeId = $function->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Function($function));
    }

    protected function pStmt_Const(\PhpParser\Node\Stmt\Const_ $const): string
    {
        $nodeId = $const->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Const($const));
    }

    protected function pStmt_Declare(Declare_ $declare): string
    {
        $nodeId = $declare->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Declare($declare));
    }

    protected function pStmt_DeclareDeclare(DeclareDeclare $declareDeclare): string
    {
        $nodeId = $declareDeclare->getAttribute(AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s">%s</a>',
            $this->uuid,
            $nodeId,
            parent::pStmt_DeclareDeclare($declareDeclare)
        );
    }

    protected function pStmt_If(If_ $if): string
    {
        $nodeId = $if->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_If($if));
    }

    protected function pStmt_ElseIf(ElseIf_ $elseIf): string
    {
        $nodeId = $elseIf->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_ElseIf($elseIf));
    }

    protected function pStmt_Else(Else_ $else): string
    {
        $nodeId = $else->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Else($else));
    }

    protected function pStmt_For(For_ $for): string
    {
        $nodeId = $for->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_For($for));
    }

    protected function pStmt_Foreach(Foreach_ $foreach): string
    {
        $nodeId = $foreach->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Foreach($foreach));
    }

    protected function pStmt_While(While_ $while): string
    {
        $nodeId = $while->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_While($while));
    }

    protected function pStmt_Do(Do_ $do): string
    {
        $nodeId = $do->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Do($do));
    }

    protected function pStmt_Switch(Switch_ $switch): string
    {
        $nodeId = $switch->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Switch($switch));
    }

    protected function pStmt_Case(Case_ $case): string
    {
        $nodeId = $case->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Case($case));
    }

    protected function pStmt_TryCatch(TryCatch $tryCatch): string
    {
        $nodeId = $tryCatch->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_TryCatch($tryCatch));
    }

    protected function pStmt_Catch(Catch_ $catch): string
    {
        $nodeId = $catch->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Catch($catch));
    }

    protected function pStmt_Finally(Finally_ $finally): string
    {
        $nodeId = $finally->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Finally($finally));
    }

    protected function pStmt_Break(Break_ $break): string
    {
        $nodeId = $break->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Break($break));
    }

    protected function pStmt_Continue(Continue_ $continue): string
    {
        $nodeId = $continue->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Continue($continue));
    }

    protected function pStmt_Return(Return_ $return): string
    {
        $nodeId = $return->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Return($return));
    }

    protected function pStmt_Throw(\PhpParser\Node\Stmt\Throw_ $throw): string
    {
        $nodeId = $throw->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Throw($throw));
    }

    protected function pStmt_Label(Label $label): string
    {
        $nodeId = $label->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Label($label));
    }

    protected function pStmt_Goto(Goto_ $goto): string
    {
        $nodeId = $goto->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Goto($goto));
    }

    protected function pStmt_Expression(Expression $expression): string
    {
        $nodeId = $expression->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Expression($expression));
    }

    protected function pStmt_Echo(Echo_ $echo): string
    {
        $nodeId = $echo->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Echo($echo));
    }

    protected function pStmt_Static(Static_ $static): string
    {
        $nodeId = $static->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Static($static));
    }

    protected function pStmt_Global(Global_ $global): string
    {
        $nodeId = $global->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Global($global));
    }

    protected function pStmt_StaticVar(StaticVar $staticVar): string
    {
        $nodeId = $staticVar->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_StaticVar($staticVar));
    }

    protected function pStmt_Unset(\PhpParser\Node\Stmt\Unset_ $unset): string
    {
        $nodeId = $unset->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Unset($unset));
    }

    protected function pStmt_InlineHTML(InlineHTML $inlineHTML): string
    {
        $nodeId = $inlineHTML->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_InlineHTML($inlineHTML));
    }

    protected function pStmt_HaltCompiler(HaltCompiler $haltCompiler): string
    {
        $nodeId = $haltCompiler->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_HaltCompiler($haltCompiler));
    }

    protected function pStmt_Nop(Nop $nop): string
    {
        $nodeId = $nop->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStmt_Nop($nop));
    }

    protected function pDereferenceLhs(Node $node): string
    {
        $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pDereferenceLhs($node));
    }

    protected function pStaticDereferenceLhs(Node $node): string
    {
        $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pStaticDereferenceLhs($node));
    }

    protected function pCallLhs(Node $node): string
    {
        $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pCallLhs($node));
    }

    protected function pNewVariable(Node $node): string
    {
        $nodeId = $node->getAttribute(AttributeKey::NODE_ID);
        return sprintf('<a href="/ast/%s/%s">%s</a>', $this->uuid, $nodeId, parent::pNewVariable($node));
    }
}
