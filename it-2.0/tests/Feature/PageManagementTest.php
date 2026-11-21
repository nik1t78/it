<?php
namespace Tests\Feature;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class PageManagementTest extends TestCase {
 use RefreshDatabase;
 public function test_editor_can_create_and_publish_a_page(): void {
  $editor=User::query()->create(['name'=>'Редактор','email'=>'page-editor@example.test','password'=>'Strong-password-2026','role'=>'editor','is_active'=>true]);
  $this->actingAs($editor)->post(route('admin.pages.store'),[
   'title'=>'О мерах поддержки','slug'=>'mery-podderzhki','body'=>'Информация для жителей','status'=>'published','sort_order'=>10,'show_in_menu'=>'1',
  ])->assertRedirect(route('admin.pages.index'));
  $this->assertDatabaseHas('pages',['slug'=>'mery-podderzhki','status'=>'published','show_in_menu'=>1]);
  $this->get(route('pages.show',['page'=>'mery-podderzhki']))->assertOk()->assertSee('Информация для жителей');
 }
 public function test_draft_page_is_not_publicly_accessible(): void {
  $page=Page::query()->create(['title'=>'Скрытая страница','slug'=>'hidden-page','body'=>'Секрет','status'=>'draft']);
  $this->get(route('pages.show',['page'=>$page->slug]))->assertNotFound();
 }
}
