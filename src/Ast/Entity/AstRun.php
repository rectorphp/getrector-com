<?php

declare(strict_types=1);

namespace Rector\Website\Ast\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAstRun
 */
final class AstRun extends Model
{
    /**
     * To keep database static
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $guarded = ['id', 'hash'];

    /**
     * @var string[]
     */
    protected $fillable = ['content'];

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @api used in blade
     */
    public function hasRun(): bool
    {
        return $this->content !== '';
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
        $this->hash = sha1($content);
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}
