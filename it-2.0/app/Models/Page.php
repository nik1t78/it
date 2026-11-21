<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Page extends Model {
 use SoftDeletes;
 protected $fillable=['parent_id','title','slug','body','status','sort_order','show_in_menu'];
 protected function casts(): array {return ['show_in_menu'=>'boolean','sort_order'=>'integer'];}
 public function getRouteKeyName(): string {return 'slug';}
 public function parent(){return $this->belongsTo(self::class,'parent_id');}
 public function children(){return $this->hasMany(self::class,'parent_id')->orderBy('sort_order');}
}
