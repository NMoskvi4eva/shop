<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\AuditLog;

class DesertShopTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_1_guest_cannot_access_admin_panel()
    {
        $response = $this->get('/admin/products');
        $response->assertStatus(302);
        // Адаптовано під ваш middleware авторизації
        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_2_regular_user_cannot_access_admin_panel()
    {
        $user = User::factory()->create(['email' => 'user@gmail.com']);
        $response = $this->actingAs($user)->get('/admin/products');$response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function test_3_admin_can_access_admin_panel()
    {
        $admin = User::factory()->create(['email' => 'admin@gmail.com']);
        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_4_checkout_fails_if_cart_is_empty()
    {
        $response = $this->post('/checkout', [
            'name' => 'Надія',
            'phone' => '+380953233376',
            'cart_data' => json_encode([])
        ]);

        $response->assertStatus(302);
        $this->assertEquals(0, Order::count());
    }

    /** @test */
    public function test_5_checkout_calculates_correct_total_amount()
    {
        $cartData = [
            ['name' => 'Еклер', 'price' => 250, 'quantity' => 2],
            ['name' => 'Торт Наполеон', 'price' => 1500, 'quantity' => 1]
        ];

        $response = $this->post('/checkout', [
            'name' => 'Надія Олександрівна',
            'phone' => '+380953233376',
            'cart_data' => json_encode($cartData)
        ]);

        $this->assertDatabaseHas('orders', [
            'name' => 'Надія Олександрівна',
            'total_amount' => 2000
        ]);
    }

    /** @test */
    public function test_6_checkout_generates_liqpay_redirect_view()
    {
        $cartData = [['name' => 'Макарун', 'price' => 100, 'quantity' => 5]];

        $response = $this->post('/checkout', [
            'name' => 'Тест Покупець',
            'phone' => '+380000000000',
            'cart_data' => json_encode($cartData)
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('checkout.redirect');
    }

    /** @test */
    public function test_7_adding_product_creates_audit_log()
    {
        $admin = User::factory()->create(['email' => 'admin@gmail.com']);
        $category = Category::create(['name' => 'Торти']);

        $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Тестовий Чизкейк',
            'price' => 500,
            'description' => 'Смачний опис тестового десерту',
            'category_id' => $category->id
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_email' => 'admin@gmail.com',
            'action' => 'Додавання товару',
        ]);
    }

    /** @test */
    public function test_8_deleting_product_creates_audit_log()
    {
        $admin = User::factory()->create(['email' => 'admin@gmail.com']);
        $category = Category::create(['name' => 'Торти']);
        $product = Product::create([
            'name' => 'Торт на видалення',
            'price' => 600,
            'description' => 'Опис десерту на видалення',
            'category_id' => $category->id
        ]);

        $this->actingAs($admin)->delete("/admin/products/{$product->id}");

        $this->assertDatabaseHas('audit_logs', [
            'user_email' => 'admin@gmail.com',
            'action' => 'Видалення товару',
        ]);
    }

    /** @test */
    public function test_9_changing_order_status_creates_audit_log()
    {
        $admin = User::factory()->create(['email' => 'admin@gmail.com']);
        $order = Order::create([
            'name' => 'Іван',
            'phone' => '+380999999999',
            'total_amount' => 1200,
            'status' => 'Очікує оплату',
            'liqpay_order_id' => 'order_123',
            'products_list' => 'Торт'
        ]);

        $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", [
            'status' => 'Готується'
        ]);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'Готується']);
        $this->assertDatabaseHas('audit_logs', [
            'user_email' => 'admin@gmail.com',
            'action' => 'Зміна статусу замовлення'
        ]);
    }

/** @test */
    public function test_10_products_are_sorted_by_latest_first()
    {
        $admin = User::factory()->create(['email' => 'admin@gmail.com']);
        $category = Category::create(['name' => 'Торти']);

        // 1. Подорожуємо на 2 дні в минуле і створюємо СТАРИЙ торт
        $this->travelTo(now()->subDays(2));
        $oldProduct = Product::create([
            'name' => 'Старий торт', 
            'price' => 100, 
            'description' => 'Старий опис',
            'category_id' => $category->id, 
        ]);

        // 2. Повертаємо час у СЬОГОДНІ (на 2 дні пізніше) і створюємо НОВИЙ торт
        $this->travelBack(); 
        $newProduct = Product::create([
            'name' => 'Новий торт', 
            'price' => 200, 
            'description' => 'Новий опис',
            'category_id' => $category->id, 
        ]);

        // Виконуємо запит
        $response = $this->actingAs($admin)->get('/admin/products');

        $productsInView = $response->viewData('products');
        
        // Тепер новий торт має свіжішу дату створення і буде першим
        $this->assertEquals($newProduct->id, $productsInView->first()->id);
    }
}