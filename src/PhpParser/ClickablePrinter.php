<?php

declare(strict_types=1);

namespace Rector\Website\PhpParser;

use PhpParser\Node\Expr\Assign;

final class ClickablePrinter extends \PhpParser\PrettyPrinter\Standard
{
    public function __construct(
        private string $uuid,
        private ?int $activeNodeId
    ) {
        parent::__construct();
    }

    protected function pParam(\PhpParser\Node\Param $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pParam($node)
        );
    }

    protected function pArg(\PhpParser\Node\Arg $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pArg($node)
        );
    }

    protected function pVariadicPlaceholder(\PhpParser\Node\VariadicPlaceholder $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pVariadicPlaceholder($node)
        );
    }

    protected function pConst(\PhpParser\Node\Const_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pConst($node)
        );
    }

    protected function pNullableType(\PhpParser\Node\NullableType $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pNullableType($node)
        );
    }

    protected function pUnionType(\PhpParser\Node\UnionType $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pUnionType($node)
        );
    }

    protected function pIntersectionType(\PhpParser\Node\IntersectionType $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pIntersectionType($node)
        );
    }

    protected function pIdentifier(\PhpParser\Node\Identifier $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pIdentifier($node)
        );
    }

    protected function pVarLikeIdentifier(\PhpParser\Node\VarLikeIdentifier $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pVarLikeIdentifier($node)
        );
    }

    protected function pAttribute(\PhpParser\Node\Attribute $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pAttribute($node)
        );
    }

    protected function pAttributeGroup(\PhpParser\Node\AttributeGroup $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pAttributeGroup($node)
        );
    }

    protected function pName(\PhpParser\Node\Name $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pName($node)
        );
    }

    protected function pName_FullyQualified(\PhpParser\Node\Name\FullyQualified $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pName_FullyQualified($node)
        );
    }

    protected function pName_Relative(\PhpParser\Node\Name\Relative $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pName_Relative($node)
        );
    }

    protected function pScalar_MagicConst_Class(\PhpParser\Node\Scalar\MagicConst\Class_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_MagicConst_Class($node)
        );
    }

    protected function pScalar_MagicConst_Dir(\PhpParser\Node\Scalar\MagicConst\Dir $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_MagicConst_Dir($node)
        );
    }

    protected function pScalar_MagicConst_File(\PhpParser\Node\Scalar\MagicConst\File $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_MagicConst_File($node)
        );
    }

    protected function pScalar_MagicConst_Function(\PhpParser\Node\Scalar\MagicConst\Function_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_MagicConst_Function($node)
        );
    }

    protected function pScalar_MagicConst_Line(\PhpParser\Node\Scalar\MagicConst\Line $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_MagicConst_Line($node)
        );
    }

    protected function pScalar_MagicConst_Method(\PhpParser\Node\Scalar\MagicConst\Method $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_MagicConst_Method($node)
        );
    }

    protected function pScalar_MagicConst_Namespace(\PhpParser\Node\Scalar\MagicConst\Namespace_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_MagicConst_Namespace($node)
        );
    }

    protected function pScalar_MagicConst_Trait(\PhpParser\Node\Scalar\MagicConst\Trait_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_MagicConst_Trait($node)
        );
    }

    protected function pScalar_String(\PhpParser\Node\Scalar\String_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_String($node)
        );
    }

    protected function pScalar_Encapsed(\PhpParser\Node\Scalar\Encapsed $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_Encapsed($node)
        );
    }

    protected function pScalar_LNumber(\PhpParser\Node\Scalar\LNumber $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_LNumber($node)
        );
    }

    protected function pScalar_DNumber(\PhpParser\Node\Scalar\DNumber $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_DNumber($node)
        );
    }

    protected function pScalar_EncapsedStringPart(\PhpParser\Node\Scalar\EncapsedStringPart $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pScalar_EncapsedStringPart($node)
        );
    }

    protected function pExpr_Assign(\PhpParser\Node\Expr\Assign $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        $link = PrinterHelper::printLink(' = ', $this->uuid, $nodeId, $this->activeNodeId);

        return $this->pInfixOp(Assign::class, $node->var, $link, $node->expr);
    }

    protected function pExpr_AssignRef(\PhpParser\Node\Expr\AssignRef $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignRef($node)
        );
    }

    protected function pExpr_AssignOp_Plus(\PhpParser\Node\Expr\AssignOp\Plus $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_Plus($node)
        );
    }

    protected function pExpr_AssignOp_Minus(\PhpParser\Node\Expr\AssignOp\Minus $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_Minus($node)
        );
    }

    protected function pExpr_AssignOp_Mul(\PhpParser\Node\Expr\AssignOp\Mul $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_Mul($node)
        );
    }

    protected function pExpr_AssignOp_Div(\PhpParser\Node\Expr\AssignOp\Div $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_Div($node)
        );
    }

    protected function pExpr_AssignOp_Concat(\PhpParser\Node\Expr\AssignOp\Concat $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_Concat($node)
        );
    }

    protected function pExpr_AssignOp_Mod(\PhpParser\Node\Expr\AssignOp\Mod $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_Mod($node)
        );
    }

    protected function pExpr_AssignOp_BitwiseAnd(\PhpParser\Node\Expr\AssignOp\BitwiseAnd $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_BitwiseAnd($node)
        );
    }

    protected function pExpr_AssignOp_BitwiseOr(\PhpParser\Node\Expr\AssignOp\BitwiseOr $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_BitwiseOr($node)
        );
    }

    protected function pExpr_AssignOp_BitwiseXor(\PhpParser\Node\Expr\AssignOp\BitwiseXor $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_BitwiseXor($node)
        );
    }

    protected function pExpr_AssignOp_ShiftLeft(\PhpParser\Node\Expr\AssignOp\ShiftLeft $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_ShiftLeft($node)
        );
    }

    protected function pExpr_AssignOp_ShiftRight(\PhpParser\Node\Expr\AssignOp\ShiftRight $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_ShiftRight($node)
        );
    }

    protected function pExpr_AssignOp_Pow(\PhpParser\Node\Expr\AssignOp\Pow $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_Pow($node)
        );
    }

    protected function pExpr_AssignOp_Coalesce(\PhpParser\Node\Expr\AssignOp\Coalesce $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_AssignOp_Coalesce($node)
        );
    }

    protected function pExpr_BinaryOp_Plus(\PhpParser\Node\Expr\BinaryOp\Plus $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_Plus($node)
        );
    }

    protected function pExpr_BinaryOp_Minus(\PhpParser\Node\Expr\BinaryOp\Minus $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_Minus($node)
        );
    }

    protected function pExpr_BinaryOp_Mul(\PhpParser\Node\Expr\BinaryOp\Mul $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_Mul($node)
        );
    }

    protected function pExpr_BinaryOp_Div(\PhpParser\Node\Expr\BinaryOp\Div $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_Div($node)
        );
    }

    protected function pExpr_BinaryOp_Concat(\PhpParser\Node\Expr\BinaryOp\Concat $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_Concat($node)
        );
    }

    protected function pExpr_BinaryOp_Mod(\PhpParser\Node\Expr\BinaryOp\Mod $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_Mod($node)
        );
    }

    protected function pExpr_BinaryOp_BooleanAnd(\PhpParser\Node\Expr\BinaryOp\BooleanAnd $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_BooleanAnd($node)
        );
    }

    protected function pExpr_BinaryOp_BooleanOr(\PhpParser\Node\Expr\BinaryOp\BooleanOr $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_BooleanOr($node)
        );
    }

    protected function pExpr_BinaryOp_BitwiseAnd(\PhpParser\Node\Expr\BinaryOp\BitwiseAnd $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_BitwiseAnd($node)
        );
    }

    protected function pExpr_BinaryOp_BitwiseOr(\PhpParser\Node\Expr\BinaryOp\BitwiseOr $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_BitwiseOr($node)
        );
    }

    protected function pExpr_BinaryOp_BitwiseXor(\PhpParser\Node\Expr\BinaryOp\BitwiseXor $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_BitwiseXor($node)
        );
    }

    protected function pExpr_BinaryOp_ShiftLeft(\PhpParser\Node\Expr\BinaryOp\ShiftLeft $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_ShiftLeft($node)
        );
    }

    protected function pExpr_BinaryOp_ShiftRight(\PhpParser\Node\Expr\BinaryOp\ShiftRight $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_ShiftRight($node)
        );
    }

    protected function pExpr_BinaryOp_Pow(\PhpParser\Node\Expr\BinaryOp\Pow $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_Pow($node)
        );
    }

    protected function pExpr_BinaryOp_LogicalAnd(\PhpParser\Node\Expr\BinaryOp\LogicalAnd $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_LogicalAnd($node)
        );
    }

    protected function pExpr_BinaryOp_LogicalOr(\PhpParser\Node\Expr\BinaryOp\LogicalOr $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BinaryOp_LogicalOr($node)
        );
    }

    protected function pExpr_BinaryOp_LogicalXor(\PhpParser\Node\Expr\BinaryOp\LogicalXor $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\LogicalXor::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_Equal(\PhpParser\Node\Expr\BinaryOp\Equal $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\Equal::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_NotEqual(\PhpParser\Node\Expr\BinaryOp\NotEqual $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\NotEqual::class, $node->left, $link, $node->right);
    }

    /**
     * @override
     */
    protected function pExpr_BinaryOp_Identical(\PhpParser\Node\Expr\BinaryOp\Identical $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\Identical::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_NotIdentical(\PhpParser\Node\Expr\BinaryOp\NotIdentical $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\NotIdentical::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_Spaceship(\PhpParser\Node\Expr\BinaryOp\Spaceship $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\Spaceship::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_Greater(\PhpParser\Node\Expr\BinaryOp\Greater $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\Greater::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_GreaterOrEqual(\PhpParser\Node\Expr\BinaryOp\GreaterOrEqual $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\GreaterOrEqual::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_Smaller(\PhpParser\Node\Expr\BinaryOp\Smaller $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\Smaller::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_SmallerOrEqual(\PhpParser\Node\Expr\BinaryOp\SmallerOrEqual $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\SmallerOrEqual::class, $node->left, $link, $node->right);
    }

    protected function pExpr_BinaryOp_Coalesce(\PhpParser\Node\Expr\BinaryOp\Coalesce $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = PrinterHelper::printLink(
            ' ' . $node->getOperatorSigil() . '  ',
            $this->uuid,
            $nodeId,
            $this->activeNodeId
        );

        return $this->pInfixOp(\PhpParser\Node\Expr\BinaryOp\Coalesce::class, $node->left, $link, $node->right);
    }

    protected function pExpr_Instanceof(\PhpParser\Node\Expr\Instanceof_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Instanceof($node)
        );
    }

    protected function pExpr_BooleanNot(\PhpParser\Node\Expr\BooleanNot $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BooleanNot($node)
        );
    }

    protected function pExpr_BitwiseNot(\PhpParser\Node\Expr\BitwiseNot $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_BitwiseNot($node)
        );
    }

    protected function pExpr_UnaryMinus(\PhpParser\Node\Expr\UnaryMinus $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_UnaryMinus($node)
        );
    }

    protected function pExpr_UnaryPlus(\PhpParser\Node\Expr\UnaryPlus $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_UnaryPlus($node)
        );
    }

    protected function pExpr_PreInc(\PhpParser\Node\Expr\PreInc $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_PreInc($node)
        );
    }

    protected function pExpr_PreDec(\PhpParser\Node\Expr\PreDec $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_PreDec($node)
        );
    }

    protected function pExpr_PostInc(\PhpParser\Node\Expr\PostInc $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_PostInc($node)
        );
    }

    protected function pExpr_PostDec(\PhpParser\Node\Expr\PostDec $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_PostDec($node)
        );
    }

    protected function pExpr_ErrorSuppress(\PhpParser\Node\Expr\ErrorSuppress $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_ErrorSuppress($node)
        );
    }

    protected function pExpr_YieldFrom(\PhpParser\Node\Expr\YieldFrom $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_YieldFrom($node)
        );
    }

    protected function pExpr_Print(\PhpParser\Node\Expr\Print_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Print($node)
        );
    }

    protected function pExpr_Cast_Int(\PhpParser\Node\Expr\Cast\Int_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Cast_Int($node)
        );
    }

    protected function pExpr_Cast_Double(\PhpParser\Node\Expr\Cast\Double $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Cast_Double($node)
        );
    }

    protected function pExpr_Cast_String(\PhpParser\Node\Expr\Cast\String_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Cast_String($node)
        );
    }

    protected function pExpr_Cast_Array(\PhpParser\Node\Expr\Cast\Array_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Cast_Array($node)
        );
    }

    protected function pExpr_Cast_Object(\PhpParser\Node\Expr\Cast\Object_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Cast_Object($node)
        );
    }

    protected function pExpr_Cast_Bool(\PhpParser\Node\Expr\Cast\Bool_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Cast_Bool($node)
        );
    }

    protected function pExpr_Cast_Unset(\PhpParser\Node\Expr\Cast\Unset_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Cast_Unset($node)
        );
    }

    protected function pExpr_FuncCall(\PhpParser\Node\Expr\FuncCall $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_FuncCall($node)
        );
    }

    /**
     * @overloaded
     */
    protected function pExpr_MethodCall(\PhpParser\Node\Expr\MethodCall $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = sprintf(
            '<a href="/ast/%s/%s" %s>-></a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
        );

        return $this->pDereferenceLhs($node->var) . $link . $this->pObjectProperty($node->name)
            . '(' . $this->pMaybeMultiline($node->args) . ')';
    }

    protected function pExpr_NullsafeMethodCall(\PhpParser\Node\Expr\NullsafeMethodCall $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_NullsafeMethodCall($node)
        );
    }

    protected function pExpr_StaticCall(\PhpParser\Node\Expr\StaticCall $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_StaticCall($node)
        );
    }

    protected function pExpr_Empty(\PhpParser\Node\Expr\Empty_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Empty($node)
        );
    }

    protected function pExpr_Isset(\PhpParser\Node\Expr\Isset_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Isset($node)
        );
    }

    protected function pExpr_Eval(\PhpParser\Node\Expr\Eval_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Eval($node)
        );
    }

    protected function pExpr_Include(\PhpParser\Node\Expr\Include_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Include($node)
        );
    }

    protected function pExpr_List(\PhpParser\Node\Expr\List_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_List($node)
        );
    }

    protected function pExpr_Error(\PhpParser\Node\Expr\Error $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Error($node)
        );
    }

    protected function pExpr_Variable(\PhpParser\Node\Expr\Variable $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Variable($node)
        );
    }

    protected function pExpr_Array(\PhpParser\Node\Expr\Array_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Array($node)
        );
    }

    protected function pExpr_ArrayItem(\PhpParser\Node\Expr\ArrayItem $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_ArrayItem($node)
        );
    }

    protected function pExpr_ArrayDimFetch(\PhpParser\Node\Expr\ArrayDimFetch $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_ArrayDimFetch($node)
        );
    }

    protected function pExpr_ConstFetch(\PhpParser\Node\Expr\ConstFetch $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_ConstFetch($node)
        );
    }

    protected function pExpr_ClassConstFetch(\PhpParser\Node\Expr\ClassConstFetch $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);

        $link = sprintf(
            '<a href="/ast/%s/%s" %s>::</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
        );

        return $this->pStaticDereferenceLhs($node->class) . $link . $this->pObjectProperty($node->name);
    }

    protected function pExpr_PropertyFetch(\PhpParser\Node\Expr\PropertyFetch $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        $link = PrinterHelper::printLink('->', $this->uuid, $nodeId, $this->activeNodeId);

        return $this->pDereferenceLhs($node->var) . $link . $this->pObjectProperty($node->name);
    }

    protected function pExpr_NullsafePropertyFetch(\PhpParser\Node\Expr\NullsafePropertyFetch $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_NullsafePropertyFetch($node)
        );
    }

    protected function pExpr_StaticPropertyFetch(\PhpParser\Node\Expr\StaticPropertyFetch $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_StaticPropertyFetch($node)
        );
    }

    protected function pExpr_ShellExec(\PhpParser\Node\Expr\ShellExec $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_ShellExec($node)
        );
    }

    protected function pExpr_Closure(\PhpParser\Node\Expr\Closure $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Closure($node)
        );
    }

    protected function pExpr_Match(\PhpParser\Node\Expr\Match_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Match($node)
        );
    }

    protected function pMatchArm(\PhpParser\Node\MatchArm $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pMatchArm($node)
        );
    }

    protected function pExpr_ArrowFunction(\PhpParser\Node\Expr\ArrowFunction $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_ArrowFunction($node)
        );
    }

    protected function pExpr_ClosureUse(\PhpParser\Node\Expr\ClosureUse $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_ClosureUse($node)
        );
    }

    protected function pExpr_New(\PhpParser\Node\Expr\New_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_New($node)
        );
    }

    protected function pExpr_Clone(\PhpParser\Node\Expr\Clone_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Clone($node)
        );
    }

    protected function pExpr_Ternary(\PhpParser\Node\Expr\Ternary $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Ternary($node)
        );
    }

    protected function pExpr_Exit(\PhpParser\Node\Expr\Exit_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Exit($node)
        );
    }

    protected function pExpr_Throw(\PhpParser\Node\Expr\Throw_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Throw($node)
        );
    }

    protected function pExpr_Yield(\PhpParser\Node\Expr\Yield_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pExpr_Yield($node)
        );
    }

    protected function pStmt_Namespace(\PhpParser\Node\Stmt\Namespace_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Namespace($node)
        );
    }

    protected function pStmt_Use(\PhpParser\Node\Stmt\Use_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Use($node)
        );
    }

    protected function pStmt_GroupUse(\PhpParser\Node\Stmt\GroupUse $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_GroupUse($node)
        );
    }

    protected function pStmt_UseUse(\PhpParser\Node\Stmt\UseUse $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_UseUse($node)
        );
    }

    protected function pStmt_Interface(\PhpParser\Node\Stmt\Interface_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Interface($node)
        );
    }

    protected function pStmt_Enum(\PhpParser\Node\Stmt\Enum_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Enum($node)
        );
    }

    protected function pStmt_Class(\PhpParser\Node\Stmt\Class_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Class($node)
        );
    }

    protected function pStmt_Trait(\PhpParser\Node\Stmt\Trait_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Trait($node)
        );
    }

    protected function pStmt_EnumCase(\PhpParser\Node\Stmt\EnumCase $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_EnumCase($node)
        );
    }

    protected function pStmt_TraitUse(\PhpParser\Node\Stmt\TraitUse $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_TraitUse($node)
        );
    }

    protected function pStmt_TraitUseAdaptation_Precedence(
        \PhpParser\Node\Stmt\TraitUseAdaptation\Precedence $node
    ): string {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_TraitUseAdaptation_Precedence($node)
        );
    }

    protected function pStmt_TraitUseAdaptation_Alias(\PhpParser\Node\Stmt\TraitUseAdaptation\Alias $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_TraitUseAdaptation_Alias($node)
        );
    }

    protected function pStmt_Property(\PhpParser\Node\Stmt\Property $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Property($node)
        );
    }

    protected function pStmt_PropertyProperty(\PhpParser\Node\Stmt\PropertyProperty $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_PropertyProperty($node)
        );
    }

    protected function pStmt_ClassMethod(\PhpParser\Node\Stmt\ClassMethod $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_ClassMethod($node)
        );
    }

    protected function pStmt_ClassConst(\PhpParser\Node\Stmt\ClassConst $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_ClassConst($node)
        );
    }

    protected function pStmt_Function(\PhpParser\Node\Stmt\Function_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Function($node)
        );
    }

    protected function pStmt_Const(\PhpParser\Node\Stmt\Const_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Const($node)
        );
    }

    protected function pStmt_Declare(\PhpParser\Node\Stmt\Declare_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Declare($node)
        );
    }

    protected function pStmt_DeclareDeclare(\PhpParser\Node\Stmt\DeclareDeclare $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_DeclareDeclare($node)
        );
    }

    protected function pStmt_If(\PhpParser\Node\Stmt\If_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_If($node)
        );
    }

    protected function pStmt_ElseIf(\PhpParser\Node\Stmt\ElseIf_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_ElseIf($node)
        );
    }

    protected function pStmt_Else(\PhpParser\Node\Stmt\Else_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Else($node)
        );
    }

    protected function pStmt_For(\PhpParser\Node\Stmt\For_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_For($node)
        );
    }

    protected function pStmt_Foreach(\PhpParser\Node\Stmt\Foreach_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Foreach($node)
        );
    }

    protected function pStmt_While(\PhpParser\Node\Stmt\While_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_While($node)
        );
    }

    protected function pStmt_Do(\PhpParser\Node\Stmt\Do_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Do($node)
        );
    }

    protected function pStmt_Switch(\PhpParser\Node\Stmt\Switch_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Switch($node)
        );
    }

    protected function pStmt_Case(\PhpParser\Node\Stmt\Case_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Case($node)
        );
    }

    protected function pStmt_TryCatch(\PhpParser\Node\Stmt\TryCatch $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_TryCatch($node)
        );
    }

    protected function pStmt_Catch(\PhpParser\Node\Stmt\Catch_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Catch($node)
        );
    }

    protected function pStmt_Finally(\PhpParser\Node\Stmt\Finally_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Finally($node)
        );
    }

    protected function pStmt_Break(\PhpParser\Node\Stmt\Break_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Break($node)
        );
    }

    protected function pStmt_Continue(\PhpParser\Node\Stmt\Continue_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Continue($node)
        );
    }

    protected function pStmt_Return(\PhpParser\Node\Stmt\Return_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Return($node)
        );
    }

    protected function pStmt_Throw(\PhpParser\Node\Stmt\Throw_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Throw($node)
        );
    }

    protected function pStmt_Label(\PhpParser\Node\Stmt\Label $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Label($node)
        );
    }

    protected function pStmt_Goto(\PhpParser\Node\Stmt\Goto_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Goto($node)
        );
    }

    protected function pStmt_Expression(\PhpParser\Node\Stmt\Expression $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Expression($node)
        );
    }

    protected function pStmt_Echo(\PhpParser\Node\Stmt\Echo_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Echo($node)
        );
    }

    protected function pStmt_Static(\PhpParser\Node\Stmt\Static_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Static($node)
        );
    }

    protected function pStmt_Global(\PhpParser\Node\Stmt\Global_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Global($node)
        );
    }

    protected function pStmt_StaticVar(\PhpParser\Node\Stmt\StaticVar $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_StaticVar($node)
        );
    }

    protected function pStmt_Unset(\PhpParser\Node\Stmt\Unset_ $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Unset($node)
        );
    }

    protected function pStmt_InlineHTML(\PhpParser\Node\Stmt\InlineHTML $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_InlineHTML($node)
        );
    }

    protected function pStmt_HaltCompiler(\PhpParser\Node\Stmt\HaltCompiler $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_HaltCompiler($node)
        );
    }

    protected function pStmt_Nop(\PhpParser\Node\Stmt\Nop $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pStmt_Nop($node)
        );
    }

    protected function pObjectProperty($node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pObjectProperty($node)
        );
    }

    protected function pDereferenceLhs(\PhpParser\Node $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pDereferenceLhs($node)
        );
    }

    protected function pCallLhs(\PhpParser\Node $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pCallLhs($node)
        );
    }

    protected function pNewVariable(\PhpParser\Node $node): string
    {
        $nodeId = $node->getAttribute(\Rector\Website\Enum\AttributeKey::NODE_ID);
        return sprintf(
            '<a href="/ast/%s/%s" %s>%s</a>',
            $this->uuid,
            $nodeId,
            $this->activeNodeId == $nodeId ? 'class="active-node"' : '',
            parent::pNewVariable($node)
        );
    }
}
