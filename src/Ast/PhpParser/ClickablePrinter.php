<?php

declare(strict_types=1);

namespace App\Ast\PhpParser;

final class ClickablePrinter extends \PhpParser\PrettyPrinter\Standard
{
    public function __construct(
        private ?int $activeNodeId
    ) {
        parent::__construct();
    }

    protected function pParam(\PhpParser\Node\Param $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pParam($node));
    }

    protected function pArg(\PhpParser\Node\Arg $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pArg($node));
    }

    protected function pVariadicPlaceholder(\PhpParser\Node\VariadicPlaceholder $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pVariadicPlaceholder(
            $node
        ));
    }

    protected function pConst(\PhpParser\Node\Const_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pConst($node));
    }

    protected function pNullableType(\PhpParser\Node\NullableType $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pNullableType(
            $node
        ));
    }

    protected function pUnionType(\PhpParser\Node\UnionType $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pUnionType(
            $node
        ));
    }

    protected function pIntersectionType(\PhpParser\Node\IntersectionType $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pIntersectionType(
            $node
        ));
    }

    protected function pIdentifier(\PhpParser\Node\Identifier $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pIdentifier(
            $node
        ));
    }

    protected function pVarLikeIdentifier(\PhpParser\Node\VarLikeIdentifier $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pVarLikeIdentifier(
            $node
        ));
    }

    protected function pAttribute(\PhpParser\Node\Attribute $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pAttribute(
            $node
        ));
    }

    protected function pAttributeGroup(\PhpParser\Node\AttributeGroup $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pAttributeGroup(
            $node
        ));
    }

    protected function pName(\PhpParser\Node\Name $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pName($node));
    }

    protected function pName_FullyQualified(\PhpParser\Node\Name\FullyQualified $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pName_FullyQualified(
            $node
        ));
    }

    protected function pName_Relative(\PhpParser\Node\Name\Relative $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pName_Relative(
            $node
        ));
    }

    protected function pScalar_MagicConst_Class(\PhpParser\Node\Scalar\MagicConst\Class_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_Class(
            $node
        ));
    }

    protected function pScalar_MagicConst_Dir(\PhpParser\Node\Scalar\MagicConst\Dir $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_Dir(
            $node
        ));
    }

    protected function pScalar_MagicConst_File(\PhpParser\Node\Scalar\MagicConst\File $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_File(
            $node
        ));
    }

    protected function pScalar_MagicConst_Function(\PhpParser\Node\Scalar\MagicConst\Function_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_Function(
            $node
        ));
    }

    protected function pScalar_MagicConst_Line(\PhpParser\Node\Scalar\MagicConst\Line $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_Line(
            $node
        ));
    }

    protected function pScalar_MagicConst_Method(\PhpParser\Node\Scalar\MagicConst\Method $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_Method(
            $node
        ));
    }

    protected function pScalar_MagicConst_Namespace(\PhpParser\Node\Scalar\MagicConst\Namespace_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_Namespace(
            $node
        ));
    }

    protected function pScalar_MagicConst_Trait(\PhpParser\Node\Scalar\MagicConst\Trait_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_Trait(
            $node
        ));
    }

    protected function pScalar_MagicConst_Property(\PhpParser\Node\Scalar\MagicConst\Property $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_MagicConst_Property(
            $node
        ));
    }

    protected function pScalar_String(\PhpParser\Node\Scalar\String_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_String(
            $node
        ));
    }

    protected function pScalar_InterpolatedString(\PhpParser\Node\Scalar\InterpolatedString $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_InterpolatedString(
            $node
        ));
    }

    protected function pScalar_Int(\PhpParser\Node\Scalar\Int_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_Int(
            $node
        ));
    }

    protected function pScalar_Float(\PhpParser\Node\Scalar\Float_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pScalar_Float(
            $node
        ));
    }

    protected function pExpr_PreInc(\PhpParser\Node\Expr\PreInc $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_PreInc(
            $node
        ));
    }

    protected function pExpr_PreDec(\PhpParser\Node\Expr\PreDec $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_PreDec(
            $node
        ));
    }

    protected function pExpr_PostInc(\PhpParser\Node\Expr\PostInc $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_PostInc(
            $node
        ));
    }

    protected function pExpr_PostDec(\PhpParser\Node\Expr\PostDec $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_PostDec(
            $node
        ));
    }

    protected function pExpr_FuncCall(\PhpParser\Node\Expr\FuncCall $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_FuncCall(
            $node
        ));
    }

    protected function pExpr_MethodCall(\PhpParser\Node\Expr\MethodCall $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_MethodCall(
            $node
        ));
    }

    protected function pExpr_NullsafeMethodCall(\PhpParser\Node\Expr\NullsafeMethodCall $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_NullsafeMethodCall(
            $node
        ));
    }

    protected function pExpr_StaticCall(\PhpParser\Node\Expr\StaticCall $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_StaticCall(
            $node
        ));
    }

    protected function pExpr_Empty(\PhpParser\Node\Expr\Empty_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Empty(
            $node
        ));
    }

    protected function pExpr_Isset(\PhpParser\Node\Expr\Isset_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Isset(
            $node
        ));
    }

    protected function pExpr_Eval(\PhpParser\Node\Expr\Eval_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Eval(
            $node
        ));
    }

    protected function pExpr_List(\PhpParser\Node\Expr\List_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_List(
            $node
        ));
    }

    protected function pExpr_Error(\PhpParser\Node\Expr\Error $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Error(
            $node
        ));
    }

    protected function pExpr_Variable(\PhpParser\Node\Expr\Variable $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Variable(
            $node
        ));
    }

    protected function pExpr_Array(\PhpParser\Node\Expr\Array_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Array(
            $node
        ));
    }

    protected function pArrayItem(\PhpParser\Node\ArrayItem $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pArrayItem(
            $node
        ));
    }

    protected function pExpr_ArrayDimFetch(\PhpParser\Node\Expr\ArrayDimFetch $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_ArrayDimFetch(
            $node
        ));
    }

    protected function pExpr_ConstFetch(\PhpParser\Node\Expr\ConstFetch $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_ConstFetch(
            $node
        ));
    }

    protected function pExpr_ClassConstFetch(\PhpParser\Node\Expr\ClassConstFetch $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_ClassConstFetch(
            $node
        ));
    }

    protected function pExpr_PropertyFetch(\PhpParser\Node\Expr\PropertyFetch $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_PropertyFetch(
            $node
        ));
    }

    protected function pExpr_NullsafePropertyFetch(\PhpParser\Node\Expr\NullsafePropertyFetch $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_NullsafePropertyFetch(
            $node
        ));
    }

    protected function pExpr_StaticPropertyFetch(\PhpParser\Node\Expr\StaticPropertyFetch $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_StaticPropertyFetch(
            $node
        ));
    }

    protected function pExpr_ShellExec(\PhpParser\Node\Expr\ShellExec $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_ShellExec(
            $node
        ));
    }

    protected function pExpr_Closure(\PhpParser\Node\Expr\Closure $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Closure(
            $node
        ));
    }

    protected function pExpr_Match(\PhpParser\Node\Expr\Match_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Match(
            $node
        ));
    }

    protected function pMatchArm(\PhpParser\Node\MatchArm $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pMatchArm(
            $node
        ));
    }

    protected function pClosureUse(\PhpParser\Node\ClosureUse $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pClosureUse(
            $node
        ));
    }

    protected function pExpr_New(\PhpParser\Node\Expr\New_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_New(
            $node
        ));
    }

    protected function pExpr_Exit(\PhpParser\Node\Expr\Exit_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pExpr_Exit(
            $node
        ));
    }

    protected function pStmt_Namespace(\PhpParser\Node\Stmt\Namespace_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Namespace(
            $node
        ));
    }

    protected function pStmt_Use(\PhpParser\Node\Stmt\Use_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Use(
            $node
        ));
    }

    protected function pStmt_GroupUse(\PhpParser\Node\Stmt\GroupUse $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_GroupUse(
            $node
        ));
    }

    protected function pUseItem(\PhpParser\Node\UseItem $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pUseItem($node));
    }

    protected function pStmt_Interface(\PhpParser\Node\Stmt\Interface_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Interface(
            $node
        ));
    }

    protected function pStmt_Enum(\PhpParser\Node\Stmt\Enum_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Enum(
            $node
        ));
    }

    protected function pStmt_Class(\PhpParser\Node\Stmt\Class_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Class(
            $node
        ));
    }

    protected function pStmt_Trait(\PhpParser\Node\Stmt\Trait_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Trait(
            $node
        ));
    }

    protected function pStmt_EnumCase(\PhpParser\Node\Stmt\EnumCase $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_EnumCase(
            $node
        ));
    }

    protected function pStmt_TraitUse(\PhpParser\Node\Stmt\TraitUse $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_TraitUse(
            $node
        ));
    }

    protected function pStmt_TraitUseAdaptation_Precedence(
        \PhpParser\Node\Stmt\TraitUseAdaptation\Precedence $node
    ): string {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_TraitUseAdaptation_Precedence(
            $node
        ));
    }

    protected function pStmt_TraitUseAdaptation_Alias(\PhpParser\Node\Stmt\TraitUseAdaptation\Alias $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_TraitUseAdaptation_Alias(
            $node
        ));
    }

    protected function pStmt_Property(\PhpParser\Node\Stmt\Property $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Property(
            $node
        ));
    }

    protected function pPropertyItem(\PhpParser\Node\PropertyItem $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pPropertyItem(
            $node
        ));
    }

    protected function pPropertyHook(\PhpParser\Node\PropertyHook $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pPropertyHook(
            $node
        ));
    }

    protected function pStmt_ClassMethod(\PhpParser\Node\Stmt\ClassMethod $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_ClassMethod(
            $node
        ));
    }

    protected function pStmt_ClassConst(\PhpParser\Node\Stmt\ClassConst $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_ClassConst(
            $node
        ));
    }

    protected function pStmt_Function(\PhpParser\Node\Stmt\Function_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Function(
            $node
        ));
    }

    protected function pStmt_Const(\PhpParser\Node\Stmt\Const_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Const(
            $node
        ));
    }

    protected function pStmt_Declare(\PhpParser\Node\Stmt\Declare_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Declare(
            $node
        ));
    }

    protected function pDeclareItem(\PhpParser\Node\DeclareItem $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pDeclareItem(
            $node
        ));
    }

    protected function pStmt_If(\PhpParser\Node\Stmt\If_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_If($node));
    }

    protected function pStmt_ElseIf(\PhpParser\Node\Stmt\ElseIf_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_ElseIf(
            $node
        ));
    }

    protected function pStmt_Else(\PhpParser\Node\Stmt\Else_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Else(
            $node
        ));
    }

    protected function pStmt_For(\PhpParser\Node\Stmt\For_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_For(
            $node
        ));
    }

    protected function pStmt_Foreach(\PhpParser\Node\Stmt\Foreach_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Foreach(
            $node
        ));
    }

    protected function pStmt_While(\PhpParser\Node\Stmt\While_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_While(
            $node
        ));
    }

    protected function pStmt_Do(\PhpParser\Node\Stmt\Do_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Do($node));
    }

    protected function pStmt_Switch(\PhpParser\Node\Stmt\Switch_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Switch(
            $node
        ));
    }

    protected function pStmt_Case(\PhpParser\Node\Stmt\Case_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Case(
            $node
        ));
    }

    protected function pStmt_TryCatch(\PhpParser\Node\Stmt\TryCatch $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_TryCatch(
            $node
        ));
    }

    protected function pStmt_Catch(\PhpParser\Node\Stmt\Catch_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Catch(
            $node
        ));
    }

    protected function pStmt_Finally(\PhpParser\Node\Stmt\Finally_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Finally(
            $node
        ));
    }

    protected function pStmt_Break(\PhpParser\Node\Stmt\Break_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Break(
            $node
        ));
    }

    protected function pStmt_Continue(\PhpParser\Node\Stmt\Continue_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Continue(
            $node
        ));
    }

    protected function pStmt_Return(\PhpParser\Node\Stmt\Return_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Return(
            $node
        ));
    }

    protected function pStmt_Label(\PhpParser\Node\Stmt\Label $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Label(
            $node
        ));
    }

    protected function pStmt_Goto(\PhpParser\Node\Stmt\Goto_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Goto(
            $node
        ));
    }

    protected function pStmt_Expression(\PhpParser\Node\Stmt\Expression $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Expression(
            $node
        ));
    }

    protected function pStmt_Echo(\PhpParser\Node\Stmt\Echo_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Echo(
            $node
        ));
    }

    protected function pStmt_Static(\PhpParser\Node\Stmt\Static_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Static(
            $node
        ));
    }

    protected function pStmt_Global(\PhpParser\Node\Stmt\Global_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Global(
            $node
        ));
    }

    protected function pStaticVar(\PhpParser\Node\StaticVar $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStaticVar(
            $node
        ));
    }

    protected function pStmt_Unset(\PhpParser\Node\Stmt\Unset_ $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Unset(
            $node
        ));
    }

    protected function pStmt_InlineHTML(\PhpParser\Node\Stmt\InlineHTML $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_InlineHTML(
            $node
        ));
    }

    protected function pStmt_HaltCompiler(\PhpParser\Node\Stmt\HaltCompiler $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_HaltCompiler(
            $node
        ));
    }

    protected function pStmt_Nop(\PhpParser\Node\Stmt\Nop $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Nop(
            $node
        ));
    }

    protected function pStmt_Block(\PhpParser\Node\Stmt\Block $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pStmt_Block(
            $node
        ));
    }

    protected function pObjectProperty(\PhpParser\Node $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pObjectProperty(
            $node
        ));
    }

    protected function pDereferenceLhs(\PhpParser\Node $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pDereferenceLhs(
            $node
        ));
    }

    protected function pCallLhs(\PhpParser\Node $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pCallLhs($node));
    }

    protected function pNewOperand(\PhpParser\Node $node): string
    {
        $nodeId = $node->getAttribute(\App\Enum\AttributeKey::NODE_ID);
        return sprintf('<a href="#" wire:click.prevent="$dispatch(\'select_node\', {
            nodeId: %s,
        })" %s>%s</a>', $nodeId, $this->activeNodeId == $nodeId ? 'class="active-node"' : '', parent::pNewOperand(
            $node
        ));
    }
}
