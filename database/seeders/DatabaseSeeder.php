<?php

namespace Database\Seeders;

use App\Models\Carrito;
use App\Models\Contacto;
use App\Models\Contenido;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Nosotros;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Pablo',
            'email' => 'pablo@osole.com',
            'role' => 'admin',
            'password' => bcrypt('pablopablo')
        ]);
        User::create([
            'name' => 'Feli',
            'email' => 'Feli@osole.com',
            'password' => bcrypt('pablopablo')
        ]);
        Logo::create([
            'path' => 'images/logologin.png',
            'seccion' => 'login',
        ]);
        Logo::create([
            'path' => 'images/logohome.png',
            'seccion' => 'home',
        ]);
        Logo::create([
            'path' => 'images/logonav.png',
            'seccion' => 'navbar',
        ]);
        Logo::create([
            'path' => 'images/logoblanco.png',
            'seccion' => 'footer',
        ]);
        Metadato::create([
            'seccion' => 'home',
            'keyword' => 'inicio, bienvenida, principal',
            'descripcion' => 'Sección principal de la página de inicio con información destacada.',
        ]);
        Metadato::create([
            'seccion' => 'nosotros',
            'keyword' => 'quiénes somos, empresa, equipo',
            'descripcion' => 'Sección que describe la historia, misión y valores de la empresa.',
        ]);
        Metadato::create([
            'seccion' => 'productos',
            'keyword' => 'catálogo, artículos, servicios',
            'descripcion' => 'Sección dedicada a mostrar los productos o servicios ofrecidos.',
        ]);
        Metadato::create([
            'seccion' => 'novedades',
            'keyword' => 'noticias, actualizaciones, blog',
            'descripcion' => 'Sección con las últimas noticias y actualizaciones de la empresa.',
        ]);
        Metadato::create([
            'seccion' => 'contacto',
            'keyword' => 'formulario, dirección, teléfono',
            'descripcion' => 'Sección con información de contacto y formulario para consultas.',
        ]);
        Contacto::create([
            'direccion' => 'Calle 129 2929 (ex, Carlos Gardel) B1651 Villa Libertad, Buenos Aires',
            'email' => 'info@industriarpc.com.ar',
            'telefonouno' => '011 6589-6064',
            'telefonodos' => '011 5564-8230',
            'iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3284.880932402293!2d-58.56170319359548!3d-34.58187921234347!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcb9d7ba676873%3A0xe9ab481b2dcbc7a9!2sIndustria%20R.P.C.!5e0!3m2!1ses-419!2sar!4v1744740555596!5m2!1ses-419!2sar" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
        ]);
        Nosotros::create([
            'path' => 'images/nosotros.png',
            'titulo' => '¿Quiénes somos?',
            'descripcion' => 'Industria R.P.C. es una empresa dedicada a la fabricación de accesorios para sanitarios, comprometida con la calidad, la innovación y la satisfacción de nuestros clientes. Desde nuestros inicios, trabajamos con la premisa de ofrecer soluciones funcionales y duraderas que se adapten a las necesidades del mercado, combinando tecnología, diseño y materiales de alta resistencia. Nuestra misión es desarrollar y producir accesorios para sanitarios que mejoren la experiencia y el confort de los usuarios, garantizando altos estándares de calidad y eficiencia en cada uno de nuestros productos. Buscamos optimizar cada etapa del proceso productivo, apostando por la mejora continua y la sostenibilidad en nuestras prácticas industriales. Aspiramos a consolidarnos como una empresa referente en el sector, reconocida por la confiabilidad de nuestros productos y la innovación en el diseño y la funcionalidad de nuestros accesorios. Visualizamos un futuro en el que Industria R.P.C. sea sinónimo de excelencia, impulsando el desarrollo del rubro con propuestas que marquen la diferencia en el mercado.',
            'imagen1' => 'images/mision.png',
            'titulo1' => 'Misión',
            'descripcion1' => 'Desarrollar y fabricar accesorios para sanitarios de alta calidad, ofreciendo soluciones innovadoras y funcionales que mejoren la experiencia de los usuarios. Nos comprometemos con la excelencia en cada etapa del proceso, garantizando productos duraderos y eficientes que se adapten a las necesidades del mercado.',
            'imagen2' => 'images/vision.png',
            'titulo2' => 'Visión',
            'descripcion2' => 'Ser una empresa líder en la industria de accesorios para sanitarios, reconocida por la confiabilidad, el diseño y la innovación de nuestros productos. Buscamos expandir nuestra presencia en el mercado, apostando por el desarrollo tecnológico y la mejora continua para ofrecer soluciones cada vez más eficientes y sostenibles.',
            'imagen3' => 'images/valores.png',
            'titulo3' => 'Valores',
            'descripcion3' => 'Nos guiamos por la responsabilidad, la integridad y el compromiso con la calidad en cada uno de nuestros productos. Promovemos la innovación como pilar fundamental de nuestro crecimiento y apostamos por la sostenibilidad en nuestros procesos para contribuir a un futuro más eficiente y respetuoso con el medioambiente.',
        ]);
        Contenido::create([
            'path' => 'images/contenido.png',
            'titulo' => 'Nosotros',
            'descripcion' => 'Industria R.P.C. es una empresa dedicada a la fabricación de accesorios para sanitarios, comprometida con la calidad, la innovación y la satisfacción de nuestros clientes. Desde nuestros inicios, trabajamos con la premisa de ofrecer soluciones funcionales y duraderas que se adapten a las necesidades del mercado, combinando tecnología, diseño y materiales de alta resistencia. Nuestra misión es desarrollar y producir accesorios para sanitarios que mejoren la experiencia y el confort de los usuarios, garantizando altos estándares de calidad y eficiencia en cada uno de nuestros productos. Buscamos optimizar cada etapa del proceso productivo, apostando por la mejora continua y la sostenibilidad en nuestras prácticas industriales.',
        ]);
        Carrito::create([
            'descripcion' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        ]);
    }
}
