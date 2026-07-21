<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DessertSeeder extends Seeder
{
    public function run(): void
{
    // Тимчасово вимикаємо перевірку зв'язків
    Schema::disableForeignKeyConstraints();

    Product::truncate();
    Category::truncate();

    // Вмикаємо перевірку назад
    Schema::enableForeignKeyConstraints();

        // Створюємо категорії десертів відповідно до їх ID
        $torti = Category::create(['name' => 'Авторські торти']);       // ID: 1
        $musovi = Category::create(['name' => 'Мусові & кето десерти']); // ID: 2
        $ekleri = Category::create(['name' => 'Тістечка & еклери']);     // ID: 3
        $makaruni = Category::create(['name' => 'Макаруни & сети']);     // ID: 4

        // --- Категорія: Макаруни & сети (ID: 4) ---
        Product::create([
            'category_id' => $makaruni->id,
            'name' => 'Сет Капкейків Шоколадна Карамель',
            'description' => 'Шоколадні капкейки з рідкою карамеллю всередині та шоколадним кремом.',
            'price' => 720.00,
            'image' => 'images/set.jpg'
        ]);

        Product::create([
            'category_id' => $makaruni->id,
            'name' => 'Сет Капкейків Червоний Оксамит',
            'description' => 'Набір із 4-х розкішних капкейків із вишневим кюлі та шапкою крем-чизу.',
            'price' => 720.00,
            'image' => 'images/2.jpg'
        ]);

        Product::create([
            'category_id' => $makaruni->id,
            'name' => 'Асорті Макарунів Мікс',
            'description' => 'Колекція смаків: ароматна фісташка, гірська лаванда, лимон, ніжна троянда.',
            'price' => 560.00,
            'image' => 'images/macarons.jpg'
        ]);

        // --- Категорія: Тістечка & еклери (ID: 3) ---
        Product::create([
            'category_id' => $ekleri->id,
            'name' => 'Тірамісу Класико',
            'description' => 'Печиво савоярді, міцне еспресо, лікер Amaretto та маскарпоне.',
            'price' => 450.00,
            'image' => 'images/tiramisu.jpg'
        ]);

        Product::create([
            'category_id' => $ekleri->id,
            'name' => 'Еклер Солона Карамель',
            'description' => 'Шовковистий домашній карамельний крем з рожевою гімалайською сіллю.',
            'price' => 250.00,
            'image' => 'images/caramel2.jpg'
        ]);

        Product::create([
            'category_id' => $ekleri->id,
            'name' => 'Французький Еклер Шоколад',
            'description' => 'Крем із насиченого бельгійського шоколаду 70% у темній глазурі.',
            'price' => 250.00,
            'image' => 'images/ekler2.jpg'
        ]);

        Product::create([
            'category_id' => $ekleri->id,
            'name' => 'Французький Еклер Ваніль',
            'description' => 'Традиційне тісто шу з ніжним вершковим кремом на ванілі Bourbon.',
            'price' => 240.00,
            'image' => 'images/ekler.jpg'
        ]);

        Product::create([
            'category_id' => $ekleri->id,
            'name' => 'Тістечко Рожевий Оксамит',
            'description' => 'Свіжі лісові ягоди та ніжна начинка з вишуканого вершкового сиру.',
            'price' => 640.00,
            'image' => 'images/ro.jpg'
        ]);

        // --- Категорія: Мусові & кето десерти (ID: 2) ---
        Product::create([
            'category_id' => $musovi->id,
            'name' => 'Мусове Серце Полуничне',
            'description' => 'Ніжний полуничний мус у формі дзеркального глянцевого серця.',
            'price' => 280.00,
            'image' => 'images/st2.jpg'
        ]);

        Product::create([
            'category_id' => $musovi->id,
            'name' => 'Шоколадний Фондан Кето',
            'description' => 'Низьковуглеводний гарячий десерт з рідким центром із шоколаду 85%.',
            'price' => 320.00,
            'image' => 'images/shoko.jpg'
        ]);

        Product::create([
            'category_id' => $musovi->id,
            'name' => 'Лавандово-Чорничний Мус',
            'description' => 'Квітковий мус із французькою лавандою, чорницею та мигдалевою основою.',
            'price' => 2050.00,
            'image' => 'images/lavanda.jpg'
        ]);

        Product::create([
            'category_id' => $musovi->id,
            'name' => 'Екзотик Манго-Кокос',
            'description' => 'Кето-десерт: кокосове суфле на еритритолі з натуральним пюре манго.',
            'price' => 1900.00,
            'image' => 'images/mango.jpg'
        ]);

        Product::create([
            'category_id' => $musovi->id,
            'name' => 'Фісташковий Мус з Малиною',
            'description' => 'Шовковистий мус на сицилійській фісташці з соковитим ягідним центром.',
            'price' => 2150.00,
            'image' => 'images/sf.jpg'
        ]);

        // --- Категорія: Авторські торти (ID: 1) ---
        Product::create([
            'category_id' => $torti->id,
            'name' => 'Грушевий Тарт з Амаретто',
            'description' => 'Пісочна кошик з мигдалевим кремом франжипан та соковитою печеною грушею.',
            'price' => 900.00,
            'image' => 'images/pear_tart.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Лимонний Меренговий Тарт',
            'description' => 'Хрустке пісочне тісто сабле, кислий лимонний курд та облачна хмаринка меренги.',
            'price' => 850.00,
            'image' => 'images/lemon_tart.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Святковий Нарізний Торт',
            'description' => 'Яскраві шари повітряного тіста з соковитим ягідним сиропом.',
            'price' => 800.00,
            'image' => 'images/cake.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Грільяжний Шоколадний',
            'description' => 'Карамельний горіховий грільяж з шоколадним ганашем.',
            'price' => 1900.00,
            'image' => 'images/gr.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Матча-Торт з Мусом',
            'description' => 'Японський зелений чай матча з вершковим білим шоколадом та лаймовим кулі.',
            'price' => 2100.00,
            'image' => 'images/mt.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Чизкейк Полуничний Нью-Йорк',
            'description' => 'Ніжна сирна основа, пісочний корж та дзеркальне полуничне желе.',
            'price' => 1600.00,
            'image' => 'images/sch.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Торт Червоний Оксамит',
            'description' => 'Елегантний червоний бісквіт з соковитою вишневою начинкою та крем-чизом.',
            'price' => 1850.00,
            'image' => 'images/redo.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Наполеон Ванільний Бурбон',
            'description' => 'Найніжніше листкове тісто з густим заварним кремом на ванілі Bourbon.',
            'price' => 1500.00,
            'image' => 'images/napoleon.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Медовик з Грушею',
            'description' => 'Ароматні медові коржі зі сметанно-вершковим кремом та карамелізованою грушею.',
            'price' => 1400.00,
            'image' => 'images/mg.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Київський Торт (Авторський)',
            'description' => 'Повітряне горіхове безе з добірним фундуком та оксамитовим кремом Шарлотт.',
            'price' => 1750.00,
            'image' => 'images/kiev.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Шоколадний Трюфель',
            'description' => 'Насичений бельгійський шоколад із трюфельною начинкою та лікером Baileys.',
            'price' => 2200.00,
            'image' => 'images/st.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Крем-Карамель',
            'description' => 'Карамельні коржі, соковита солона карамель та хрустке горіхове праліне.',
            'price' => 2100.00,
            'image' => 'images/caramel.jpg'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Рожева Пелюстка',
            'description' => 'Ніжний бісквіт з екстрактом дамаської троянди, малиновим конфі та кремом.',
            'price' => 1950.00,
            'image' => 'images/rp.png'
        ]);

        Product::create([
            'category_id' => $torti->id,
            'name' => 'Мандариновий Чизкейк',
            'description' => 'Легкий вершковий мус на ніжній основі з кулі зі стиглих мандаринів.',
            'price' => 1800.00,
            'image' => 'images/mch.jpeg'
        ]);
    }
}