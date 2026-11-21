<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class News extends Model {
 use SoftDeletes;
 protected $table='news';
 protected $fillable=['title','slug','excerpt','body','status','published_at','cover_path'];
 protected function casts(): array {return ['published_at'=>'datetime'];}
 public function getRouteKeyName(): string {return 'slug';}
}
