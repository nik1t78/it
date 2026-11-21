<?php
namespace Tests\Feature;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class NewsManagementTest extends TestCase {
 use RefreshDatabase;
 public function test_editor_can_create_news_and_public_can_read_it(): void {
  $editor=User::query()->create(['name'=>'Редактор','email'=>'editor@example.test','password'=>'Strong-password-2026','role'=>'editor','is_active'=>true]);
  $this->actingAs($editor)->post(route('admin.news.store'),[
   'title'=>'Важная новость','slug'=>'vazhnaya-novost','excerpt'=>'Краткий текст','body'=>'Полный текст публикации','status'=>'published',
  ])->assertRedirect(route('admin.news.index'));
  $this->assertDatabaseHas('news',['slug'=>'vazhnaya-novost','status'=>'published']);
  $this->get(route('news.show',['news'=>'vazhnaya-novost']))->assertOk()->assertSee('Полный текст публикации');
 }
 public function test_draft_news_is_not_publicly_visible(): void {
  $news=News::query()->create(['title'=>'Черновик','slug'=>'draft-story','body'=>'Не показывать','status'=>'draft']);
  $this->get(route('news.show',['news'=>$news->slug]))->assertNotFound();
 }
}
